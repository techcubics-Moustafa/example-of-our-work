<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class SubcategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'sub_category_id' => $this->id,
            'ranking' => $this->ranking,
            'image' => getAvatar($this->image),
            'name' => $this->translateOrDefault(locale())?->name,
            'slug' => $this->translateOrDefault(locale())?->slug,
            'category' => CategoryResource::make($this->whenLoaded('parent'))
        ];
    }
}
