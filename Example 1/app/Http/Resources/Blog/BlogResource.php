<?php

namespace App\Http\Resources\Blog;

use App\Models\BlogCategory;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class BlogResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'blog_id' => $this->id,
            'title' => $this->translate(locale())?->title,
            'slug' => $this->translate(locale())?->slug,
            'category' => BlogCategoryResource::make($this->whenLoaded('category')),
            'image' => getAvatar($this->default_image),
            $this->mergeWhen(!request()->routeIs('api.blog-details'), [
                'content' => Str::limit(removeHtmlTags($this->translate(locale())?->content)),
            ]),
            $this->mergeWhen(request()->routeIs('api.blog-details'), [
                'content' => $this->translate(locale())?->content,
            ]),
            'date' => $this->created_at->format('Y-m-d'),
        ];
    }
}
