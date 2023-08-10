<?php

namespace App\Http\Resources;

use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApartmentSearchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'type' => $this->apartment_type?->name,
            'size' => $this->size,
            'beds_list' => $this->bedsList,
            'bathrooms' => $this->bathrooms,
            'facilities' => FacilityResource::collection($this->whenLoaded('facilities')),
            'price' => (new PricingService())->calculateApartmentPriceForDates($this->prices, $request->start_date, $request->end_date),
        ];
    }
}
