<?php

namespace App\Http\Resources\Property;

use App\Http\Resources\File\FileResource;
use App\Http\Resources\Project\ProjectResource;
use App\Http\Resources\RealEstate\RealEstateDetailResource;
use App\Http\Resources\RealEstate\RealEstateEditResource;
use App\Http\Resources\RealEstate\RealEstateResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'property_id' => $this->id,
            'type' => $this->type,
            'status' => $this->status,
            'moderation_status' => $this->moderation_status,
            'project' => ProjectResource::make($this->whenLoaded('project')),
            'number_bedrooms' => $this->number_bedrooms,
            'number_bathrooms' => $this->number_bathrooms,
            'number_floors' => $this->number_floors,
            'square' => $this->square,
            'price' => $this->price,
            'images' => FileResource::collection($this->whenLoaded('images')),
            'real_estate' => RealEstateResource::make($this->whenLoaded('realEstate')),
            $this->mergeWhen(request()->routeIs('api.property.show'), [
                'real_estate_details' => RealEstateEditResource::make($this->whenLoaded('realEstateDetail')),
            ]),
            $this->mergeWhen(!request()->routeIs('api.property.show'), [
                'real_estate_details' => RealEstateDetailResource::make($this->whenLoaded('realEstateDetail')),
            ])

        ];
    }
}
