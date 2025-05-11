<?php

namespace App\Livewire\Shop;

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
class Payment extends Component
{
    public Order $order;
    public $snapToken;
    public $paymentUrl;
    public $paymentStatus = 'initial';

    public function mount(Order $order)
    {
        $this->order = $order;
        // Jika sudah dibayar, redirect ke success
        if ($this->order->transaction && $this->order->transaction->transaction_status === 'settlement') {
            return redirect()->route('payment.success', ['order' => $this->order->id]);
        }
    }

    public function initializePayment()
    {
        $this->validate([
            'order.id' => 'required|exists:orders,id',
        ]);

        $midtransService = new MidtransService();

        $params = [
            'transaction_details' => [
                'order_id' => $this->order->transaction->transaction_code,
                'gross_amount' => $this->order->total_price,
            ],
            'customer_details' => [
                'first_name' => $this->order->user->name,
                'email' => $this->order->user->email,
                'phone' => $this->order->user->phone,
            ],
            'item_details' => $this->buildItemDetails(),
            'callbacks' => [
                'finish' => route('orders.payment.success', ['orderId' => $this->order->id]),
            ]
        ];

        try {
            $this->paymentUrl = $midtransService->createTransaction($params)->redirect_url;
            $this->paymentStatus = 'ready';
        } catch (\Exception $e) {
            $this->addError('payment', 'Error processing payment: ' . $e->getMessage());
            $this->paymentStatus = 'failed';
        }
    }

    public function redirectToPayment()
    {
        return redirect()->away($this->paymentUrl);
    }

    protected function buildItemDetails()
    {
        $items = [];

        foreach ($this->order->orderItems as $item) {
            $items[] = [
                'id' => $item->product_id,
                'price' => $item->unit_price,
                'quantity' => $item->quantity,
                'name' => $item->name,
            ];
        }

        if ($this->order->shipping_cost > 0) {
            $items[] = [
                'id' => 'SHIPPING',
                'price' => $this->order->shipping_cost,
                'quantity' => 1,
                'name' => 'Biaya Pengiriman',
            ];
        }

        return $items;
    }

    public function render()
    {
        return view('livewire.shop.payment');
    }
}
