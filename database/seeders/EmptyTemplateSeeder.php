<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmptyTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $temp = array_map(function ($day) {
            return [
                'day' => $day,
                'ranges' => [
                    [
                        'from' => '09:00',
                        'till' => '17:00',
                    ]
                ]
            ];
        }, range(0, 6));


        // Todo: Venue ID
        $template = new Template;
        $template->id = "0a2f32e0-3002-4348-8576-1979b9905c2e";
        $template->venue_id = '9c2ebd0e-f6fc-4824-9425-00ad75b04645';
        $template->name = "Lege template";
        $template->price = 2000;
        $template->visible = false;
        $template->template = $temp;
        $template->save();
    }
}
