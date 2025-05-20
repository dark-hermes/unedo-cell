<?php

use App\Livewire\Admin\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Users\EditUser;
use App\Livewire\Admin\Order\ShowOrder;
use App\Livewire\Admin\Users\IndexUser;
use App\Livewire\Admin\Order\IndexOrder;
use App\Livewire\Admin\Users\CreateUser;
use App\Livewire\Admin\Option\EditOption;
use App\Livewire\Admin\Option\IndexOption;
use App\Livewire\Admin\Products\EditProduct;
use App\Livewire\Admin\Users\IndexUserAdmin;
use App\Livewire\Admin\Products\HistoryStock;
use App\Livewire\Admin\Products\IndexProduct;
use App\Livewire\Admin\Users\CreateUserAdmin;
use App\Livewire\Admin\Products\CreateProduct;
use App\Livewire\Admin\Products\IndexStockEntry;
use App\Livewire\Admin\Products\IndexStockOutput;
use App\Livewire\Admin\Products\ManageStockEntry;
use App\Livewire\Admin\Reparation\ShowReparation;
use App\Livewire\Admin\Products\ManageStockOutput;
use App\Livewire\Admin\Reparation\IndexReparation;
use App\Http\Controllers\Admin\DashboardController;
use App\Livewire\Admin\HomeContents\EditHomeContent;
use App\Livewire\Admin\HomeContents\IndexHomeContent;
use App\Livewire\Admin\HomeContents\CreateHomeContent;
use App\Livewire\Admin\Products\Categories\EditProductCategory;
use App\Livewire\Admin\Products\Categories\IndexProductCategory;
use App\Livewire\Admin\Products\Categories\CreateProductCategory;

Route::middleware(['web', 'auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', Dashboard::class)
            ->name('dashboard');

        Route::get('/home-contents', IndexHomeContent::class)
            ->name('home-contents.index');
        Route::get('/home-contents/create', CreateHomeContent::class)
            ->name('home-contents.create');
        Route::get('/home-contents/{homeContent}/edit', EditHomeContent::class)
            ->name('home-contents.edit');

        Route::get('/users', IndexUser::class)
            ->name('users.index');
        Route::get('/users/create', CreateUser::class)
            ->name('users.create');
        Route::get('/users/{user}/edit', EditUser::class)
            ->name('users.edit');

        Route::get('/admins', IndexUserAdmin::class)
            ->name('admins.index');
        Route::get('/admins/create', CreateUserAdmin::class)
            ->name('admins.create');

        Route::get('/products', IndexProduct::class)
            ->name('products.index');
        Route::get('/products/create', CreateProduct::class)
            ->name('products.create');
        Route::get('/products/{product}/edit', EditProduct::class)
            ->name('products.edit');
        Route::get('/products/{product}/edit', EditProduct::class)
            ->name('products.edit');
        Route::get('/products/{product}/stock-entries', IndexStockEntry::class)
            ->name('products.stock-entries.index');
        Route::get('/products/{product}/stock-entries/create', ManageStockEntry::class)
            ->name('products.stock-entries.create');
        Route::get('/producsts/{product}/stock-outputs', IndexStockOutput::class)
            ->name('products.stock-outputs.index');
        Route::get('/products/{product}/stock-outputs/create', ManageStockOutput::class)
            ->name('products.stock-outputs.create');
        Route::get('/products/{product}/history-stock', HistoryStock::class)
            ->name('products.history-stock.index');

        Route::get('/products/categories', IndexProductCategory::class)
            ->name('products.categories.index');
        Route::get('/products/categories/create', CreateProductCategory::class)
            ->name('products.categories.create');
        Route::get('/products/categories/{category}/edit', EditProductCategory::class)
            ->name('products.categories.edit');

        Route::get('/orders', IndexOrder::class)
            ->name('orders.index');
        Route::get('/orders/{order}', ShowOrder::class)
            ->name('orders.show');

        Route::get('/reparations', IndexReparation::class)
            ->name('reparations.index');
        Route::get('/reparations/{reparation}', ShowReparation::class)
            ->name('reparations.show');

        Route::get('/options', IndexOption::class)
            ->name('options.index');
        Route::get('/options/{option}/edit', EditOption::class)
            ->name('options.edit');
    });
