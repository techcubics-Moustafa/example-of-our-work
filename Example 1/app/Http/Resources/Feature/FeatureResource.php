<?php

namespace App\Http\Resources\Feature;

use Illuminate\Http\Resources\Json\JsonResource;

class FeatureResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'feature_id' => $this->id,
            'name' => $this->translateOrDefault(locale())?->name,
            'slug' => $this->translateOrDefault(locale())?->slug,
        ];
    }
}
