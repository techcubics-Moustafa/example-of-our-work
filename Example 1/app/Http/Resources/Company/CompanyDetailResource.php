<?php

namespace App\Http\Resources\Company;

use App\Http\Resources\Country\CountryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->id,
            'slug' => $this->translateOrDefault(locale())?->slug,
            'name' => $this->translateOrDefault(locale())?->name,
            'description' => $this->translateOrDefault(locale())?->description,
            'logo' => getAvatar($this->logo),
            'whatsapp' => $this->whatsapp_number,
            'location' => $this->location,
            'country' => CountryResource::make($this->whenLoaded('country')),
            'governorate' => CountryResource::make($this->whenLoaded('governorate')),
            'region' => CountryResource::make($this->whenLoaded('region')),
            'address' => $this->translateOrDefault(locale())?->address,
            'category' => CountryResource::make($this->whenLoaded('category')),
            'sub_category' => CountryResource::make($this->whenLoaded('subCategory')),
            'social_media' => $this->social_media,
        ];
    }
}
