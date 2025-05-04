<?php

namespace App\Livewire\Shop;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
class IndexWishlist extends Component
{
    #[Url]
    public $search = '';

    // public function updatingSearch()
    // {
    //     $this->resetPage();
    // }

    public function toggleWishlist($productId)
    {
        $product = Product::find($productId);

        $wishlist = $product->wishlists()->where('user_id', Auth::id())->first();
        if ($wishlist) {
            $wishlist->delete();
            $this->dispatch('swal', [
                'title' => 'Wishlist Dihapus',
                'text' => 'Produk telah dihapus dari wishlist!',
                'icon' => 'success',
            ]);
        } else {
            $product->wishlists()->create(['user_id' => Auth::id()]);
            $this->dispatch('swal', [
                'title' => 'Wishlist Ditambahkan',
                'text' => 'Produk telah ditambahkan ke wishlist!',
                'icon' => 'success',
            ]);
        }
    }

    public function render()
    {
        $products = Product::query()
            ->whereHas('wishlists', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->when(
                $this->search,
                fn($query) =>
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
            )
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.shop.index-wishlist', [
            'products' => $products,
        ]);
    }
}
