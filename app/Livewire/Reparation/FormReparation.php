<?php

namespace App\Livewire\Reparation;

use App\Models\User;
use Livewire\Component;
use App\Models\Fileable;
use App\Models\Reparation;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Services\TelegramNotificationService;

#[Layout('components.layouts.app')]
class FormReparation extends Component
{
    use WithFileUploads;

    public $item_name;
    public $item_type;
    public $item_brand;
    public $description;
    public $files = [];
    public $status = 'pending';

    protected $rules = [
        'item_name' => 'required|string|max:255',
        'item_type' => 'required|string',
        'item_brand' => 'nullable|string|max:255',
        'description' => 'required|string|min:10',
        'files.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:10240', // 10MB max
    ];

    public function submit()
    {
        $this->validate();

        // Create reparation record
        $reparation = Reparation::create([
            'user_id' => Auth::id(),
            'item_name' => $this->item_name,
            'item_type' => $this->item_type,
            'item_brand' => $this->item_brand,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        // Handle file uploads
        if ($this->files) {
            logger()->info('File Bukti Upload', [
                'files' => $this->files,
            ]);
            foreach ($this->files as $file) {
                $path = $file->store('reparations', 'public');
                
                Fileable::create([
                    'fileable_id' => $reparation->id,
                    'fileable_type' => Reparation::class,
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                ]);
            }
        }

        $telegramService = new TelegramNotificationService();
        $telegramService->sendReparationNotification($reparation);

        // Notify the admin
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\Reparation\RequestCreated($reparation));
        }

        // Reset form
        $this->reset(['item_name', 'item_type', 'item_brand', 'description', 'files']);

        // Show success message
        $this->dispatch('swal', [
            'title' => 'Reparasi Diajukan',
            'text' => 'Permohonan reparasi Anda telah diajukan!',
            'icon' => 'success',
        ]);
        
        // Redirect to history page
        // return redirect()->route('rp-history');
    }

    public function render()
    {
        return view('livewire.reparation.form-reparation');
    }
}