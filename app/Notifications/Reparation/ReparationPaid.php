<?php

namespace App\Notifications\Reparation;

use App\Models\Reparation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReparationPaid extends Notification
{
    use Queueable;

    public $reparation;
    public $userRole;
    public $actionUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct(Reparation $reparation, string $userRole)
    {
        $this->reparation = $reparation;
        $this->userRole = $userRole;
        $this->actionUrl = $userRole === 'admin' ? url('/admin/reparations/' . $this->reparation->id) : url('/reparations/history');
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Pembayaran untuk Reparasi ' . $this->reparation->item_name . ' Telah Diterima')
                    ->lineIf($this->userRole === 'admin', 'Pembayaran untuk reparasi ' . $this->reparation->item_name . ' telah diterima pada ' . $this->reparation->updated_at->format('d-m-Y H:i:s'))
                    ->lineIf($this->userRole === 'user', 'Pembayaran untuk reparasi ' . $this->reparation->item_name . ' telah diterima pada ' . $this->reparation->updated_at->format('d-m-Y H:i:s'))
                    ->line('Device: ' . $this->reparation->item_name)
                    ->line('Deskripsi kerusakan: ' . $this->reparation->description)
                    ->line('Status: ' . $this->reparation->status)
                    ->lineIf($this->userRole === 'user', 'Silakan ambil device Anda di Unedo Cell pada jam kerja.')
                    ->action('Lihat Riwayat Reparasi', $this->actionUrl)
                    ->lineIf($this->userRole === 'admin', 'Silakan klik tombol di atas untuk melihat detail pembayaran.')
                    ->lineIf($this->userRole === 'user', 'Terima kasih telah menggunakan layanan kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'reparation_id' => $this->reparation->id,
            'title' => 'Pembayaran untuk Reparasi ' . $this->reparation->item_name . ' Telah Diterima',
            'message' => 'Pembayaran untuk reparasi ' . $this->reparation->item_name . ' telah diterima.',
            'action_url' => $this->actionUrl,
        ];
    }
}
