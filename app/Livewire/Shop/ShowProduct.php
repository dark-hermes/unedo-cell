<?php

namespace App\Livewire\Shop;

use App\Models\Cart;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
class ShowProduct extends Component
{
    public Product $product;
    public $quantity = 1;

    public function mount($slug)
    {
        $this->product = Product::where('slug', $slug)->firstOrFail();
    }

    public function increment()
    {
        if ($this->quantity < $this->product->stock) {
            $this->quantity++;
        } else {
            $this->quantity = $this->product->stock;
            $this->dispatch('show-toast', [
                'message' => 'Stok produk tidak mencukupi!',
                'type' => 'error',
            ]);
        }
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        } else {
            $this->quantity = 1;
            $this->dispatch('show-toast', [
                'message' => 'Jumlah produk tidak bisa kurang dari 1!',
                'type' => 'error',
            ]);
        }
    }

    public function addToCart()
    {
        if ($this->product->stock < $this->quantity) {
            $this->dispatch('swal', [
                'title' => 'Stok tidak mencukupi',
                'text' => 'Stok produk tidak mencukupi untuk jumlah yang diminta.',
                'icon' => 'error',
            ]);
            return;
        }

        $productInCart = Cart::where('user_id', Auth::id())
            ->where('product_id', $this->product->id)
            ->first();

        if ($productInCart) {
            $productInCart->update(['quantity' => $this->quantity + $productInCart->quantity]);
            $this->dispatch('swal', [
                'title' => 'Produk ditambahkan ke keranjang',
                'text' => 'Produk berhasil ditambahkan ke keranjang belanja.',
                'icon' => 'success',
            ]);
            return;
        }

        Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $this->product->id,
            'quantity' => $this->quantity,
        ]);

        $this->dispatch('swal', [
            'title' => 'Produk ditambahkan ke keranjang',
            'text' => 'Produk berhasil ditambahkan ke keranjang belanja.',
            'icon' => 'success',
        ]);
    }

    public function toggleWishlist()
    {
        $wishlist = $this->product->wishlists()->where('user_id', Auth::id())->first();
        if ($wishlist) {
            $wishlist->delete();
            $this->dispatch('show-toast', [
                'message' => 'Produk berhasil dihapus dari wishlist!',
                'type' => 'success',
            ]);
        } else {
            $this->product->wishlists()->create(['user_id' => Auth::id()]);
            $this->dispatch('show-toast', [
                'message' => 'Produk berhasil ditambahkan ke wishlist!',
                'type' => 'success',
            ]);
        }
    }
    
    public function render()
    {
        return view('livewire.shop.show-product');
    }
}
