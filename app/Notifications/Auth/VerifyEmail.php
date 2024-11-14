<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Hash;

class VerifyEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
            ->subject('Verifieer je e-mail bij Timerent')
            ->line('Welkom bij Timerent.')
            ->line('Verifieer je email door op de onderstaande knop te drukken.')
            ->action('Je email verifieren', url(env('APP_URL') . '/email/verify?user=' . $notifiable->id . '&token=' . Hash::make($notifiable->email . $notifiable->email_verification_token)))
            ->line('Heb je zelf geen account aangemaakt? Dan hoef je verder geen actie te ondernemen')
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
