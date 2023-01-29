<?php

namespace App\Http\Resources\Company;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->id,
            'slug' => $this->translateOrDefault(locale())?->slug,
            'name' => $this->translateOrDefault(locale())?->name,
            'logo' => getAvatar($this->logo),
            'rent_count' => $this->whenCounted('rent_count'),
            'sale_count' => $this->whenCounted('sale_count'),
            'project_count' => $this->whenCounted('projects'),
        ];
    }
}
