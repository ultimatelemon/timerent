<?php

namespace App\Console\Commands;

use Database\Seeders\SettingSeeder;
use Illuminate\Console\Command;

class venueSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'venue:seed {venue_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed a venue';

    /**
     * Execute the console command.
     */
    public function handle(SettingSeeder $seeder)
    {
        $venue = $this->argument('venue_id');
        $seeder->run($venue);
    }
}
