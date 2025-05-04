<?php

namespace App\Livewire\Admin\HomeContents;

use Livewire\Component;
use App\Models\HomeContent;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class CreateHomeContent extends Component
{
    use WithFileUploads;

    public $title;
    public $description;
    public $image;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string|max:1000',
        'image' => 'nullable|image|max:2048', // 2MB Max
    ];

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function store()
    {
        $this->validate();

        try {
            $imagePath = null;
            if ($this->image) {
                $imagePath = $this->image->store('home_contents', 'public');
            }

            // Assuming you have a HomeContent model
            HomeContent::create([
                'title' => $this->title,
                'description' => $this->description,
                'image' => $imagePath,
            ]);

            $this->dispatch('show-toast', [
                'message' => 'Konten Beranda berhasil dibuat!',
                'type' => 'success',
            ]);
            return redirect()->route('admin.home-contents.index');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Gagal membuat konten beranda: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.home-contents.create-home-content');
    }
}
