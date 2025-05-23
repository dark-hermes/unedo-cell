<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use App\Models\StockOutput;
use Carbon\Carbon;

class MonthlySalesChart extends Component
{
    public $months = [];
    public $salesData = [];

    public function mount()
    {
        $this->prepareChartData();
    }

    protected function prepareChartData()
    {
        $this->months = [];
        $this->salesData = [];
        
        for ($i = 0; $i < 12; $i++) {
            $month = Carbon::now()->subMonths(11)->addMonths($i);
            $this->months[] = $month->format('M Y');
            
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();
            
            $this->salesData[] = StockOutput::where('reason', 'sale')
                ->whereBetween('output_date', [$start, $end])
                ->sum('quantity');
        }
    }

    public function render()
    {
        return view('livewire.admin.dashboard.monthly-sales-chart');
    }
}