<?php

namespace App\Livewire\User;

use App\Models\User;
use App\Models\Address;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
class IndexAddress extends Component
{
    #[Url]
    public $search = '';

    public function pickDefaultAddress($addressId)
    {
        $userId = Auth::id();
        
        $addresses = Address::where('user_id', $userId)->get();
        foreach ($addresses as $address) {
            if ($address->id == $addressId) {
                $address->is_default = true;
            } else {
                $address->is_default = false;
            }
            $address->save();
        }

        $this->dispatch('show-toast', [
            'message' => 'Alamat berhasil dipilih sebagai alamat utama!',
            'type' => 'success',
        ]);
    }

    public function deleteAddress($addressId)
    {
        $address = Address::find($addressId);
        
        // Prevent deleting default address
        if ($address && $address->is_default) {
            $this->dispatch('swal', [
                'title' => 'Tidak dapat menghapus alamat utama',
                'text' => 'Anda tidak dapat menghapus alamat utama. Silakan pilih alamat lain sebagai alamat utama terlebih dahulu.',
                'icon' => 'error',
            ]);
            return;
        }

        if ($address) {
            $address->delete();
            $this->dispatch('swal', [
                'title' => 'Alamat berhasil dihapus!',
                'icon' => 'success',
            ]);
        } else {
            $this->dispatch('swal', [
                'title' => 'Alamat tidak ditemukan!',
                'icon' => 'error',
            ]);
        }
    }

    public function rules()
    {
        return [
            'search' => 'nullable|string|max:255',
        ];
    }

    public function render()
    {
        $this->validate();
        $addresses = Address::where('user_id', Auth::id())
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('address', 'like', '%' . $this->search . '%');
            })
            ->get();

        return view(
            'livewire.user.index-address',
            [
                'addresses' => $addresses,
            ]
        );
    }
}