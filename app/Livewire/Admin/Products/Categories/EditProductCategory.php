<?php

namespace App\Livewire\Admin\Products\Categories;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\ProductCategory;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.admin')]
class EditProductCategory extends Component
{
    use WithFileUploads;

    public ProductCategory $category;

    public $name, $description, $code;
    public $newImage;
    public $showPhotoInput = false;

    public function mount(ProductCategory $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->code = $category->code;
        $this->description = $category->description;
    }

    public function updatedNewImage()
    {
        $this->validate([
            'newImage' => 'image|max:1024',
        ]);
    }

    public function removePhoto()
    {
        if ($this->category->image) {
            Storage::disk('public')->delete($this->category->image);
            $this->category->update(['image' => null]);
        }
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|min:2|max:4|unique:product_categories,code,' . $this->category->id,
            'description' => 'nullable|string',
            'newImage' => 'nullable|image|max:1024',
        ];
    }

    public function update()
    {
        $this->validate();

        if ($this->newImage) {
            if ($this->category->image) {
                Storage::disk('public')->delete($this->category->image);
            }

            $path = $this->newImage->store('product_categories', 'public');
            $this->category->image = $path;
        }

        $this->category->update([
            'name' => $this->name,
            'code' => $this->category->code,
            'image' => $this->category->image,
            'description' => $this->description,
        ]);

        $this->dispatch('show-toast', [
            'message' => 'Kategori berhasil diperbarui!',
            'type' => 'success',
        ]);

        $this->showPhotoInput = false;
        $this->newImage = null;
    }
    public function render()
    {
        return view('livewire.admin.products.categories.edit-product-category');
    }
}
