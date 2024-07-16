<?php

namespace App\Notifications\Traits;

use Illuminate\Notifications\Notifiable;

class EmailNotifiable
{
    use Notifiable;

    /**
     * The email
     *
     * @var string
     */
    protected string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function routeNotificationFor($notification): string
    {
        return $this->email;
    }
}