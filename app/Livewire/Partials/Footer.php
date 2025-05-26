<?php

namespace App\Livewire\Partials;

use App\Models\Option;
use Livewire\Component;

class Footer extends Component
{
    public function render()
    {
        $store_address = Option::where('key', 'STORE_ADDRESS')->first();
        $store_phone = Option::where('key', 'STORE_PHONE')->first();
        $store_email = Option::where('key', 'STORE_EMAIL')->first();
        $store_facebook = Option::where('key', 'FACEBOOK_LINK')->first();
        $store_instagram = Option::where('key', 'INSTAGRAM_LINK')->first();
        return view('livewire.partials.footer', [
            'store_address' => $store_address,
            'store_phone' => $store_phone,
            'store_email' => $store_email,
            'store_facebook' => $store_facebook,
            'store_instagram' => $store_instagram,
        ]);
    }
}
