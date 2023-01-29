<?php

namespace App\Http\Resources\Project;

use App\Http\Resources\File\FileResource;
use App\Http\Resources\RealEstate\RealEstateDetailResource;
use App\Http\Resources\RealEstate\RealEstateEditResource;
use App\Http\Resources\RealEstate\RealEstateResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->id,
            'status' => $this->status,
            'number_blocks' => $this->number_blocks,
            'number_floors' => $this->number_floors,
            'number_flats' => $this->number_flats,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'open_sell_date' => $this->open_sell_date,
            'finish_date' => $this->finish_date,
            'images' => FileResource::collection($this->whenLoaded('images')),
            'real_estate' => RealEstateResource::make($this->whenLoaded('realEstate')),
            $this->mergeWhen(request()->routeIs('api.project.show'), [
                'real_estate_details' => RealEstateEditResource::make($this->whenLoaded('realEstateDetail')),
            ]),
            $this->mergeWhen(!request()->routeIs('api.project.show'), [
                'real_estate_details' => RealEstateDetailResource::make($this->whenLoaded('realEstateDetail')),
            ])
        ];
    }
}
