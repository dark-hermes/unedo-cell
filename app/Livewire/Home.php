<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\HomeContent;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Home extends Component
{
    public function render()
    {
        $homeContents = HomeContent::all();

        return view('livewire.home', [
            'homeContents' => $homeContents,
        ]);
    }
}
