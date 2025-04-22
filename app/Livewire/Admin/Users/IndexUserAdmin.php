<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class IndexUserAdmin extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public int $perPage = 10;

    public function toggleStatus($userId)
    {
        $user = User::find($userId);

        if ($user) {
            $user->update(['is_active' => !$user->is_active]);

            $this->dispatch('show-toast', [
                'message' => $user->is_active ? 'Pengguna diaktifkan!' : 'Pengguna dinonaktifkan!',
                'type' => 'success',
            ]);
        } else {
            $this->dispatch('show-toast', [
                'message' => 'Pengguna tidak ditemukan!',
                'type' => 'error',
            ]);
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteUser($userId)
    {
        $user = User::find($userId);

        if ($user) {
            $user->delete();

            $this->dispatch('show-toast', [
                'message' => 'Pengguna berhasil dihapus!',
                'type' => 'success',
            ]);
        } else {
            $this->dispatch('show-toast', [
                'message' => 'Pengguna tidak ditemukan!',
                'type' => 'error',
            ]);
        }
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        $users = User::query()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })

            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.users.index-user-admin', [
            'users' => $users,
            'search' => $this->search,
            'sortField' => $this->sortField,
            'sortDirection' => $this->sortDirection,
            'perPage' => $this->perPage,
        ]);
    }
}
