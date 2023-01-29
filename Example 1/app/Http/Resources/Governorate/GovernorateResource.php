<?php

namespace App\Http\Resources\Governorate;

use App\Http\Resources\Country\CountryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GovernorateResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'governorate_id' => $this->id,
            'name' => $this->translateOrDefault(locale())?->name,
            'slug' => $this->translateOrDefault(locale())?->slug,
            'country' => CountryResource::make($this->whenLoaded('country'))
        ];
    }
}
