<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;

#[Layout('components.layouts.admin')]
class CreateUserAdmin extends Component
{
    public $name;
    public $email;
    public $phone;
    public $password;
    public $password_confirmation;
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'nullable|string|max:16|unique:users,phone',
        'password' => 'required|string|min:8|confirmed',
        'is_active' => 'boolean',
    ];

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function store()
    {
        $this->validate();

        try {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'password' => Hash::make($this->password),
                'is_active' => $this->is_active,
            ]);

            $user->assignRole('admin');

            $this->dispatch('show-toast', [
                'message' => 'Pengguna berhasil dibuat!',
                'type' => 'success',
            ]);

            $this->reset();
        } catch (\Throwable $e) {
            report($e); // optional: kirim ke log
            $this->dispatch('show-toast', [
                'message' => 'Gagal membuat pengguna: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.users.create-user-admin');
    }
}
