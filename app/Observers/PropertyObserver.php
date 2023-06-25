<?php

namespace App\Observers;

use App\Models\Property;
use Spatie\Geocoder\Facades\Geocoder;

class PropertyObserver
{
    public function creating(Property $property)
    {
        // We also add the owner automatically
        if (auth()->check()) {
            $property->owner_id = auth()->id();
        }

        if (is_null($property->lat) && is_null($property->long) && !(app()->environment('testing'))) {
            $fullAddress = $property->address_street . ', '
                . $property->address_postcode . ', '
                . $property->city->name . ', '
                . $property->city->country->name;

            $result = Geocoder::getCoordinatesForAddress($fullAddress);

            if (!empty($result)) {
                $property->lat = $result['lat'];
                $property->long = $result['lng'];
            }
        }
    }
}
