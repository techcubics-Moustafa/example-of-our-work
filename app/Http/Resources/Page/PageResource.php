<?php

namespace App\Http\Resources\Page;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            //'id' => $this->id,
            'page_type' => _trans($this->page_type),
            'title' => $this->translate(locale())?->title,
            'description' => $this->translate(locale())?->description,
            'image' => getAvatar($this->image),
        ];
    }
}
