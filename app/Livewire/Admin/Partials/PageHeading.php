<?php

namespace App\Livewire\Admin\Partials;

use Livewire\Component;

class PageHeading extends Component
{
    public string $title;
    public string $subtitle;
    public array $breadcrumbs = [];

    public function render()
    {
        return view('livewire.admin.partials.page-heading');
    }
}
