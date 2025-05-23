<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;

class UsersCount extends Component
{
    public function render()
    {
        $usersCount = \App\Models\User::whereHas('roles', function ($query) {
            $query->where('name', '!=', 'admin');
        })->count();
        $usersCount = number_format($usersCount, 0, ',', '.');
        return view('livewire.admin.dashboard.users-count', [
            'usersCount' => $usersCount
        ]);
    }
}
