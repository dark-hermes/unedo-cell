<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use App\Models\Reparation;

class NewReparations extends Component
{
    public function render()
    {
        $reparations = Reparation::with('user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();
            
        return view('livewire.admin.dashboard.new-reparations', [
            'reparations' => $reparations
        ]);
    }
}
