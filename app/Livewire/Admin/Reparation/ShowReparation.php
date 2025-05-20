<?php

namespace App\Livewire\Admin\Reparation;

use App\Models\Reparation;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Session;

#[Layout('components.layouts.admin')]
class ShowReparation extends Component
{
    public Reparation $reparation;
    public bool $isProcessing = false;

    public $price; // Add this property

    public function mount(Reparation $reparation)
    {
        Session::reflash();
        $this->reparation = $reparation->fresh();
        $this->price = $this->reparation->price; // Initialize the price
    }

    public function hydrate()
    {
        Session::reflash();
    }

    public function confirmReparation()
    {
        $this->isProcessing = true;

        try {
            $this->reparation->update(['status' => 'confirmed']);

            // Notify the user about the confirmation
            $this->reparation->user->notify(new \App\Notifications\Reparation\RequestConfirmed($this->reparation));
            $this->dispatch('show-toast', [
                'message' => 'Perbaikan telah dikonfirmasi!',
                'type' => 'success',
            ])->self();

            $this->dispatch('reinitialize');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Gagal mengkonfirmasi perbaikan: ' . $e->getMessage(),
                'type' => 'error',
            ])->self();
        } finally {
            $this->isProcessing = false;
        }
    }

    public function startReparation()
    {
        try {
            $this->reparation->update(['status' => 'in_progress']);

            // Notify the user about the start of the reparation
            $this->reparation->user->notify(new \App\Notifications\Reparation\RequestProgressed($this->reparation));

            $this->dispatch('show-toast', [
                'message' => 'Perbaikan telah dimulai!',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Gagal memulai perbaikan: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function completeReparation()
    {
        try {
            if ($this->reparation->price == null) {
                $this->dispatch('show-toast', [
                    'message' => 'Harga perbaikan belum ditentukan!',
                    'type' => 'error',
                ]);
                return;
            }

            $this->reparation->update(['status' => 'completed']);
            $this->dispatch('show-toast', [
                'message' => 'Perbaikan telah selesai!',
                'type' => 'success',
            ]);

            // Notify the user about the completion
            $this->reparation->user->notify(new \App\Notifications\Reparation\ReparationCompleted($this->reparation));
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Gagal menyelesaikan perbaikan: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function cancelReparation()
    {
        try {
            $this->reparation->update(['status' => 'cancelled']);
            $this->dispatch('show-toast', [
                'message' => 'Perbaikan telah dibatalkan!',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Gagal membatalkan perbaikan: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function updatePrice()
    {
        $this->validate([
            'price' => 'required|numeric|min:0',
        ]);

        try {
            $this->reparation->update([
                'price' => $this->price,
            ]);

            $this->dispatch('show-toast', [
                'message' => 'Harga perbaikan berhasil diperbarui!',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Gagal memperbarui harga: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.reparation.show-reparation');
    }
}
