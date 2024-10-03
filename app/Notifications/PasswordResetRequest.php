<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetRequest extends Notification
{
    use Queueable;

    /**
     * The user instance
     *
     * @var User
     */
    private User $user;

    /**
     * The url to reset the password
     *
     * @var string
     */
    private string $url;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, string $url)
    {
        $this->user = $user;
        $this->url = $url;
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
            ->subject('Reset je wachtwoord')
            ->line('Er is een wachtwoord reset aangevraagd voor jouw account.')
            ->action('Reset je wachtwoord', url($this->url))
            ->line('Heb je zelf geen verzoek aangevraagd? Dan hoef je verder geen actie te ondernemen')
            ->line('* Deze link is 2 uur geldig');
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
