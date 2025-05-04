<?php

namespace App\Livewire\Admin\HomeContents;

use Livewire\Component;
use App\Models\HomeContent;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class IndexHomeContent extends Component
{
    use WithPagination;
    #[Url]
    public string $search = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public int $perPage = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteHomeContent($homeContentId)
    {
        $homeContent = HomeContent::find($homeContentId);
        if ($homeContent) {
            $homeContent->delete();

            $this->dispatch('show-toast', [
                'message' => 'Konten Beranda berhasil dihapus!',
                'type' => 'success',
            ]);
        } else {
            $this->dispatch('show-toast', [
                'message' => 'Konten Beranda tidak ditemukan!',
                'type' => 'error',
            ]);
        }
    }

    public function render()
    {
        $homeContents = HomeContent::query()
            ->when(
                $this->search,
                fn($query) =>
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
            )
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
        return view('livewire.admin.home-contents.index-home-content', [
            'homeContents' => $homeContents,
        ]);
    }
}
