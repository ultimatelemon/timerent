<?php

namespace App\Notifications\Application;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ApplicationReservationCancelled extends Notification
{
    use Queueable;

    /**
     * The reservation instance
     *
     * @var Reservation
     */
    private Reservation $reservation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
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
            ->subject('Reservering geannuleerd')
            ->line('Hi, ' . $notifiable->name . '!')
            ->line(new HtmlString('Bij deze bevestigen wij je annuleren voor reservering <b>#' . strtoupper(explode('-', $this->reservation->id)[0]) . '</b>.'))
            ->line(new HtmlString('Het totaalbedrag van <b>€' . number_format($this->reservation->payment_amount / 100, '2', ',' , '.') . '</b> zal binnen 10 dagen worden teruggestort.'))
            ->line('We hopen je snel weer te zien!');
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
