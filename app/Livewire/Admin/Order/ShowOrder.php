<?php

namespace App\Livewire\Admin\Order;

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

#[Layout('components.layouts.admin')]
class ShowOrder extends Component
{
    public Order $order;
    public string $receipt_number = '';
    public bool $isProcessing = false;
    public float $shipping_cost = 0;
    public bool $showShippingForm = false;

    protected $rules = [
        'receipt_number' => 'required|string|max:255',
    ];

    public function mount(Order $order)
    {
        Session::reflash();
        $this->order = $order->fresh();
        $this->receipt_number = $order->receipt_number ?? '';
        $this->shipping_cost = $order->shipping_cost;
    }

    public function hydrate()
    {
        // Ensure session is maintained during hydration
        Session::reflash();
    }

    public function confirmOrder()
    {
        $this->isProcessing = true;

        try {
            // Use database transaction for safety
            DB::transaction(function () {
                $freshOrder = Order::with(['user', 'orderItems.product'])->findOrFail($this->order->id);

                $freshOrder->update(['order_status' => 'confirmed']);

                $freshOrder->transaction()->create([
                    'user_id' => $freshOrder->user_id,
                    'transaction_code' => 'TRX-' . strtoupper(uniqid()),
                    'amount' => $freshOrder->total_price,
                ]);

                $freshOrder->user->notify(new \App\Notifications\Shop\OrderConfirmed($freshOrder));

                // Refresh all component data
                $this->order = $freshOrder->fresh();
            });

            $this->dispatch('show-toast', [
                'message' => 'Pesanan telah dikonfirmasi!',
                'type' => 'success',
            ])->self();

            // Reinitialize Livewire after successful update
            $this->dispatch('reinitialize');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Gagal mengkonfirmasi pesanan: ' . $e->getMessage(),
                'type' => 'error',
            ])->self();
        } finally {
            $this->isProcessing = false;
        }
    }

    public function cancelOrder()
    {
        try {
            $this->order->update(['order_status' => 'canceled']);
            $this->dispatch('show-toast', [
                'message' => 'Pesanan telah dibatalkan!',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Gagal membatalkan pesanan: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function shipOrder()
    {
        try {
            $this->order->update(['order_status' => 'shipped']);
            $this->dispatch('show-toast', [
                'message' => 'Pesanan telah dikirim!',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Gagal menandai pesanan sebagai dikirim: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function inputReceiptNumber()
    {
        $this->validate();

        if ($this->order->order_status !== 'shipped') {
            $this->dispatch('show-toast', [
                'message' => 'Pesanan harus dalam status dikirim untuk memasukkan nomor resi!',
                'type' => 'error',
            ]);
            return;
        }

        if ($this->order->receipt_number !== null) {
            $this->dispatch('show-toast', [
                'message' => 'Nomor resi hanya bisa diinput satu kali!',
                'type' => 'error',
            ]);
            return;
        }

        try {
            $this->order->update(['receipt_number' => $this->receipt_number]);
            $this->dispatch('show-toast', [
                'message' => 'Nomor resi telah diperbarui!',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Gagal menyimpan nomor resi: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function updateShippingCost()
    {
        $this->validate([
            'shipping_cost' => 'required|numeric|min:0',
        ]);

        try {
            if ($this->order->order_status !== 'pending') {
                $this->dispatch('show-toast', [
                    'message' => 'Biaya pengiriman hanya dapat diperbarui untuk pesanan yang belum diproses!',
                    'type' => 'error',
                ]);
                return;
            }

            DB::transaction(function () {
                $freshOrder = Order::findOrFail($this->order->id);

                // Hitung ulang total harga
                $subtotal = $freshOrder->orderItems->sum('total_price');
                $total_price = $subtotal + $this->shipping_cost;

                $freshOrder->update([
                    'shipping_cost' => $this->shipping_cost,
                    'total_price' => $total_price
                ]);

                $this->order = $freshOrder->fresh();
                $this->showShippingForm = false;
            });

            $this->dispatch('show-toast', [
                'message' => 'Biaya pengiriman berhasil diperbarui!',
                'type' => 'success',
            ])->self();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Gagal memperbarui biaya pengiriman: ' . $e->getMessage(),
                'type' => 'error',
            ])->self();
        }
    }

    public function editShippingCost()
    {
        $this->shipping_cost = $this->order->shipping_cost;
        $this->showShippingForm = true;
    }

    public function render()
    {
        return view('livewire.admin.order.show-order');
    }
}
