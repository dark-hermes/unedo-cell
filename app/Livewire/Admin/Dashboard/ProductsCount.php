<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;

class ProductsCount extends Component
{
    public function render()
    {
        $productsCount = \App\Models\Product::count();
        $productsCount = number_format($productsCount, 0, ',', '.');
        return view('livewire.admin.dashboard.products-count', [
            'productsCount' => $productsCount
        ]);
    }
}
