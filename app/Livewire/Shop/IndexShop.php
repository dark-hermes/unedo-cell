<?php

namespace App\Livewire\Shop;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\ProductCategory;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
class IndexShop extends Component
{
    #[Url] // Menyimpan filter di URL
    public $categoryFilter = '*'; // Nilai default untuk semua produk

    #[Url]
    public $search = '';

    public function updatedCategoryFilter($value)
    {
        $this->categoryFilter = $value;
    }


    public function showQuickView($productId)
    {
        $this->dispatch('load-product', productId: $productId)
            ->to(ProductQuickView::class);
    }

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
        $query = Product::where('show', true)
            ->withCurrentStock()
            ->having('current_stock', '>=', 0);

        // Filter berdasarkan kategori
        if ($this->categoryFilter && $this->categoryFilter != '*') {
            $query->where('product_category_id', $this->categoryFilter);
        }

        // Filter pencarian
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $products = $query->orderBy('created_at', 'desc')
            ->paginate(10);

        $productCategories = ProductCategory::where('name', 'NOT LIKE', '%kartu%')
            ->where('name', 'NOT LIKE', '%voucher%')
            ->where('name', 'NOT LIKE', '%pulsa%')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.shop.index-shop', [
            'products' => $products,
            'productCategories' => $productCategories,
        ]);
    }
}
