<?php

use App\Http\Controllers\BraceletController;
use App\Http\Controllers\OrderController;
use App\Http\Livewire\Bracelet\Show as BraceletShow;
use App\Http\Livewire\Order\Show as OrderShow;
use App\Mail\OrderCreatedAdmin;
use App\Models\Order;
use Illuminate\Support\Facades\Route;

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

Route::get('/mailtest', function () {
    // return view('welcome');
    $order = Order::first();
    return new OrderCreatedAdmin($order);
});

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
});
