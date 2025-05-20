<?php

use App\Livewire\Home;
use App\Livewire\Shop\Payment;
use App\Livewire\User\Profile;
use App\Livewire\Shop\IndexCart;
use App\Livewire\Shop\IndexShop;
use App\Livewire\Shop\ShowProduct;
use App\Livewire\User\EditAddress;
use App\Livewire\Shop\OrderHistory;
use App\Livewire\User\IndexAddress;
use App\Livewire\Shop\IndexWishlist;
use App\Livewire\User\CreateAddress;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Livewire\Reparation\FormReparation;
use App\Livewire\Reparation\HistoryReparation;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Livewire\Reparations\ReparationPayment;

Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])
    ->name('auth.google.redirect');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])
    ->name('auth.google.callback');

Route::get('/', Home::class)
    ->name('home');

Route::get('/shop', IndexShop::class)
    ->name('shop.index');
Route::get('/shop/{slug}', ShowProduct::class)
    ->name('shop.show');

Route::get('/cart', IndexCart::class)
    ->name('cart.index')
    ->middleware('auth', 'role:user');

Route::get('/wishlist', IndexWishlist::class)
    ->name('wishlist.index')
    ->middleware('auth', 'role:user');

Route::get('/orders/history', OrderHistory::class)
    ->name('orders.history')
    ->middleware('auth', 'role:user');

Route::get('/orders/{order}/payment', Payment::class)
    ->name('orders.payment')
    ->middleware('auth', 'role:user');

Route::get('/payment/success/{orderId}', [PaymentController::class, 'paymentSuccess'])->name('orders.payment.success')->middleware('auth', 'role:user');

Route::post('/payment/notification', [PaymentController::class, 'handleNotification'])
    ->name('payment.notification');

Route::get('/reparations/history', HistoryReparation::class)
    ->name('reparations.history')
    ->middleware('auth', 'role:user');

Route::get('/reparations/{reparation}/payment', ReparationPayment::class)
    ->name('reparations.payment')
    ->middleware('auth', 'role:user');

Route::get('/reparations/{reparationId}/payment/success', [PaymentController::class, 'reparationPaymentSuccess'])
    ->name('reparations.payment.success')
    ->middleware('auth', 'role:user');

Route::get('/reparations', FormReparation::class)
    ->name('reparations.form')
    ->middleware('auth', 'role:user');

Route::get('/profile', Profile::class)
    ->name('profile')
    ->middleware('auth');

Route::get('/address', IndexAddress::class)
    ->name('address.index')
    ->middleware('auth', 'role:user');

Route::get('/address/create', CreateAddress::class)
    ->name('address.create')
    ->middleware('auth', 'role:user');

Route::get('/address/{address}/edit', EditAddress::class)
    ->name('address.edit')
    ->middleware('auth', 'role:user');
    
// Route::get('/orders/{order}/payment', [PaymentController::class, 'payment'])
//     ->name('orders.payment')
//     ->middleware('auth');
