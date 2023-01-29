<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogCategoryResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->id,
            'name' => $this->translate(locale())?->name,
            'slug' => $this->translate(locale())?->slug,
            'image' => getAvatar($this->image),
            'count' => $this->blogs_count,
        ];
    }
}
