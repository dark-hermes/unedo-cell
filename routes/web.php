<?php

use App\Livewire\Home;
use App\Livewire\Shop\IndexCart;
use App\Livewire\Shop\IndexShop;
use App\Livewire\Shop\ShowProduct;
use App\Livewire\Shop\OrderHistory;
use App\Livewire\Shop\IndexWishlist;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleAuthController;

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
    ->name('cart.index');

Route::get('/wishlist', IndexWishlist::class)
    ->name('wishlist.index');

Route::get('/orders/history', OrderHistory::class)
    ->name('orders.history');