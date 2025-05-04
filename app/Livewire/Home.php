<?php

namespace App\Livewire;

use App\Models\Option;
use Livewire\Component;
use App\Models\HomeContent;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Home extends Component
{
    public function render()
    {
        $homeContents = HomeContent::all();
        $banner = Option::where('key', 'BANNER_IMAGE')->first();

        return view('livewire.home', [
            'homeContents' => $homeContents,
            'banner' => $banner,
        ]);
    }
}
