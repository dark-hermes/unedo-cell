<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\ProductCategory;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.admin')]
class EditProduct extends Component
{
    use WithFileUploads;

    public Product $product;
    public $name, $description, $sale_price, $buy_price, $discount, $min_stock, $category_id;
    public $newImage;
    public $showPhotoInput = false;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->name = $product->name;
        $this->sale_price = $product->sale_price;
        $this->buy_price = $product->buy_price;
        $this->discount = $product->discount;
        $this->min_stock = $product->min_stock;
        $this->category_id = $product->category_id;
        $this->description = $product->description;
    }

    public function updatedNewImage()
    {
        $this->validate([
            'newImage' => 'image|max:2048',
        ]);
    }

    public function removePhoto()
    {
        if ($this->product->image) {
            Storage::disk('public')->delete($this->product->image);
            $this->product->update(['image' => null]);
        }
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'sale_price' => 'required|numeric|min:0',
            'buy_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'min_stock' => 'required|numeric|min:0',
            'category_id' => 'required|exists:product_categories,id',
            'description' => 'nullable|string',
            'newImage' => 'nullable|image|max:2048',
        ];
    }

    public function update()
    {
        $this->validate();

        if ($this->newImage) {
            if ($this->product->image) {
                Storage::disk('public')->delete($this->product->image);
            }

            $path = $this->newImage->store('products', 'public');
            $this->product->image = $path;
        }

        $this->product->update([
            'name' => $this->name,
            'sale_price' => $this->sale_price,
            'buy_price' => $this->buy_price,
            'discount' => $this->discount,
            'min_stock' => $this->min_stock,
            'category_id' => $this->category_id,
            'description' => $this->description,
            'image' => $this->product->image,
        ]);

        $this->dispatch('show-toast', [
            'message' => 'Produk berhasil diperbarui!',
            'type' => 'success',
        ]);

        $this->showPhotoInput = false;
        $this->newImage = null;
    }
    public function render()
    {
        $categories = ProductCategory::orderBy('name')->get();
        return view('livewire.admin.products.edit-product', [
            'categories' => $categories,
        ]);
    }
}
