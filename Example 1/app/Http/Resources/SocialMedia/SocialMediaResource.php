<?php

namespace App\Http\Resources\SocialMedia;

use Illuminate\Http\Resources\Json\JsonResource;

class SocialMediaResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'name' => $this->slug,
            'url' => $this->url,
            'icon' => getAvatar($this->icon),
        ];
    }
}
