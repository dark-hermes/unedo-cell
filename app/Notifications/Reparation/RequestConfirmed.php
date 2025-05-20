<?php

namespace App\Notifications\Reparation;

use App\Models\Option;
use App\Models\Reparation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RequestConfirmed extends Notification
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
                    ->subject('Permintaan Reparasi Dikonfirmasi')
                    ->line('Permintaan reparasi Anda telah dikonfirmasi pada ' . $this->reparation->updated_at->format('d-m-Y H:i:s'))
                    ->line('Device: ' . $this->reparation->item_name)
                    ->line('Deskripsi kerusakan: ' . $this->reparation->description)
                    ->line('Status: ' . $this->reparation->status)
                    ->line('Lanjutkan komunikasi dengan teknisi via WhatsApp dengan mengklik tombol di bawah ini.')
                    ->action('Hubungi Teknisi', url('https://wa.me/' . Option::where('key', 'STORE_PHONE')->first()->value . '?text=Halo%20teknisi%2C%20saya%20dengan%20' . $this->reparation->user->name . '%20mau%20bertanya%20tentang%20perbaikan%20device%20saya%20' . $this->reparation->item_name . '%20dengan%20deskripsi%20' . $this->reparation->description))
                    
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
            'title' => 'Permintaan Reparasi Dikonfirmasi',
            'message' => 'Permintaan reparasi . ' . $this->reparation->item_name . ' telah dikonfirmasi',
            'action_url' => url('/reparations/' . $this->reparation->id . '/payment'),
        ];
    }
}
