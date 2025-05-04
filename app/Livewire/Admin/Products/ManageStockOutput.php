<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use Livewire\Component;
use App\Models\StockOutput;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.admin')]
class ManageStockOutput extends Component
{
    public Product $product;
    public $product_id;
    public $quantity;
    public $reason;
    public $note;
    public $output_date;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->product_id = $product->id;
        $this->quantity = 1;
        $this->reason = '';
        $this->note = '';
        $this->output_date = now()->format('Y-m-d');
    }

    public function hydrate()
    {
        $this->validate();
    }

    protected $rules = [
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
        'reason' => 'required|string|max:255',
        'note' => 'nullable|string|max:255',
        'output_date' => 'required|date',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function store()
    {
        $this->validate();
            
        StockOutput::create([
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'reason' => $this->reason,
            'note' => $this->note,
            'output_date' => $this->output_date,
            'user_id' => Auth::id(),
        ]);
        
        // Show a success message
        $this->dispatch('show-toast', [
            'message' => 'Stok keluar berhasil ditambahkan!',
            'type' => 'success',
        ]);

        // redirect to the product page
        return redirect()->route('admin.products.stock-outputs.index', ['product' => $this->product_id]);
    }

    public function render()
    {
        return view('livewire.admin.products.manage-stock-output');
    }
}
