<?php

namespace App\Livewire\Admin\Products\Categories;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\ProductCategory;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class IndexProductCategory extends Component
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

    public function deleteCategory($categoryId)
    {
        $category = ProductCategory::find($categoryId);

        if ($category) {

            if ($category->products()->count() > 0) {
                $this->dispatch('show-toast', [
                    'message' => 'Kategori tidak dapat dihapus karena memiliki produk terkait!',
                    'type' => 'error',
                ]);
            } else {
                $category->delete();

                $this->dispatch('show-toast', [
                    'message' => 'Kategori berhasil dihapus!',
                    'type' => 'success',
                ]);
            }
        } else {
            $this->dispatch('show-toast', [
                'message' => 'Kategori tidak ditemukan!',
                'type' => 'error',
            ]);
        }
    }

    public function render()
    {
        $categories = ProductCategory::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('code', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view(
            'livewire.admin.products.categories.index-product-category',
            [
                'categories' => $categories,
                'search' => $this->search,
                'sortField' => $this->sortField,
                'sortDirection' => $this->sortDirection,
                'perPage' => $this->perPage,
            ]
        );
    }
}
