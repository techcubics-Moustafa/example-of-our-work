<?php

namespace App\Http\Resources\Region;

use App\Http\Resources\Country\CountryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RegionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'region_id' => $this->id,
            'name' => $this->translateOrDefault(locale())?->name,
            'slug' => $this->translateOrDefault(locale())?->slug,
            'country' => CountryResource::make($this->whenLoaded('country')),
            'governorate' => CountryResource::make($this->whenLoaded('governorate')),
        ];
    }
}
