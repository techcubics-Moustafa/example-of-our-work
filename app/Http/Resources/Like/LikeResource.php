<?php

namespace App\Http\Resources\Like;

use App\Http\Resources\RealEstate\RealEstateResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LikeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'like_id' => $this->id,
            'real_estates' => RealEstateResource::collection($this->whenLoaded('modelable')),
        ];
    }
}
