<?php

namespace App\Livewire\Admin\Reparation;

use Livewire\Component;
use App\Models\Reparation;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class IndexReparation extends Component
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

    public function render()
    {
        $reparations = Reparation::query()
            ->when($this->search, function ($query) {
                $query->where('item_name', 'like', '%' . $this->search . '%')
                    ->orWhere('item_type', 'like', '%' . $this->search . '%')
                    ->orWhere('item_brand', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhere('status', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.reparation.index-reparation', [
            'reparations' => $reparations,
        ]);
    }
}
