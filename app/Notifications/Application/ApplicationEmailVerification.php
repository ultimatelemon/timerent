<?php

namespace App\Notifications\Application;

use App\Models\Member;
use App\Models\Venue;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Hash;

class ApplicationEmailVerification extends Notification
{
    use Queueable;

    /**
     * The URL instance
     *
     * @var string
     */
    private string $url;

    /**
     * The venue member instance
     *
     * @var Member
     */
    private Member $member;

    /**
     * The venue member instance
     *
     * @var Venue
     */
    private Venue $venue;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $url, Member $member, Venue $venue)
    {
        $this->url = $url;
        $this->member = $member;
        $this->venue = $venue;
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
            ->line("Welkom bij {$this->venue->name}")
            ->line('Verifieer je email door op de onderstaande knop te drukken.')
            ->action('Je email verifieren', url($this->url))
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
