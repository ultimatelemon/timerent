<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Venue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class OnboardEmail extends Notification
{
    use Queueable;

    /**
     * The venue instance
     *
     * @var Venue
     */
    private Venue $venue;

    /**
     * The user instance
     *
     * @var User
     */
    private User $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(Venue $venue, User $user)
    {
        $this->venue = $venue;
        $this->user = $user;
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
            ->subject('Welkom bij de club, ' . $this->venue->name . '!')
            ->line('Van harte welkom bij Timerent, ' . $this->user->name . '!')
            ->line(new HtmlString(
                'Om goed van start te gaan met <b>'. $this->venue->name .'</b> hebben we een snelle stappenplan voor je gemaakt.'
            ))
            ->line(new HtmlString('<b>1. </b>
                    Klik op je vestiging om naar je beheerpagina te gaan. Vervolgens selecteer je links in je menu "Beheer" en ga je naar algemene instellingen. Vul deze gegevens aan zover het nog niet is gevuld, zo worden je automatische facturen goed gemaild naar je klanten  na elke reservering :) 
                    '))
            ->line(new HtmlString('<b>2. </b>
                    Vervolgens kun je bij finance instellingen je betaalmethode kiezen. Heb je al een Mollie account? Vul gemakkelijk je live api key in, of onboard je via Timerent payments. Betalingen hiervan worden op elke 3e van de maand overgemaakt en gemakkelijk inzichtelijk in je persoonlijke Stripe account die je direct kunt aanmaken. Heb je elke week een uitbetaling nodig of elke dag? Neem dan contact met ons op, dan passen wij dit graag voor je aan! 
                    '))
            ->line(new HtmlString('<b>3. </b>
                    Zo snel kan het gaan! Maak nu gemakkelijk je units aan onder het kopje "Applicatie", vervolgens kun je eventueel producten aanmaken, je eerste template en templates toewijzen aan je ruimtes in de agenda. 
                    '))
            ->line('Heb je na onze fantastische stappenplan nog vragen? Maak dan een ticket aan onder de "Help" knop. Onze medewerkers staan elke dag voor je klaar. Succes met Timerent!')
            ->action('Direct naar je vestiging', url(env('APP_URL') . '/store/' . $this->venue->id . '/home'))
            ->line('Thank you for using our application!');
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
