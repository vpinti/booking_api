<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\PropertySearchResource;
use App\Models\Facility;
use App\Models\Geoobject;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertySearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $propertiesQuery = Property::query()
            ->with([
                'city',
                'apartments.apartment_type',
                'apartments.rooms.beds.bed_type',
                'apartments.prices' => function($query) use ($request) {
                    $query->validForRange([
                        $request->start_date ?? now()->addDay()->toDateString(),
                        $request->end_date ?? now()->addDays(2)->toDateString(),
                    ]);
                },
                'facilities',
                'media' => fn($query) => $query->orderBy('position'),
            ])
            ->withAvg('bookings', 'rating')
            ->when($request->city, function($query) use ($request) {
                $query->where('city_id', $request->city);
            })
            ->when($request->country, function($query) use ($request) {
                $query->whereHas('city', fn($q) => $q->where('country_id', $request->country));
            })
            ->when($request->geoobject, function($query) use ($request) {
                $geoobject = Geoobject::find($request->geoobject);
                if ($geoobject) {
                    $condition = "(
                        6371 * acos(
                            cos(radians(" . $geoobject->lat . "))
                            * cos(radians(`lat`))
                            * cos(radians(`long`) - radians(" . $geoobject->long . "))
                            + sin(radians(" . $geoobject->lat . ")) * sin(radians(`lat`))
                        ) < 10
                    )";
                    $query->whereRaw($condition);
                }
            })
            ->when($request->adults && $request->children, function($query) use ($request) {
                $query->withWhereHas('apartments', function($query) use ($request) {
                    $query->where('capacity_adults', '>=', $request->adults)
                        ->where('capacity_children', '>=', $request->children)
                        ->orderBy('capacity_adults')
                        ->orderBy('capacity_children')
                        ->take(1);
                });
            })
            ->when($request->facilities, function($query) use ($request) {
                $query->whereHas('facilities', function($query) use ($request) {
                    $query->whereIn('facilities.id', $request->facilities);
                });
            })
            ->when($request->price_from, function($query) use ($request) {
                $query->whereHas('apartments.prices', function($query) use ($request) {
                    $query->where('price', '>=', $request->price_from);
                });
            })
            ->when($request->price_to, function($query) use ($request) {
                $query->whereHas('apartments.prices', function($query) use ($request) {
                    $query->where('price', '<=', $request->price_to);
                });
            })
            ->orderBy('bookings_avg_rating', 'desc');

            $facilities = Facility::query()
                ->withCount(['properties' => function ($property) use ($propertiesQuery) {
                    // THIS ->pluck() NOW GETS IDs DIRECTLY FROM DB
                    $property->whereIn('id', $propertiesQuery->pluck('id'));
                }])
                ->get()
                ->where('properties_count', '>', 0)
                ->sortByDesc('properties_count')
                ->pluck('properties_count', 'name');

            // WE ADD ->paginate(10) HERE INSTEAD
            $properties = $propertiesQuery->paginate(10)->withQueryString();

            return [
                // https://laracasts.com/discuss/channels/laravel/paginate-while-returning-array-of-api-resource-objects-to-the-resource-collection?page=1&replyId=575401
                'properties' => PropertySearchResource::collection($properties)
                    ->response()
                    ->getData(true),
                'facilities' => $facilities,
            ];
    }
}
