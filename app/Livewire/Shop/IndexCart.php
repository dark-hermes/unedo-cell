<?php

namespace App\Livewire\Shop;

use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use App\Services\TelegramNotificationService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
class IndexCart extends Component
{
    public $quantities = [];
    public $cartItems = [];
    public $totalPrice = 0;
    public $shippingMethod = 'self_pickup';
    public $selectedAddress = null;

    protected $listeners = ['refreshCart' => 'refresh'];

    public function mount()
    {
        $this->selectedAddress = User::find(Auth::id())->addresses()
            ->where('is_default', true)
            ->value('id');
        $this->refresh();
    }

    public function updatedShippingMethod($value)
    {
        // Reset selected address jika kembali ke self_pickup
        if ($value === 'self_pickup') {
            $this->selectedAddress = null;
        }
    }


    public function refresh()
    {
        $this->cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        $this->quantities = $this->cartItems->pluck('quantity', 'id')->toArray();
        $this->calculateTotalPrice();
    }

    public function calculateTotalPrice()
    {
        $this->totalPrice = $this->cartItems->sum(function ($item) {
            return $item->product->price_after_discount * $this->quantities[$item->id];
        });
    }

    public function updateQuantity($cartId, $quantity)
    {
        $quantity = max(1, min($quantity, Cart::find($cartId)->product->stock));

        Cart::find($cartId)->update(['quantity' => $quantity]);
        $this->quantities[$cartId] = $quantity;
        $this->calculateTotalPrice();
        $this->dispatch('initSelect2'); // Reinitialize Select2
    }

    public function increment($cartId)
    {
        $currentQty = $this->quantities[$cartId];
        $maxStock = Cart::find($cartId)->product->stock;
        $newQty = min($currentQty + 1, $maxStock);

        $this->updateQuantity($cartId, $newQty);
    }

    public function decrement($cartId)
    {
        $currentQty = $this->quantities[$cartId];
        $newQty = max(1, $currentQty - 1);

        $this->updateQuantity($cartId, $newQty);
    }

    public function removeFromCart($cartId)
    {
        Cart::find($cartId)->delete();
        $this->dispatch('swal', [
            'title' => 'Keranjang Dihapus',
            'text' => 'Produk telah dihapus dari keranjang!',
            'icon' => 'success',
        ]);
        $this->refresh();
        $this->dispatch('refreshCartCount');
    }

    public function updateShippingMethod($value)
    {
        $this->shippingMethod = $value;
        $this->dispatch('shippingMethodChanged');
        $this->dispatch('refresh');
    }

    public function checkout()
    {
        // Validasi
        if ($this->shippingMethod !== 'self_pickup' && empty($this->selectedAddress)) {
            $this->dispatch('swal', [
                'title' => 'Alamat Diperlukan',
                'text' => 'Silakan pilih alamat pengiriman untuk melanjutkan.',
                'icon' => 'error'
            ]);
            return;
        }

        // Proses checkout
        try {
            // Logika checkout disini
            logger()->info('Checkout processed', [
                'shipping' => $this->shippingMethod,
                'address' => $this->selectedAddress,
                'user' => Auth::id()
            ]);

            $address = Address::find($this->selectedAddress);

            $order = Order::create([
                'user_id' => Auth::id(),
                'address' => $address->address,
                'latitude' => $address->latitude,
                'longitude' => $address->longitude,
                'recipient_name' => $address->recipient_name,
                'recipient_phone' => $address->phone,
                'order_status' => 'pending',
                'shipping_method' => $this->shippingMethod,
                'shipping_cost' => 0,
            ]);

            foreach ($this->cartItems as $item) {
                $order->orderItems()->create([
                    'product_id' => $item->product_id,
                    'sku' => $item->product->sku,
                    'name' => $item->product->name,
                    'quantity' => $this->quantities[$item->id],
                    'unit_price' => $item->product->price_after_discount,
                ]);

                // Delete item from cart
                $item->delete();
            }

            // Send notification to admin
            $telegramService = new TelegramNotificationService();
            $telegramService->sendOrderNotification($order);
            
            $admins = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->get();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\Shop\OrderCreated($order));
            }

            $this->dispatch('swal', [
                'title' => 'Checkout Berhasil',
                'text' => 'Pesanan Anda telah berhasil diproses!',
                'icon' => 'success'
            ]);

            // wait 3 seconds and redirect to order history
            $this->redirect(route('orders.history'));
        } catch (\Exception $e) {
            logger()->error('Checkout error: ' . $e->getMessage());
            $this->dispatch('swal', [
                'title' => 'Error',
                'text' => 'Terjadi kesalahan saat memproses checkout.',
                'icon' => 'error'
            ]);
        }
    }

    public function render()
    {
        $addresses = User::find(Auth::id())->addresses()->get();
        return view('livewire.shop.index-cart', [
            'addresses' => $addresses,
        ]);
    }
}
