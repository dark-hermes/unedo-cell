<?php

namespace App\Notifications\Reparation;

use App\Models\Reparation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReparationCompleted extends Notification
{
    use Queueable;
    public $reparation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Reparation $reparation)
    {
        $this->reparation = $reparation;
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
                    ->subject('Reparasi '. $this->reparation->item_name . ' Anda Telah Selesai')
                    ->line('Permintaan reparasi Anda telah selesai pada ' . $this->reparation->updated_at->format('d-m-Y H:i:s'))
                    ->line('Device: ' . $this->reparation->item_name)
                    ->line('Deskripsi kerusakan: ' . $this->reparation->description)
                    ->line('Status: ' . $this->reparation->status)
                    ->line('Silakan ambil device Anda dan lakukan pembayaran dengan mengklik tombol di bawah ini.')
                    ->action('Bayar Sekarang', url('/reparations/' . $this->reparation->id . '/payment'))
                    ->line('Terima kasih telah menggunakan layanan kami!');
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
            'title' => 'Reparasi '. $this->reparation->item_name . ' Anda Telah Selesai',
            'message' => 'Permintaan reparasi '. $this->reparation->item_name . ' Anda telah selesai.',
            'action_url' => url('/reparations/history/'),
        ];
    }
}
