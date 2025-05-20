<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use App\Models\Product;

class LowStockProducts extends Component
{
    public function render()
    {
        $lowStockProducts = Product::withCurrentStock()
            ->havingRaw('current_stock <= min_stock')
            ->orderBy('current_stock')
            ->take(5)
            ->get();
            
        return view('livewire.admin.dashboard.low-stock-products', [
            'lowStockProducts' => $lowStockProducts
        ]);
    }
}