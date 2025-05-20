<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use App\Models\StockOutput;
use Carbon\Carbon;

class SalesThisWeek extends Component
{
    public function render()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $salesThisWeek = StockOutput::where('reason', 'sale')
            ->whereBetween('output_date', [$startOfWeek, $endOfWeek])
            ->sum('quantity');
            
        return view('livewire.admin.dashboard.sales-this-week', [
            'salesThisWeek' => $salesThisWeek
        ]);
    }
}