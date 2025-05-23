<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;

class ReparationsThisWeek extends Component
{
    public function render()
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        $reparationsThisWeek = \App\Models\Reparation::where('status', 'pending')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->count();
        $reparationsThisWeek = number_format($reparationsThisWeek, 0, ',', '.');
        return view('livewire.admin.dashboard.reparations-this-week', [
            'reparationsThisWeek' => $reparationsThisWeek
        ]);
    }
}
