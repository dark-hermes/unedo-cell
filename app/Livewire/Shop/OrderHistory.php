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
            
            session()->flash('message', 'Pesanan berhasil dibatalkan.');
        } else {
            session()->flash('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
        }
    }

    public function completeOrder($orderId)
    {
        $order = Order::findOrFail($orderId);
        
        // Only allow completion if order is delivered
        if ($order->order_status === 'delivered') {
            $order->update([
                'order_status' => 'completed'
            ]);
            
            session()->flash('message', 'Pesanan berhasil diselesaikan.');
        } else {
            session()->flash('error', 'Pesanan hanya dapat diselesaikan setelah diterima.');
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