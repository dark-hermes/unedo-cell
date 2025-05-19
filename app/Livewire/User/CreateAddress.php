<?php

namespace App\Livewire\User;

use App\Models\Address;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
class CreateAddress extends Component
{
    #[Rule('required|min:3|max:100')]
    public $name = '';

    #[Rule('required|min:3|max:100')]
    public $recipient_name = '';

    #[Rule('required|min:10|max:15')]
    public $phone = '';

    #[Rule('required|min:10')]
    public $address = '';

    #[Rule('required|numeric|between:-90,90')]
    public $latitude = '-6.2088'; // Default Jakarta coordinates

    #[Rule('required|numeric|between:-180,180')]
    public $longitude = '106.8456'; // Default Jakarta coordinates

    public $note = '';
    public $searchLocation = '';

    public function setCurrentLocation()
    {
        $this->dispatch('request-browser-location');
    }

    public function updateLocation($lat, $lng)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
        // Tidak perlu dispatch event locationUpdated di sini karena akan dihandle oleh JS
    }

    public function save()
    {
        $this->validate();

        Auth::user()->addresses()->create([
            'name' => $this->name,
            'recipient_name' => $this->recipient_name,
            'phone' => $this->phone,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'note' => $this->note,
            'is_default' => Auth::user()->addresses()->count() === 0,
        ]);

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Alamat berhasil ditambahkan'
        ]);
    }

    public function render()
    {
        return view('livewire.user.create-address');
    }
}
