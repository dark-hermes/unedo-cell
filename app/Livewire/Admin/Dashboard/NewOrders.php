<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use App\Models\Order;

class NewOrders extends Component
{
    public function render()
    {
        $orders = Order::latest()
            ->where('order_status', 'pending')
            ->take(5)
            ->get();
        return view('livewire.admin.dashboard.new-orders', [
            'orders' => $orders
        ]);
    }
}
