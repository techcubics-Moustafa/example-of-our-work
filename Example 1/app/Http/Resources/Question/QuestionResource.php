<?php

namespace App\Http\Resources\Question;

use App\Http\Resources\Country\CountryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'question_id' => $this->id,
            'question' => $this->question,
            'answers_count' => $this->whenCounted('answers'),
            'user' => CountryResource::make($this->whenLoaded('user')),
            'country' => CountryResource::make($this->whenLoaded('country')),
            'governorate' => CountryResource::make($this->whenLoaded('governorate')),
            'region' => CountryResource::make($this->whenLoaded('region')),
        ];
    }
}
