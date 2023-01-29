<?php

namespace App\Http\Resources\RealEstate;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Currency\CurrencyResource;
use App\Http\Resources\Project\ProjectResource;
use App\Http\Resources\Property\PropertyResource;
use App\Http\Resources\Special\SpecialResource;
use App\Http\Resources\User\UserResource;
use App\Models\Project;
use App\Models\Property;
use Illuminate\Http\Resources\Json\JsonResource;

class RealEstateResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'real_estate_id' => $this->id,
            'title' => $this->translateOrDefault(locale())?->title,
            'slug' => $this->translateOrDefault(locale())?->slug,
            'description' => $this->translateOrDefault(locale())?->description,
            'real_estate_type' => $this->modelable_type == Project::class ? 'project' : 'property',
            $this->mergeWhen($this->modelable_type == Project::class, [
                'project' => ProjectResource::make($this->whenLoaded('modelable'))
            ]),
            $this->mergeWhen($this->modelable_type == Property::class, [
                'property' => PropertyResource::make($this->whenLoaded('modelable'))
            ]),
            'user' => UserResource::make($this->whenLoaded('user')),
            'special' => SpecialResource::make($this->whenLoaded('special')),
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'sub_category' => CategoryResource::make($this->whenLoaded('subCategory')),
            'currency' => CurrencyResource::make($this->whenLoaded('currency')),
            'image' => getAvatar($this->image),
        ];
    }
}
