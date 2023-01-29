<?php

namespace App\Listeners;

use Stevebauman\Location\Facades\Location;

class GetLocationsByIP
{
    public function handle($event): void
    {
        if ($position = Location::get(request()->ip())) {
            $event->customer->activityLogs()->updateOrCreate([
                'date' => now()->format('Y-m-d')
            ], [
                'ip' => $position->ip,
                'country_name' => $position->countryName,
                'country_code' => $position->countryCode,
                'region_code' => $position->regionCode,
                'region_name' => $position->regionName,
                'city_name' => $position->cityName,
                'zip_code' => $position->zipCode,
                'latitude' => $position->latitude,
                'longitude' => $position->longitude,
                'timezone' => $position->timezone,
                'area_code' => $position->areaCode,
            ]);
        }
    }
}
