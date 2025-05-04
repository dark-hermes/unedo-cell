<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class HistoryStock extends Component
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
            ->get();

        $stockOutputs = $this->product->stockOutputs()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        $histories = $stockEntries->concat($stockOutputs);

        $histories = $histories->sortByDesc(function ($item) {
            return $item->received_at ?? $item->output_date;
        });

        $histories = $histories->values();

        return view('livewire.admin.products.history-stock', [
            'histories' => $histories,
        ]);
    }
}
