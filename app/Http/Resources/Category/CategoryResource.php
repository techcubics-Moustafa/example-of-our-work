<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->id,
            //'ranking' => $this->ranking,
            'slug' => $this->translateOrDefault(locale())?->slug,
            'name' => $this->translateOrDefault(locale())?->name,
            'image' => getAvatar($this->image),
            'children' => SubcategoryResource::collection($this->whenLoaded('children')),
        ];
    }
}
