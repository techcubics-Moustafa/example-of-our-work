<?php

namespace App\Http\Resources\Blog;

use App\Http\Resources\File\FileResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class BlogDetailedResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'blog_id' => $this->id,
            'title' => $this->translate(locale())?->title,
            'content' => $this->translate(locale())?->content,
            'email' => $this->email,
            'date' => $this->created_at->format('Y-m-d'),
            'category' => BlogCategoryResource::make($this->whenLoaded('category')),
            'image' => getAvatar($this->default_image),
            'images' => FileResource::collection($this->whenLoaded('images')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
        ];
    }
}
