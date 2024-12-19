<?php

namespace App\Notifications;

use App\Models\Venue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpdatedPaymentSettings extends Notification
{
    use Queueable;

    /**
     * Venue instance
     *
     * @var Venue
     */
    private Venue $venue;

    /**
     * Create a new notification instance.
     */
    public function __construct(Venue $venue)
    {
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
                    ->subject('Betaalinstellingen voor ' . $this->venue->name . ' zijn aangepast.')
                    ->line('Via deze mail willen wij je laten weten dat je betaalinstellingen zijn aangepast.');
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
