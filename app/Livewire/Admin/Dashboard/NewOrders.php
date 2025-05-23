<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use App\Models\Order;

class NewOrders extends Component
{
    public function render()
    {
        $orders = Order::with(['user', 'orderItems.product'])
            ->latest()
            ->take(5)
            ->where('order_status', '==', 'pending')
            ->get();
            
        return view('livewire.admin.dashboard.new-orders', [
            'orders' => $orders
        ]);
    }
}