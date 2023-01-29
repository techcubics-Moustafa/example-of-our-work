<?php

namespace App\Http\Resources\RealEstate;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Category\SubcategoryResource;
use App\Http\Resources\Country\CountryResource;
use App\Http\Resources\Currency\CurrencyResource;
use App\Http\Resources\Feature\FeatureResource;
use App\Http\Resources\Governorate\GovernorateResource;
use App\Http\Resources\Project\ProjectResource;
use App\Http\Resources\Property\PropertyResource;
use App\Http\Resources\Region\RegionResource;
use App\Http\Resources\Special\SpecialResource;
use App\Http\Resources\User\UserResource;
use App\Models\Project;
use App\Models\Property;
use Illuminate\Http\Resources\Json\JsonResource;

class RealEstateEditResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'real_estate_id' => $this->id,
            'translations' => $this->translations,
            'real_estate_type' => $this->modelable_type == Project::class ? 'project' : 'property',
            'special' => SpecialResource::make($this->whenLoaded('special')),
            'country' => CountryResource::make($this->whenLoaded('country')),
            'governorate' => GovernorateResource::make($this->whenLoaded('governorate')),
            'region' => RegionResource::make($this->whenLoaded('region')),
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'sub_category' => SubcategoryResource::make($this->whenLoaded('subCategory')),
            'currency' => CurrencyResource::make($this->whenLoaded('currency')),
            'features' => FeatureResource::collection($this->whenLoaded('features')),
            'location' => $this->location,
            'youtube_video_thumbnail' => getAvatar($this->youtube_video_thumbnail),
            'youtube_video_url' => $this->youtube_video_url,
            'image' => getAvatar($this->image),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }
}
