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

    public function render()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['orderItems', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.shop.order-history', [
            'orders' => $orders,
        ]);
    }
}