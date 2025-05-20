<?php

namespace App\Livewire\Reparations;

use Livewire\Component;
use App\Models\Reparation;
use Livewire\Attributes\Layout;
use App\Services\MidtransService;

#[Layout('components.layouts.app')]
class ReparationPayment extends Component
{
    public Reparation $reparation;
    public $snapToken;
    public $paymentUrl;
    public $paymentStatus = 'initial';

    public function mount(Reparation $reparation)
    {
        $this->reparation = $reparation;
        // Jika sudah dibayar, redirect ke success
        if ($this->reparation->reparationTransaction && $this->reparation->reparationTransaction->transaction_status === 'settlement') {
            return redirect()->route('reparations.payment.success', ['reparation' => $this->reparation->id]);
        }
    }

    public function initializePayment()
    {
        $this->validate([
            'reparation.id' => 'required|exists:reparations,id',
        ]);

        $midtransService = new MidtransService();

        $params = [
            'transaction_details' => [
                'order_id' => $this->reparation->reparationTransaction->transaction_code,
                'gross_amount' => $this->reparation->price,
            ],
            'customer_details' => [
                'first_name' => $this->reparation->user->name,
                'email' => $this->reparation->user->email,
                'phone' => $this->reparation->user->phone,
            ],
            'item_details' => $this->buildItemDetails(),
            'callbacks' => [
                'finish' => route('reparations.payment.success', ['reparationId' => $this->reparation->id]),
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
        return [
            [
                'id' => $this->reparation->id,
                'price' => $this->reparation->price,
                'quantity' => 1,
                'name' => 'Reparasi: ' . $this->reparation->description,
            ],
        ];
    }

    public function render()
    {
        return view('livewire.reparations.reparation-payment');
    }
}
