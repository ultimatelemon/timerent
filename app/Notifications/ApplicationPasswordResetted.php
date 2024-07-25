<?php

namespace App\Notifications;

use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationPasswordResetted extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    /**
     * The member instance
     *
     * @var Member
     */
    private Member $member;

    public function __construct(Member $member)
    {
        $this->member = $member;
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
            ->subject('Je wachtwoord is gereset')
            ->line('Je wachtwoord is succesvol gereset!')
            ->line('Heb je dit niet zelf gedaan? Dan raden wij je aan om zo snel mogelijk actie te ondernemen.');
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
