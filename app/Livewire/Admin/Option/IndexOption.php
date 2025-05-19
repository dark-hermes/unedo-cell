<?php

namespace App\Livewire\Admin\Option;

use App\Models\Option;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class IndexOption extends Component
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
        $options = Option::query()
            ->where('key', 'like', '%' . $this->search . '%')
            ->orWhere('value', 'like', '%' . $this->search . '%')
            ->orWhere('type', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
        return view('livewire.admin.option.index-option', [
            'options' => $options,
        ]);
    }
}
