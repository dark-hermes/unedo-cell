<?php

namespace App\Livewire\Admin\HomeContents;

use Livewire\Component;
use App\Models\HomeContent;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.admin')]
class EditHomeContent extends Component
{
    use WithFileUploads;

    public HomeContent $homeContent;
    public $title, $description;
    public $newImage;
    public $showPhotoInput = false;

    public function mount(HomeContent $homeContent)
    {
        $this->homeContent = $homeContent;
        $this->title = $homeContent->title;
        $this->description = $homeContent->description;
    }

    public function updatedNewImage()
    {
        $this->validate([
            'newImage' => 'image|max:2048',
        ]);
    }

    public function removePhoto()
    {
        if ($this->homeContent->image) {
            Storage::disk('public')->delete($this->homeContent->image);
            $this->homeContent->update(['image' => null]);
        }
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'newImage' => 'nullable|image|max:2048', // 2MB Max
        ];
    }

    public function update()
    {
        $this->validate();

        if ($this->newImage) {
            if ($this->homeContent->image) {
                Storage::disk('public')->delete($this->homeContent->image);
            }
            $imagePath = $this->newImage->store('home_contents', 'public');
            $this->homeContent->image = $imagePath;
        }

        $this->homeContent->title = $this->title;
        $this->homeContent->description = $this->description;
        $this->homeContent->save();

        $this->dispatch('show-toast', [
            'message' => 'Konten Beranda berhasil diperbarui!',
            'type' => 'success',
        ]);
        
        $this->showPhotoInput = false;
        $this->newImage = null;
    }


    public function render()
    {
        return view('livewire.admin.home-contents.edit-home-content');
    }
}
