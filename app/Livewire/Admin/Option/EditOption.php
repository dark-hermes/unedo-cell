<?php

namespace App\Livewire\Admin\Option;

use App\Models\Option;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.admin')]
class EditOption extends Component
{
    use WithFileUploads;

    public Option $option;
    public $key, $value, $type;
    public $newImage;
    public $showPhotoInput = false;
    public $searchLocation = '';

    public function mount(Option $option)
    {
        $this->option = $option;
        $this->key = $option->key;
        $this->value = $option->value;
        $this->type = $option->type;
    }

    public function updatedNewImage()
    {
        $this->validate([
            'newImage' => 'image|max:2048',
        ]);
    }

    public function removePhoto()
    {
        if ($this->option->value) {
            Storage::disk('public')->delete($this->option->value);
            $this->option->update(['value' => null]);
            $this->value = null;
        }
    }

    public function rules()
    {
        $rules = [
            'key' => 'required|string|max:255',
            'value' => 'nullable|string|max:255',
            'type' => 'required|string|in:text,image,file',
            'newImage' => 'nullable|image|max:2048',
        ];

        if ($this->key === 'STORE_COORDINATE') {
            $rules['value'] = ['required', 'regex:/^-?\d+\.\d+,-?\d+\.\d+$/'];
        }

        return $rules;
    }

    public function save()
    {
        $this->validate();

        if ($this->newImage) {
            if ($this->option->value) {
                Storage::disk('public')->delete($this->option->value);
            }
            $this->value = $this->newImage->store('options', 'public');
        }

        $this->option->update([
            'key' => $this->key,
            'value' => $this->value,
            'type' => $this->type,
        ]);

        $this->dispatch('show-toast', [
            'message' => 'Option updated successfully.',
            'type' => 'success',
        ]);

        $this->showPhotoInput = false;
        $this->newImage = null;
    }

    public function updateLocation($lat, $lng)
    {
        if (!is_numeric($lat) || !is_numeric($lng)) {
            return;
        }
        $this->value = $lat . ',' . $lng;
        $this->validateOnly('value');

        $this->dispatch('locationUpdated', ['lat' => $lat, 'lng' => $lng]);
    }

    public function render()
    {
        $types = [
            'text' => 'Text',
            'image' => 'Image',
            'file' => 'File',
        ];

        return view('livewire.admin.option.edit-option', [
            'types' => $types,
            'mapCenter' => $this->getMapCenter(),
            'initialValue' => $this->value,
        ]);
    }

    private function getMapCenter()
    {
        if ($this->value && preg_match('/^-?\d+\.\d+,-?\d+\.\d+$/', $this->value)) {
            [$lat, $lng] = explode(',', $this->value);
            return ['lat' => (float)$lat, 'lng' => (float)$lng];
        }
        return ['lat' => -6.2088, 'lng' => 106.8456]; // Default to Jakarta coordinates
    }
}