<?php

namespace App\Livewire\User;

use App\Models\Address;
use App\Models\Option;
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
    public $storeCoordinate = null;
    public $distance = null;
    public $duration = null;

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

        // Get store coordinate
        $this->storeCoordinate = Option::where('key', 'STORE_COORDINATE')->first();

        // Calculate initial route
        $this->calculateRoute();
    }

    public function setCurrentLocation()
    {
        $this->dispatch('request-browser-location');
    }

    public function updateLocation($lat, $lng)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
        $this->calculateRoute();
    }

    public function calculateRoute()
    {
        if (!$this->storeCoordinate || !$this->latitude || !$this->longitude) {
            return;
        }

        $storeLat = explode(',', $this->storeCoordinate->value)[0];
        $storeLng = explode(',', $this->storeCoordinate->value)[1];

        // You can use either OSRM (free) or Google Maps Directions API
        $this->getRouteFromOSRM($storeLat, $storeLng, $this->latitude, $this->longitude);

        // Or if you prefer Google Maps:
        // $this->getRouteFromGoogleMaps($storeLat, $storeLng, $this->latitude, $this->longitude);
    }

    protected function getRouteFromOSRM($startLat, $startLng, $endLat, $endLng)
    {
        $url = "http://router.project-osrm.org/route/v1/driving/$startLng,$startLat;$endLng,$endLat?overview=full";

        try {
            $response = file_get_contents($url);
            $data = json_decode($response, true);

            if ($data && $data['code'] === 'Ok') {
                $route = $data['routes'][0];
                $this->distance = round($route['distance'] / 1000, 1); // Convert to km
                $this->duration = round($route['duration'] / 60); // Convert to minutes

                // Dispatch event to update map with route
                $this->dispatch('update-route', [
                    'coordinates' => $route['geometry'],
                    'distance' => $this->distance,
                    'duration' => $this->duration
                ]);
            }
        } catch (\Exception $e) {
            // Handle error
        }
    }

    protected function decodePolyline($steps)
    {
        $points = [];
        foreach ($steps as $step) {
            $points = array_merge($points, $this->decodePolylineToPoints($step['polyline']['points']));
        }
        return $points;
    }

    protected function decodePolylineToPoints($polyline)
    {
        // Implement polyline decoding algorithm
        // (You can find implementations online)
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
