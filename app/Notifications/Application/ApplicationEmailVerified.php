<?php

namespace App\Notifications\Application;

use App\Models\Venue;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationEmailVerified extends Notification
{
    use Queueable;
    /**
     * Create a new notification instance.
     */
    public function __construct()
    {

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Je e-mail is geverifieerd.')
            ->line('Je email is succesvol geverifieerd.')
            ->line('Je kunt nu aan de slag met je account!')
            ->line('Heb je eerder een reservering geplaatst bij ons met hetzelfde mail-adres? Dan komen deze automatisch in je account.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
