<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Country;

class CountrySeeder extends Seeder
{
    public function run()
    {
        Country::create([
            'name' => 'United States',
            'lat' => 37.09024,
            'long' => -95.712891
        ]);
        Country::create([
            'name' => 'United Kingdom',
            'lat' => 55.378051,
            'long' => -3.435973
        ]);
    }
}
