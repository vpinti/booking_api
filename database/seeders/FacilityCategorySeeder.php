<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FacilityCategory;

class FacilityCategorySeeder extends Seeder
{
    public function run(): void
    {
        FacilityCategory::create(['name' => 'Bedroom']);
        FacilityCategory::create(['name' => 'Kitchen']);
        FacilityCategory::create(['name' => 'Bathroom']);
        FacilityCategory::create(['name' => 'Room Amenities']);
        FacilityCategory::create(['name' => 'General']);
        FacilityCategory::create(['name' => 'Media & Technology']);
    }
}
