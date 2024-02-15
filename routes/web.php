<?php

use App\Http\Controllers\BraceletController;
use App\Http\Controllers\OrderController;
use App\Http\Livewire\Bracelet\Show as BraceletShow;
use App\Http\Livewire\Order\Show as OrderShow;
use App\Http\Livewire\Registrations\All as AllRegistrations;
use App\Http\Livewire\Registrations\Show as ShowRegistration;
use App\Mail\EventRegistrationConfirmationAdmin;
use App\Mail\EventRegistrationConfirmationCustomer;
use App\Mail\OrderCreatedAdmin;
use App\Mail\SquareNotification;
use App\Models\EventRegistration;
use App\Models\Order;
use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/mailtest', function () {
//     $registration = EventRegistration::first();
//     ray($registration);
//     return new EventRegistrationConfirmationCustomer($registration);
// });

// Route::get('/qrtest', function () {
//     $registration = EventRegistration::first();
//     // return qr code that goes to registration check-in url https://revivalmovement.org/registrations/1/checkin
//     return QrCode::size(300)->generate(route('registrations.checkin', $registration));
// });

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/bracelets', [BraceletController::class, 'dashboard'])->name('bracelets.dashboard');
    Route::get('/bracelets/all', [BraceletController::class, 'index'])->name('bracelets.index');
    Route::get('/bracelets/{bracelet}', BraceletShow::class)->name('bracelets.show');
    Route::get('/orders', [OrderController::class, 'dashboard'])->name('orders.dashboard');
    Route::get('/orders/all', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', OrderShow::class)->name('orders.show');
    Route::get('/registrations/all', AllRegistrations::class)->name('registrations.dashboard');
    Route::get('/registrations/{registration}', ShowRegistration::class)->name('registrations.show');

    Route::get('/registrations/{registration}/checkin', function(EventRegistration $registration) {
        $registration->checkin();
        return redirect()->route('registrations.confirm', $registration);
    })->name('registrations.checkin');

    Route::get('/registrations/{registration}/confirm', function(EventRegistration $registration) {
        if ($registration->checkedin_at === null) {
            return redirect('/');
        }

        return view('registrations.confirm', compact('registration'));
    })->name('registrations.confirm');
});
