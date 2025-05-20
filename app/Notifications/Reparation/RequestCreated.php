<?php

namespace App\Notifications\Reparation;

use App\Models\Reparation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestCreated extends Notification
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
                    ->subject('Permintaan Reparasi Baru')
                    ->line('Permintaan reparasi baru telah dibuat pada ' . $this->reparation->created_at->format('d-m-Y H:i:s'))
                    ->line('Nama pemohon: ' . $this->reparation->user->name)
                    ->line('Kontak pemohon: ' . $this->reparation->user->phone)
                    ->line('Device: ' . $this->reparation->item_name)
                    ->line('Deskripsi kerusakan: ' . $this->reparation->description)
                    ->action('Lihat Permintaan', url('/admin/reparations/' . $this->reparation->id));
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
            'title' => 'Permintaan Reparasi Baru',
            'message' => 'Permintaan reparasi baru telah dibuat dari ' . $this->reparation->user->name,
            'action_url' => url('/admin/reparations/' . $this->reparation->id),
        ];
    }
}
