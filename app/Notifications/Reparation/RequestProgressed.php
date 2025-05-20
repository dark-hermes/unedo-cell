<?php

namespace App\Notifications\Reparation;

use App\Models\Option;
use App\Models\Reparation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestProgressed extends Notification
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
                    ->subject('Reparasi '. $this->reparation->item_name . ' Anda Sedang Dikerjakan oleh Teknisi')
                    ->line('Permintaan reparasi Anda mulai dikerjakan pada ' . $this->reparation->updated_at->format('d-m-Y H:i:s'))
                    ->line('Device: ' . $this->reparation->item_name)
                    ->line('Deskripsi kerusakan: ' . $this->reparation->description)
                    ->line('Untuk komunikasi lebih lanjut, silakan hubungi teknisi melalui WhatsApp dengan mengklik tombol di bawah ini.')
                    ->action('Hubungi Teknisi', url('https://wa.me/' . Option::where('key', 'STORE_PHONE')->first()->value))
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
            'title' => 'Permintaan Reparasi Sedang Dikerjakan',
            'message' => 'Permintaan reparasi ' . $this->reparation->item_name . ' Anda sedang dikerjakan oleh teknisi.',
            'action_url' => url('/reparations/history/'),
        ];
    }
}
