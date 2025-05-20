<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use App\Models\StockOutput;
use Carbon\Carbon;

class MonthlySalesChart extends Component
{
    public function render()
    {
        $months = [];
        $salesData = [];
        
        for ($i = 0; $i < 12; $i++) {
            $month = Carbon::now()->subMonths(11)->addMonths($i);
            $months[] = $month->format('M Y');
            
            $start = $month->startOfMonth();
            $end = $month->endOfMonth();
            
            $salesData[] = StockOutput::where('reason', 'sale')
                ->whereBetween('output_date', [$start, $end])
                ->sum('quantity');
        }
        
        return view('livewire.admin.dashboard.monthly-sales-chart', [
            'months' => $months,
            'salesData' => $salesData
        ]);
    }
}
