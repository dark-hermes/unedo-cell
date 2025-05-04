<?php

namespace App\Livewire\Shop;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\On;

class ProductQuickView extends Component
{
    public $productId;
    public Product $product;
    public $quantity = 1;
    public $showModal = false;

    protected $listeners = ['showProductModal'];

    public function showProductModal($productId)
    {
        $this->productId = $productId;
        $this->product = Product::find($this->productId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function increment()
    {
        $this->quantity++;
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        $this->emit('addToCart', $this->productId, $this->quantity);
        $this->dispatch('show-toast', [
            'message' => 'Produk berhasil ditambahkan ke keranjang!',
            'type' => 'success',
        ]);
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.shop.product-quick-view');
    }
}
