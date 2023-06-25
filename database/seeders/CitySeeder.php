<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\City;

class CitySeeder extends Seeder
{
    public function run()
    {
        City::create([
            'country_id' => 1,
            'name' => 'New York',
            'lat' => 40.712776,
            'long' => -74.005974,
        ]);

        City::create([
            'country_id' => 2,
            'name' => 'London',
            'lat' => 51.507351,
            'long' => -0.127758,
        ]);
    }
}
