<?php

namespace App\Http\Livewire\Order;

use App\Events\OrderCancelled;
use App\Events\SquareLinkGenerated;
use App\Models\Order;
use App\Providers\ConfirmationResend;
use App\Providers\OrderCreated;
use Carbon\Carbon;
use Livewire\Component;
use Square\Environment;
use Square\Models\Builders\AddressBuilder;
use Square\Models\Builders\CheckoutOptionsBuilder;
use Square\Models\Builders\CreatePaymentLinkRequestBuilder;
use Square\Models\Builders\MoneyBuilder;
use Square\Models\Builders\PrePopulatedDataBuilder;
use Square\Models\Builders\QuickPayBuilder;
use Square\Models\Currency;
use Square\SquareClient;

class Show extends Component
{
    /**
     * The route parameter's value.
     *
     * @var Order
     */
    public Order $order;

    /**
     * Temporary bracelet id to unlink.
     *
     * @var string
     */
    public string $braceletId;

    /**
     * Flag to confirm if the user wants to remove the bracelet.
     *
     * @var bool
     */
    public $confirmingBraceletUnlink = false;

    /**
     * Flag to confirm if the user wants to delete the order.
     *
     * @var bool
     */
    public $confirmingOrderDeletion = false;

    /**
     * Flag to confirm if the user wants to cancel the order.
     *
     * @var bool
     */
    public $confirmingOrderCancellation = false;

    /**
     * Event listeners
     */
    protected $listeners = [
        'unlinkInterstitial',
        'resendConfirmation' => 'emitConfirmation',
        'braceletLinked' => '$refresh',
        'braceletUnlinked' => '$refresh',
        'generateSquareLink' => 'emitSquareLink',
        'sendPaymentLink' => 'emitPaymentEmail',
        'fetchPaymentLink' => 'retrievePaymentLink',
    ];

    /**
     * Unlink bracelet interstitial
     *
     * @param int $braceletId
     */
    public function unlinkInterstitial($braceletId)
    {
        $this->confirmingBraceletUnlink = true;
        $this->braceletId = $braceletId;
    }

    /**
     * Emit confirmation email evetn
     */
    public function paymentEmail()
    {
        SquareLinkGenerated::dispatch($this->order);
        $this->order->refresh();
    }

    /**
     * Emit confirmation email evetn
     */
    public function emitConfirmation()
    {
        ConfirmationResend::dispatch($this->order);
        $this->order->refresh();
        // $this->emit('refreshComponent');
    }

    /**
     * Emit Square link generation
     */
    public function emitSquareLink()
    {
        $subtotal = $this->order->bracelets()->count() * config('constants.square.bracelet_cost');
        $total    = round(($subtotal + config('constants.square.transaction_fee_fixed')) / (1 - config('constants.square.transaction_fee')), 2);
        $id_key   = uniqid();

        $client = new SquareClient([
            'accessToken' => env('SQUARE_ACCESS_TOKEN'),
            'environment' => env('SQUARE_ENVIRONMENT') === 'production' ? Environment::PRODUCTION : Environment::SANDBOX,
        ]);
        $checkoutApi = $client->getCheckoutApi();

        $prePopulatedData = PrePopulatedDataBuilder::init()
            ->buyerEmail($this->order->customer->email);
        if ($this->order->customer->phoneForSquareApi()) {
            $prePopulatedData->buyerPhoneNumber($this->order->customer->phoneForSquareApi());
        }

        $body = CreatePaymentLinkRequestBuilder::init()
            ->idempotencyKey($id_key)
            ->quickPay(
                QuickPayBuilder::init(
                    config('constants.square.item_name'),
                    MoneyBuilder::init()
                        ->amount((int) ($total * 100))
                        ->currency(Currency::USD)
                        ->build(),
                    env('SQUARE_LOCATION_ID'),
                )
                    ->build()
            )
            ->prePopulatedData(
                $prePopulatedData
                    ->buyerAddress(
                        AddressBuilder::init()
                            ->firstName($this->order->customer->first_name)
                            ->lastName($this->order->customer->last_name)
                            ->build()
                    )
                    ->build()
            )
            ->checkoutOptions(
                CheckoutOptionsBuilder::init()
                    ->redirectUrl(env('SQUARE_REDIRECT_URL') . $this->order->number)
                    ->build()
            )
            ->build();

        $apiResponse = $checkoutApi->createPaymentLink($body);
        $this->order->update(['id_key' => $id_key]);

        if ($apiResponse->isSuccess()) {
            $plResponse = $apiResponse->getResult();
            $pl = $plResponse->getPaymentLink();

            // Save the payment link and payment ID to the order for reference
            $this->order->update([
                'payment_link' => $pl->getUrl(),
                'payment_status' => 'pending',
                'square_order_id' => $pl->getOrderId(),
                'order_notes' => ($this->order->order_notes ? $this->order->order_notes . '|' : '') . 'one_time_action:needs_payment',
            ]);

            // Send user an email with the link
            SquareLinkGenerated::dispatch($this->order);
        } else {
            $errors = $apiResponse->getErrors();
            $err_message = Carbon::now()->format( 'Y-m-d H:i:s' ) . '--' . join(
                '; ',
                array_map(
                    function ($error) {
                        return $error->getField() ?
                            $error->getField() . ': ' . $error->getDetail() :
                            $error->getDetail();
                    },
                    $errors
                )
            );

            $this->order->update([
                'order_notes' => ($this->order->order_notes ? $this->order->order_notes . '|' : '') . $err_message,
            ]);
        }

        $this->order->refresh();
    }

    /**
     * Fet Square link
     */
    public function retrievePaymentLink()
    {
        ray('fetching payment link');
        $notes_array = explode('|', $this->order->order_notes);
        $client = new SquareClient([
            'accessToken' => env('SQUARE_ACCESS_TOKEN'),
            'environment' => env('SQUARE_ENVIRONMENT') === 'production' ? Environment::PRODUCTION : Environment::SANDBOX,
        ]);
        $checkoutApi = $client->getCheckoutApi();

        $apiResponse = $checkoutApi->listPaymentLinks();

        if ($apiResponse->isSuccess()) {
            // ray('success, will loop');
            $listPaymentLinksResponse = $apiResponse->getResult();

            do  {
                ray('looping');
                foreach ($listPaymentLinksResponse->getPaymentLinks() as $pl) {
                    if ($pl->getOrderId() !== $this->order->square_order_id) {
                        continue;
                    }

                    ray('found payment link, updating order');
                    array_push($notes_array, 'payment_link_id:' . $pl->getId());
                    $this->order->update(['order_notes' => join('|', $notes_array)]);
                }

                $listPaymentLinksResponse = $checkoutApi->listPaymentLinks($listPaymentLinksResponse->getCursor());
            } while ($listPaymentLinksResponse->getCursor() && array_search('payment_link_id:' . $pl->getId(), $notes_array) === false);

            if (preg_match('/payment_link_id:/', join('|', $notes_array)) === 0) {
                ray('payment link not found');
                $this->order->update([
                    'order_notes' => join('|', $notes_array) . '|fetch_payment_link_id_failed:payment_link_id_does_not_exist',
                ]);
            }
        } else {
            $errors = $apiResponse->getErrors();
            ray($errors);
            $notes_array[] = Carbon::now()->format( 'Y-m-d H:i:s' ) . '--' . join(
                '; ',
                array_map(
                    function ($error) {
                        return $error->getField() ?
                            $error->getField() . ': ' . $error->getDetail() :
                            $error->getDetail();
                    },
                    $errors
                )
            );

            $this->order->update([
                'order_notes' => join('|', $notes_array),
            ]);
        }

        $this->order->refresh();
    }

    /**
     * Unlink a bracelet from the order.
     *
     * @param int $braceletId
     */
    public function confirmUnlinkBracelet()
    {
        if (!$this->braceletId) {
            return;
        }

        /**
         * Remove the order reference from the bracelet model,
         *  reset the name & status, and save.
         */
        $bracelet = $this->order->bracelets()->find($this->braceletId);
        $bracelet->order_id = null;
        $bracelet->name = null;
        $bracelet->status = 'system';
        $bracelet->save();

        // Remove the bracelet from the order using the HasMany relationship
        $this->order->refresh();

        $this->confirmingBraceletUnlink = false;
    }

    public function deleteOrder()
    {
        // Unlink all bracelets from the order
        $this->order->bracelets()->each(function ($bracelet) {
            $bracelet->order_id = null;
            $bracelet->name = null;
            $bracelet->status = 'system';
            $bracelet->save();
        });

        $this->order->refresh()->delete();
        $this->confirmingOrderDeletion = false;
        $this->emit('orderDeleted');
        return redirect()->route('orders.index');
    }

    public function cancelOrder()
    {
        // Is the payment status 'completed' ?
        if ($this->order->order_type != 'online' || $this->order->payment_status === 'completed') {
            $this->confirmingOrderCancellation = false;
            return;
        }

        // Send API request to Square to delete the payment link
        $notes_array = explode('|', $this->order->order_notes);
        $pmt_note  = array_filter(
            $notes_array,
            function ($note) {
                return preg_match('/^payment_link_id:/', $note);
            }
        );


        if (empty($pmt_note)) {
            $this->order->update([
                'order_notes' => ($this->order->order_notes ? $this->order->order_notes . '|' : '') . 'order_cancel_failed:payment_link_id_not_found',
            ]);
            $this->confirmingOrderCancellation = false;
            return;
        }


        $link_id = preg_replace('/^payment_link_id:/', '', $pmt_note[0]);

        $client  = new SquareClient([
            'accessToken' => env('SQUARE_ACCESS_TOKEN'),
            'environment' => env('SQUARE_ENVIRONMENT') === 'production' ? Environment::PRODUCTION : Environment::SANDBOX,
        ]);
        $checkoutApi = $client->getCheckoutApi();

        $apiResponse = $checkoutApi->deletePaymentLink($link_id);

        if ($apiResponse->isSuccess()) {
            $this->order->update([
                'id_key'         => null,
                'payment_link'   => null,
                'payment_status' => 'cancelled',
                'order_notes'    => ($this->order->order_notes ? $this->order->order_notes . '|' : '') . 'order_cancelled:<<' . Carbon::now()->format( 'Y-m-d H:i:s' ) . '>>',
            ]);

        } else {
            $errors = $apiResponse->getErrors();
            $err_message = Carbon::now()->format( 'Y-m-d H:i:s' ) . '--' . join(
                '; ',
                array_map(
                    function ($error) {
                        return $error->getField() ?
                            $error->getField() . ': ' . $error->getDetail() :
                            $error->getDetail();
                    },
                    $errors
                )
            );

            $this->order->update([
                'order_notes' => ($this->order->order_notes ? $this->order->order_notes . '|' : '') . $err_message,
            ]);
        }

        // Unlink all bracelets from the order
        $this->order->bracelets()->each(function ($bracelet) {
            $bracelet->order_id = null;
            $bracelet->name = null;
            $bracelet->status = 'system';
            $bracelet->save();
        });

        OrderCancelled::dispatch($this->order);

        $this->confirmingOrderCancellation = false;
        $this->order->refresh();
    }

    public function render()
    {
        return view('livewire.order.show')
            ->layout('layouts.app', ['header' => 'Order Details' ]);
    }
}
