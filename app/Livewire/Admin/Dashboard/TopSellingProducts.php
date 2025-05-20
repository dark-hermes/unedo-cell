<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use App\Models\Product;
use App\Models\StockOutput;

class TopSellingProducts extends Component
{
    public function render()
    {
        $topProducts = StockOutput::selectRaw('product_id, SUM(quantity) as total_sold')
            ->with('product')
            ->where('reason', 'sale')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();
            
        return view('livewire.admin.dashboard.top-selling-products', [
            'topProducts' => $topProducts
        ]);
    }
}