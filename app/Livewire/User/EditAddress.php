<?php

namespace App\Livewire\User;

use App\Models\Address;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
class EditAddress extends Component
{
    public Address $address;

    #[Rule('required|min:3|max:100')]
    public $name;

    #[Rule('required|min:3|max:100')]
    public $recipient_name;

    #[Rule('required|min:10|max:15')]
    public $phone;

    #[Rule('required|min:10')]
    public $address_text;

    #[Rule('required|numeric|between:-90,90')]
    public $latitude;

    #[Rule('required|numeric|between:-180,180')]
    public $longitude;

    public $note = '';
    public $searchLocation = '';

    public function mount(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $this->address = $address;
        $this->name = $address->name;
        $this->recipient_name = $address->recipient_name;
        $this->phone = $address->phone;
        $this->address_text = $address->address;
        $this->latitude = $address->latitude;
        $this->longitude = $address->longitude;
        $this->note = $address->note;
    }

    public function setCurrentLocation()
    {
        $this->dispatch('request-browser-location');
    }

    public function updateLocation($lat, $lng)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
    }

    public function save()
    {
        $this->validate();

        $this->address->update([
            'name' => $this->name,
            'recipient_name' => $this->recipient_name,
            'phone' => $this->phone,
            'address' => $this->address_text,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'note' => $this->note,
        ]);

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Alamat berhasil diperbarui'
        ]);
    }

    public function render()
    {
        return view('livewire.user.edit-address');
    }
}