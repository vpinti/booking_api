<?php

namespace Tests\Feature;

use App\Models\Apartment;
use App\Models\ApartmentPrice;
use App\Models\City;
use App\Models\Property;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApartmentPriceTest extends TestCase
{
    use RefreshDatabase;

    private function create_apartment(): Apartment
    {
        $owner = User::factory()->create(['role_id' => Role::ROLE_OWNER]);
        $cityId = City::value('id');

        $property = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $cityId,
        ]);

        return Apartment::create([
            'name' => 'Apartment',
            'property_id' => $property->id,
            'capacity_adults' => 3,
            'capacity_children' => 2,
        ]);
    }
}
