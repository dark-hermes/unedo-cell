<?php

namespace App\Livewire\Reparation;

use Livewire\Component;
use App\Models\Reparation;
use Illuminate\Support\Facades\Auth;

class HistoryReparation extends Component
{
    public $reparations;

    public function mount()
    {
        $this->reparations = Reparation::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.reparation.history-reparation');
    }
}