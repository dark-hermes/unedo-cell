<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class IndexStockOutput extends Component
{
    public Product $product;
    
    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function render()
    {
        $stockOutputs = $this->product->stockOutputs()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('livewire.admin.products.index-stock-output', [
            'stockOutputs' => $stockOutputs,
        ]);
    }
}
