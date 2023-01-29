<?php

namespace App\Http\Resources\Language;

use Illuminate\Http\Resources\Json\JsonResource;

class LanguageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'code' => $this->code,
            'flag' => asset('flags/'.pathinfo($this->flag)['filename'].'.png')
        ];
    }
}
