<?php

namespace App\Livewire\Admin\Products\Categories;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Models\ProductCategory;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.admin')]
class CreateProductCategory extends Component
{
    use WithFileUploads;

    public $name;
    public $code;
    public $description;
    public $image;

    protected $rules = [
        'name' => 'required|string|max:255|unique:product_categories,name',
        'code' => 'required|string|min:2|max:4|unique:product_categories,code',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:2048', // maksimal 2MB
    ];

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function store()
    {
        $this->validate();

        try {
            $imagePath = null;
            if ($this->image) {
                $imagePath = $this->image->store('product_categories', 'public');
            }

            ProductCategory::create([
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description,
                'image' => $imagePath,
            ]);

            $this->dispatch('show-toast', [
                'message' => 'Kategori berhasil dibuat!',
                'type' => 'success',
            ]);

            $this->reset();
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('show-toast', [
                'message' => 'Gagal membuat kategori: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.products.categories.create-product-category');
    }
}
