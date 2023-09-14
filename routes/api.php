<?php

use App\Models\Bracelet;
use App\Models\Customer;
use App\Models\Order;
use App\Providers\OrderCreated;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Square\SquareClient;
use Square\Environment;
use Square\Models\Builders\AddressBuilder;
use Square\Models\Builders\CheckoutOptionsBuilder;
use Square\Models\Builders\CreatePaymentLinkRequestBuilder;
use Square\Models\Builders\MoneyBuilder;
use Square\Models\Builders\PrePopulatedDataBuilder;
use Square\Models\Builders\QuickPayBuilder;
use Square\Models\Currency;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/bracelet-availability', function () {
    // get bracelets of "online" group that aren't attached to an order
    $bracelets = Bracelet::where('group', 'like', "%online%")
        ->orWhere('group', 'like', "%Online%")
        ->get()
        ->filter(function ($bracelet) {
            return $bracelet->order === null;
        });

    return response()->json([
        'available' => $bracelets->count(),
    ]);
});

Route::post('/new-order', function (Request $request) {
    $bracelets             = Bracelet::where('group', 'like', "%online%")
        ->orWhere('group', 'like', "%Online%")
        ->get()
        ->filter(function ($bracelet) {
            return $bracelet->order === null;
        });

    // First create an order
    $validData = $request->validate([
        'firstName' => 'required|string',
        'lastName' => 'required|string',
        'email' => 'required|email',
        'phone' => 'nullable|string|max:255',
        'amount' => [
            'required',
            'integer',
            'min:1',
            'max:' . $bracelets->count(),
        ],
    ]);

    // create or update customer
    $customer = Customer::firstOrCreate(
        [
            'email' => $validData['email'],
        ],
        [
            // strip out country code & any dashes
            'phone_number' => preg_replace('/^(\+1)|-/', '', $validData['phone']),
            'first_name' => $validData['firstName'],
            'last_name' => $validData['lastName'],
        ],
    );

    // create an order
    $order = $customer->orders()->create([
        'order_type' => 'online',
        'order_status' => 'pending',
    ]);

    // add bracelets to order
    $attachable_bracelets = $bracelets->take($validData['amount']);

    // add bracelets to order
    foreach ($attachable_bracelets as $bracelet) {
        // update the bracelet's name & status
        $bracelet->update([ 'status' => 'registered' ]);
        $order->bracelets()->save($bracelet);
    }

    /**
     * Prepare to create Square checkout
     */
    $subtotal = $attachable_bracelets->count() * config('constants.square.bracelet_cost');
    $total    = round(($subtotal + config('constants.square.transaction_fee_fixed')) / (1 - config('constants.square.transaction_fee')), 2);
    $id_key   = uniqid();

    $client = new SquareClient([
        'accessToken' => env('SQUARE_ACCESS_TOKEN'),
        'environment' => env('SQUARE_ENVIRONMENT') === 'production' ? Environment::PRODUCTION : Environment::SANDBOX,
    ]);
    $checkoutApi = $client->getCheckoutApi();

    $prePopulatedData = PrePopulatedDataBuilder::init()
        ->buyerEmail($customer->email);
    if ($customer->phoneForSquareApi()) {
        $prePopulatedData->buyerPhoneNumber($customer->phoneForSquareApi());
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
                    ->firstName($customer->first_name)
                    ->lastName($customer->last_name)
                    ->build()
            )
            ->build()
    )
        ->checkoutOptions(
            CheckoutOptionsBuilder::init()
                ->redirectUrl(env('SQUARE_REDIRECT_URL') . $order->number)
                ->build()
        )
        ->build();

    $apiResponse = $checkoutApi->createPaymentLink($body);

    // Save the idempotency key to the order (to retry creating checkout link if needed);
    $order->update([ 'id_key' => $id_key ]);

    if ($apiResponse->isSuccess()) {
        $plResponse = $apiResponse->getResult();
        $pl = $plResponse->getPaymentLink();
        $new_notes = 'payment_link_id:' . $pl->getId() . '|one_time_action:needs_payment';

        // Save the payment link and payment ID to the order for reference
        $order->update([
            'payment_link' => $pl->getUrl(),
            'payment_status' => 'pending',
            'square_order_id' => $pl->getOrderId(),
            'order_notes' => $order->order_notes ? $order->order_notes . $new_notes : $new_notes,
        ]);

        return response()->json([
            'order_number' => $order->number,
            'checkout_url' => $pl->getUrl(),
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

        $order->update([
            'order_notes' => $err_message,
        ]);

        return response()->json([
            'error' => ($order->order_notes ? $order->order_notes . '|' : '') . $err_message,
        ]);
    }
});

Route::get('/confirm-order/{order}', function (Order $order) {
    if ($order->order_type !== 'online' || $order->confirmation_seen) {
        return response()->json([
            'error' => 'Order cannot be displayed.',
        ]);
    } else {
        $order->update([
            'confirmation_seen' => true,
        ]);

        return response()->json([
            'order_number' => $order->number,
            'order_email'  => $order->customer->email,
            'order_amount' => $order->bracelets->count(),
        ]);
    }
});

Route::post('/update-order', function (Request $request) {
    if ($request['type'] === 'order.updated') {
        $data      = $request['data'];
        $square_id = $data['id'];
        $updated   = $data['object']['order_updated'];

        $order = Order::where('square_order_id', $square_id)->first();

        if ($order) {
            $needs_update = 'complete' !== $order->order_status && $order->id_key !== $request['event_id'];

            if ($needs_update) {
                $order_state = Str::lower($updated['state']);
                $order->update([
                    'order_status' => 'open' === $order_state ? 'complete' : ('draft' === $order_state ? 'pending' : 'n/a' ),
                    'id_key' => $request['event_id'],
                ]);
            }
        }
    }

    if ($request['type'] === 'payment.updated') {
        $data      = $request['data'];
        $payment   = $data['object']['payment'];
        $square_id = $payment['order_id'];

        $order = Order::where('square_order_id', $square_id)->first();

        if ($order) {
            $notes_array = explode('|', $order->order_notes);
            $updated = false;

            if (
                array_search(
                    'payment_idempotency_key:' . $request['event_id'],
                    $notes_array
                ) === false &&
                $order->payment_status !== Str::lower($payment['status'])
            ) {
                $notes_array[] = 'payment_idempotency_key:' . $request['event_id'];
                $updated = true;
            }


            if ( array_search( 'one_time_action:needs_payment', $notes_array) !== false ) {
                // remove the one_time_action:needs_payment flag
                $updated = true;
                $notes_array = array_filter(
                    $notes_array,
                    function ($note) {
                        return $note !== 'one_time_action:needs_payment';
                    }
                );

                OrderCreated::dispatch($order);
            }

            if ($updated) {
                $order->update([
                    'payment_status' => Str::lower($payment['status']),
                    'order_notes' => join( '|', $notes_array ),
                ]);
            }
        }
    }

    return response()->json([
        'success' => 'Webhook received.',
    ]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
