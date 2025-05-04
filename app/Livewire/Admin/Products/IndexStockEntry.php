<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class IndexStockEntry extends Component
{
    public Product $product;
    
    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function render()
    {
        $stockEntries = $this->product->stockEntries()
            ->with('user')
            ->orderBy('received_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.products.index-stock-entry', [
            'stockEntries' => $stockEntries,
        ]);
    }
}
