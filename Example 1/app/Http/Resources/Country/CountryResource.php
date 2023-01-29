<?php

namespace App\Http\Resources\Country;

use App\Http\Resources\Currency\CurrencyResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'country_id' => $this->id,
            'code' => $this->code,
            'name' => $this->translateOrDefault(locale())?->name,
            'slug' => $this->translateOrDefault(locale())?->slug,
            'flag' => getAvatar($this->icon),
            'currency' => CurrencyResource::make($this->whenLoaded('currency')),
        ];
    }
}
