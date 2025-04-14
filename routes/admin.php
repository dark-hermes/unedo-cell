<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Users\IndexUser;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;

Route::middleware(['auth'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard.index');

        Route::get('/users', IndexUser::class)
            ->name('users.index');
    });
