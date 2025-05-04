<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use Livewire\Component;
use App\Models\StockEntry;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class ManageStockEntry extends Component
{
    public Product $product;
    public $product_id;
    public $quantity;
    public $source;
    public $note;
    public $received_at;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->product_id = $product->id;
        $this->quantity = 1;
        $this->source = '';
        $this->note = '';
        $this->received_at = now()->format('Y-m-d');
    }

    public function hydrate()
    {
        $this->validate();
    }

    protected $rules = [
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
        'source' => 'required|string|max:255',
        'note' => 'nullable|string|max:255',
        'received_at' => 'required|date',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function store()
    {
        $this->validate();
            
        StockEntry::create([
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'source' => $this->source,
            'note' => $this->note,
            'received_at' => $this->received_at,
            'user_id' => Auth::id(),
        ]);
        
        // Show a success message
        $this->dispatch('show-toast', [
            'message' => 'Stok masuk berhasil ditambahkan!',
            'type' => 'success',
        ]);

        // redirect to the product page
        return redirect()->route('admin.products.stock-entries.index', ['product' => $this->product_id]);
    }

    public function render()
    {
        return view('livewire.admin.products.manage-stock-entry');
    }
}
