<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use App\Models\ProductCategory;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class CreateProduct extends Component
{
    use WithFileUploads;

    public $category_id;
    public $name;
    public $image;
    public $description;
    public $sale_price;
    public $buy_price;
    public $discount = 0;
    public $min_stock = 0;
    public $sku;

    protected $rules = [
        'category_id' => 'required|exists:product_categories,id',
        'name' => 'required|string|max:255|unique:products,name',
        'image' => 'nullable|image|max:2048',
        'description' => 'nullable|string',
        'sale_price' => 'required|numeric|min:0',
        'buy_price' => 'required|numeric|min:0',
        'discount' => 'nullable|integer|min:0|max:100',
        'min_stock' => 'nullable|integer|min:0',
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
                $imagePath = $this->image->store('products', 'public');
            }

            $code = ProductCategory::find($this->category_id)->code;
            $sku = $code . '-' . strtoupper(substr(str_shuffle('0123456789'), 0, 5));

            Product::create([
                'category_id' => $this->category_id,
                'name' => $this->name,
                'image' => $imagePath,
                'description' => $this->description,
                'sale_price' => $this->sale_price,
                'buy_price' => $this->buy_price,
                'sku' => $sku,
                'discount' => $this->discount,
                'min_stock' => $this->min_stock,
            ]);

            $this->dispatch('show-toast', [
                'message' => 'Produk berhasil dibuat!',
                'type' => 'success',
            ]);

            $this->reset();
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('show-toast', [
                'message' => 'Gagal membuat produk: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function render()
    {
        $categories = ProductCategory::orderBy('name')->get();
        return view('livewire.admin.products.create-product', [
            'categories' => $categories,
        ]);
    }
}
