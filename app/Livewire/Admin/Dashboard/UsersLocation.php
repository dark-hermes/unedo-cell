<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Order;
use App\Models\Option;
use Livewire\Component;

class UsersLocation extends Component
{
    public $searchQuery = '';
    public $locations = [];
    public $storeLocations = [];
    public $selectedLocation = null;


    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Load store locations
        $this->storeLocations = Option::where('key', 'STORE_COORDINATE')->get();

        // Load customer locations
        $this->search();
    }

    public function selectLocation($index)
    {
        if (isset($this->locations[$index])) {
            $location = $this->locations[$index];

            // Dispatch browser event dengan data lengkap
            $this->dispatch(
                'zoomToLocation',
                latitude: $location['latitude'],
                longitude: $location['longitude'],
                recipient_name: $location['recipient_name'] ?? 'Pelanggan',
                address: $location['address'] ?? ''
            );

            // Clear search query setelah memilih
            $this->searchQuery = '';
        }
    }



    public function search()
    {
        $query = Order::where('order_status', '!=', 'pending')
            ->where(function ($q) {
                $q->where('address', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('recipient_name', 'like', '%' . $this->searchQuery . '%');
            })
            ->get();


        $this->locations = $query->map(function ($order) {
            return [
                'latitude' => $order->latitude,
                'longitude' => $order->longitude,
                'address' => $order->address,
                'recipient_name' => $order->recipient_name,
                'recipient_phone' => $order->recipient_phone,
                'receipt_number' => $order->receipt_number,
            ];
        });

        $this->dispatch('updateMapData');
    }

    public function resetSearch()
    {
        $this->searchQuery = '';
        $this->search();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.users-location');
    }
}
