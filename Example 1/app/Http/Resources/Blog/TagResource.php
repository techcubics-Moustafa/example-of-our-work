<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'tag_id' => $this->id,
            'name' => $this->translate(locale())?->name,
        ];
    }
}
