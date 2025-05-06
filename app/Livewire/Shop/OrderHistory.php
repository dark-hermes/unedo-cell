<?php

namespace App\Livewire\Shop;

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
class OrderHistory extends Component
{
    public $showDetails = [];

    public function payOrder($orderId)
    {
        $order = Order::findOrFail($orderId);
        
        // Redirect to payment page or process payment
        return redirect()->route('orders.payment', ['order' => $order->id]);
    }

    public function cancelOrder($orderId)
    {
        $order = Order::findOrFail($orderId);
        
        // Only allow cancellation if order is still pending
        if ($order->order_status === 'pending') {
            $order->update([
                'order_status' => 'cancelled'
            ]);
            
            // You might also want to cancel the transaction if it exists
            if ($order->transaction) {
                $order->transaction->update([
                    'transaction_status' => 'cancel'
                ]);
            }
            
            $this->dispatch('swal', [
                'title' => 'Pesanan Dibatalkan',
                'text' => 'Pesanan Anda telah dibatalkan.',
                'icon' => 'success',
            ]);
        } else {
            $this->dispatch('swal', [
                'title' => 'Pesanan Tidak Dapat Dibatalkan',
                'text' => 'Pesanan sudah dalam proses pengiriman.',
                'icon' => 'error',
            ]);
        }
    }

    public function completeOrder($orderId)
    {
        $order = Order::findOrFail($orderId);
        
        // Only allow completion if order is delivered
        if ($order->order_status === 'shipped') {
            $order->update([
                'order_status' => 'completed'
            ]);

            $products = $order->orderItems->pluck('product_id')->toArray();
            foreach ($products as $productId) {
                $order->orderItems()->where('product_id', $productId)->product()->stockOutputs()->create([
                    'product_id' => $productId,
                    'quantity' => $order->orderItems()->where('product_id', $productId)->quantity,
                    'reason' => 'sale',
                    'note' => $order->user->name . ' : ' . $order->user->phone . ' - ' . $order->recipient_name . ' : ' . $order->recipient_phone,
                ]);
            }
            
            $this->dispatch('swal:confirm', [
                'title' => 'Pastikan Pesanan Anda Sudah Sampai',
                'text' => 'Pesanan akan diselesaikan dan uang akan diteruskan ke penjual.',
                'icon' => 'warning',
                'showCancelButton' => true,
                'confirmButtonText' => 'Ya, Selesaikan Pesanan',
                'cancelButtonText' => 'Tidak, Batalkan',
                'onConfirm' => 'completeOrderConfirmed',
            ]);
        } else {
            $this->dispatch('swal', [
                'title' => 'Pesanan Belum Selesai',
                'text' => 'Pesanan belum sampai di tangan Anda.',
                'icon' => 'error',
            ]);
        }
    }

    public function render()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['orderItems', 'orderItems.product', 'transaction'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.shop.order-history', [
            'orders' => $orders,
        ]);
    }
}