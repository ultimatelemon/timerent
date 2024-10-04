<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Template>
 */
class TemplateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $temp = array_map(function ($day) {
            return [
                'day' => $day,
                'ranges' => [
                    [
                        'from' => '09:00',
                        'to' => '17:00',
                    ]
                ]
            ];
        }, range(0, 6));

        return [
            'name' => fake()->name(),
            'venue_id' => '9cfdc635-8ca8-4b44-998b-f0eec7b7bf31',
            'template' => $temp,
            'interval' => 60,
            'price' => 1000,
        ];
    }
}
