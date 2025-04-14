<?php

namespace App\View\Components\admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SidebarItem extends Component
{
    public $active;
    public $href;
    public $icon;
    public $label;
    public $hasSub;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $active = false,
        $href = '#',
        $icon = '',
        $label = '',
        $hasSub = false

    ) {
        $this->active = $active;
        $this->href = $href;
        $this->icon = $icon;
        $this->label = $label;
        $this->hasSub = $hasSub;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.sidebar-item');
    }
}
