<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class IndexProduct extends Component
{
    use WithPagination;
    #[Url]
    public string $search = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public int $perPage = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleStatus($productId)
    {
        $product = Product::find($productId);

        if ($product) {
            $product->update(['show' => !$product->show]);

            $this->dispatch('show-toast', [
                'message' => $product->show ? 'Produk ditampilkan!' : 'Produk disembunyikan!',
                'type' => 'success',
            ]);
        } else {
            $this->dispatch('show-toast', [
                'message' => 'Produk tidak ditemukan!',
                'type' => 'error',
            ]);
        }
    }

    public function deleteProduct($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $product->delete();

            $this->dispatch('show-toast', [
                'message' => 'Produk berhasil dihapus!',
                'type' => 'success',
            ]);
        } else {
            $this->dispatch('show-toast', [
                'message' => 'Produk tidak ditemukan!',
                'type' => 'error',
            ]);
        }
    }

    public function render()
    {
        $products = Product::query()
            ->with('category')
            ->when(
                $this->search,
                fn($query) =>
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhere('sku', 'like', '%' . $this->search . '%')
                    ->orWhereHas('category', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    })
            )
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.products.index-product', [
            'products' => $products,
        ]);
    }
}
