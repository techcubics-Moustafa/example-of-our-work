<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'service_id' => $this->id,
            'ranking' => $this->ranking,
            'name' => $this->translateOrDefault(locale())?->name,
            'slug' => $this->translateOrDefault(locale())?->slug,
        ];
    }
}
