<?php

namespace App\Notifications;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Nette\Utils\Html;

class ReservationConfirmation extends Notification
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
        $groupedBlocks = $this->reservation->timeblocks->groupBy('unit_name');
        $products = collect($this->reservation->products);
        $mappedProducts = $products->map(function($product) {
           return $product->name;
        });

        $mailMessage = (new MailMessage)
            ->subject('Bevestiging van je reservering via Timerent')
            ->line(new HtmlString('Je reservering met nummer <b>#'.strtoupper(explode('-', $this->reservation->id)[0]).'</b> is succesvol bevestigd!'))
            ->line('Bekijk hieronder je reserverings details.')
            ->line(new HtmlString('<b>Datum</b>: ' . Carbon::parse($this->reservation->date)->format('d-m-Y')));

            foreach ($groupedBlocks as $unitName => $timeblocks) {
                $length = count($timeblocks) - 1;
                $mailMessage->line(new HtmlString('<b>' . $unitName . '</b>' . ' — ' . Carbon::parse($timeblocks[0]['from'])->format('H:i') . ' - ' . Carbon::parse($timeblocks[$length]['to'])->format('H:i')));
            }

            $mailMessage->line(new HtmlString('<b>Product(en): </b>' . $mappedProducts->implode(', ')));

        return $mailMessage;
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
