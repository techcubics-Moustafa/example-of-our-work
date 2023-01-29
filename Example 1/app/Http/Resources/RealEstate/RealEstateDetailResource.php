<?php

namespace App\Http\Resources\RealEstate;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Category\SubcategoryResource;
use App\Http\Resources\Comment\CommentResource;
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

class RealEstateDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'real_estate_id' => $this->id,
            'title' => $this->translateOrDefault(locale())?->title,
            'slug' => $this->translateOrDefault(locale())?->slug,
            'description' => $this->translateOrDefault(locale())?->description,
            'content' => $this->translateOrDefault(locale())?->content,
            'address' => $this->translateOrDefault(locale())?->address,
            'seo_title' => $this->translateOrDefault(locale())?->seo_title,
            'seo_description' => $this->translateOrDefault(locale())?->seo_description,
            'real_estate_type' => $this->modelable_type == Project::class ? 'project' : 'property',
            $this->mergeWhen($this->modelable_type == Project::class, [
                'project' => ProjectResource::make($this->modelable)
            ]),
            $this->mergeWhen($this->modelable_type == Property::class, [
                'property' => PropertyResource::make($this->modelable)
            ]),
            'user' => UserResource::make($this->whenLoaded('user')),
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
            'like' => $this->likeBy(auth('sanctum')->user()),
            'likes_count' => $this->whenCounted('likes'),
            'comments_count' => $this->whenCounted('comments'),
            'shares_count' => $this->whenCounted('shares'),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
