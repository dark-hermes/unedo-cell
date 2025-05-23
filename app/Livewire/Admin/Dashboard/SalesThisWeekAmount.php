<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Transaction;
use Livewire\Component;

class SalesThisWeekAmount extends Component
{
    public function render()
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        
        $salesThisWeek = Transaction::where('transaction_status', 'settlement')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->sum('amount');
        $salesThisWeek = number_format($salesThisWeek, 0, ',', '.');
        $salesThisWeek = 'Rp ' . $salesThisWeek;

        return view('livewire.admin.dashboard.sales-this-week-amount', [
            'salesThisWeek' => $salesThisWeek
        ]);
    }
}
