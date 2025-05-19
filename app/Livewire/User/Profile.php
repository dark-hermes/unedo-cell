<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.app')]
class Profile extends Component
{
    use WithFileUploads;

    public $user;
    public $name;
    public $email;
    public $phone;
    public $newImage;
    public $showPhotoInput = false;

    public function mount()
    {
        $this->user = Auth::user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
    }

    public function updatedNewImage()
    {
        $this->validate([
            'newImage' => 'image|max:2048',
        ]);
    }

    public function removePhoto()
    {
        if ($this->user->image) {
            Storage::disk('public')->delete($this->user->image);
            $this->user->update(['image' => null]);
        }
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user->id,
            'phone' => 'nullable|string|max:20',
            'newImage' => 'nullable|image|max:2048',
        ];
    }

    public function update()
    {
        $this->validate();

        if ($this->newImage) {
            if ($this->user->image) {
                Storage::disk('public')->delete($this->user->image);
            }

            $this->user->image = $this->newImage->store('profile', 'public');
        }

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'image' => $this->user->image,
        ]);

        $this->dispatch('show-toast', [
            'message' => 'Profil berhasil diperbarui!',
            'type' => 'success',
        ]);

        $this->showPhotoInput = false;
        $this->newImage = null;
    }

    public function render()
    {
        return view('livewire.user.profile');
    }
}
