<?php

namespace Database\Seeders\Performance;

use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(int $owners = 100, int $users = 100): void
    {
        User::factory($owners)->owner()->create();
        User::factory($users)->user()->create();
    }
}
