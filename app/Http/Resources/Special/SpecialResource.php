<?php

namespace App\Http\Resources\Special;

use App\Http\Resources\RealEstate\RealEstateResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecialResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'special_id' => $this->id,
            'slug' => $this->translateOrDefault(locale())?->slug,
            'name' => $this->translateOrDefault(locale())?->name,
            'real_estates' => RealEstateResource::collection($this->whenLoaded('realEstates')),
        ];
    }
}
