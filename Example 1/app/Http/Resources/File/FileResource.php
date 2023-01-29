<?php

namespace App\Http\Resources\File;

use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'path_id' => $this->id,
            'path' => getAvatar($this->full_file),
        ];
    }
}
