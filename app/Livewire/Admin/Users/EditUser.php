<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.admin')]
class EditUser extends Component
{
    use WithFileUploads;

    public User $user;

    public $name, $email, $phone, $is_active;
    public $newImage;
    public $showPhotoInput = false;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->is_active = $user->is_active;
    }

    public function updatedNewImage()
    {
        $this->validate([
            'newImage' => 'image|max:1024',
        ]);
    }


    public function removePhoto()
    {
        if ($this->user->image) {
            Storage::delete($this->user->image);
            $this->user->update(['image' => null]);
        }
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'phone' => 'nullable|string|max:16|unique:users,phone,' . $this->user->id,
            'is_active' => 'boolean',
            'newImage' => 'nullable|image|max:1024',
        ];
    }


    public function update()
    {
        $this->validate();

        if ($this->newImage) {
            if ($this->user->image) {
                Storage::delete($this->user->image);
            }

            $path = $this->newImage->store('profile-photos', 'public');
            $this->user->image = $path;
        }


        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_active' => $this->is_active,
        ]);

        $this->dispatch('show-toast', [
            'message' => 'Data pengguna diperbarui!',
            'type' => 'success',
        ]);

        $this->showPhotoInput = false;
        $this->newImage = null;
    }

    public function render()
    {
        return view('livewire.admin.users.edit-user');
    }
}
