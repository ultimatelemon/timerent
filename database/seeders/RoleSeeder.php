<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'id' => '6e488921-9675-4e5e-b7b5-8b6acd1ecd18',
                'name' => 'Admin',
                'bitfield' => 33010254,
                'deletable' => false,
            ],
            [
                'id' => '509ab95a-9dbc-4857-a142-c3a1fa9a9812',
                'name' => 'User',
                'bitfield' => 0,
                'deletable' => false,
            ],
        ];

        foreach($roles as $role) {
            Role::create($role);
        }
    }
}
