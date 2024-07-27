<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run($venueId): void
    {
        if(!$venueId) return;

        $settings = [

            // General

            [
                'id' => Str::uuid(),
                'key' => 'name',
                'value' => null,
                'category' => 'general',
                'type' => 'text',
                'display_name' => 'Bedrijfsnaam',
                'required' => true,
                'venue_id' => $venueId,
            ],
            [
                'id' => Str::uuid(),
                'key' => 'address',
                'value' => null,
                'category' => 'general',
                'type' => 'text',
                'display_name' => 'Adres',
                'required' => true,
                'venue_id' => $venueId,
            ],
            [
                'id' => Str::uuid(),
                'key' => 'postal_code',
                'value' => null,
                'category' => 'general',
                'type' => 'text',
                'display_name' => 'Postcode',
                'required' => true,
                'venue_id' => $venueId,
            ],
            [
                'id' => Str::uuid(),
                'key' => 'city',
                'value' => null,
                'category' => 'general',
                'type' => 'text',
                'display_name' => 'Plaats',
                'required' => true,
                'venue_id' => $venueId,
            ],
            [
                'id' => Str::uuid(),
                'key' => 'phone_number_support',
                'value' => null,
                'category' => 'general',
                'type' => 'text',
                'display_name' => 'Telefoonnummer support',
                'required' => true,
                'venue_id' => $venueId,
            ],
            [
                'id' => Str::uuid(),
                'key' => 'coc_number',
                'value' => null,
                'category' => 'general',
                'type' => 'text',
                'display_name' => 'KVK nummer',
                'required' => true,
                'venue_id' => $venueId,
            ],
            [
                'id' => Str::uuid(),
                'key' => 'tax_number',
                'value' => null,
                'category' => 'general',
                'type' => 'text',
                'display_name' => 'BTW Nummer',
                'required' => true,
                'venue_id' => $venueId,
            ],

            // Finance

            [
                'id' => Str::uuid(),
                'key' => 'payment_provider',
                'value' => 'timerent',
                'category' => 'finance',
                'type' => 'text',
                'display_name' => 'Seleteer je betaalprovider',
                'required' => true,
                'venue_id' => $venueId,
            ],
            [
                'id' => Str::uuid(),
                'key' => 'payment_api_key',
                'value' => '',
                'category' => 'finance',
                'type' => 'text',
                'display_name' => 'API key',
                'required' => false,
                'venue_id' => $venueId,
            ],

            // Reservation

            [
                'id' => Str::uuid(),
                'key' => 'reservation_prefix',
                'value' => 'TR-',
                'category' => 'reservations',
                'type' => 'text',
                'display_name' => 'Reservering prefix',
                'required' => true,
                'venue_id' => $venueId,
            ],
            [
                'id' => Str::uuid(),
                'key' => 'cancellation_hours',
                'value' => '24',
                'category' => 'reservations',
                'type' => 'number',
                'display_name' => 'Minimaal x uren voor annulering',
                'required' => false,
                'venue_id' => $venueId,
            ],
        ];

        DB::table('settings')->insert($settings);
    }
}
