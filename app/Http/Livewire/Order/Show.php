<?php

namespace App\Http\Livewire\Order;

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
     * Event listeners
     */
    protected $listeners = [
        'unlinkInterstitial',
        'resendConfirmation' => 'emitConfirmation',
        'braceletLinked' => '$refresh',
        'braceletUnlinked' => '$refresh',
        'generateSquareLink' => 'emitSquareLink',
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
        $total    = $subtotal + ($subtotal * config('constants.square.transaction_fee')) + config('constants.square.transaction_fee_fixed');
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
                        ->amount((int) ceil($total * 100))
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

    public function render()
    {
        return view('livewire.order.show')
            ->layout('layouts.app', ['header' => 'Order Details' ]);
    }
}
