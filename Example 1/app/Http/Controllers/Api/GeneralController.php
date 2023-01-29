<?php

namespace App\Http\Controllers\Api;

use App\Enums\{Status, PropertyType};
use App\Helpers\Setting\Utility;
use App\Http\Controllers\Controller;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Category\SubcategoryResource;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Company\{CompanyResource, CompanyDetailResource};
use App\Http\Resources\Country\CountryResource;
use App\Http\Resources\Currency\CurrencyResource;
use App\Http\Resources\Feature\FeatureResource;
use App\Http\Resources\Governorate\GovernorateResource;
use App\Http\Resources\Language\LanguageResource;
use App\Http\Resources\Project\ProjectResource;
use App\Http\Resources\Property\PropertyResource;
use App\Http\Resources\Question\QuestionResource;
use App\Http\Resources\RealEstate\{RealEstateResource, RealEstateDetailResource};
use App\Http\Resources\Region\RegionResource;
use App\Http\Resources\Service\ServiceResource;
use App\Http\Resources\SocialMedia\SocialMediaResource;
use App\Http\Resources\Special\SpecialResource;
use App\Models\{Category, Comment, Company, Country, Governorate, Region, Currency, Feature};
use App\Models\{Project, Property, Question, RealEstate, Service, SocialMedia, Special};
use App\Traits\Api\ApiResponses;
use Illuminate\Support\Fluent;

class GeneralController extends Controller
{
    use ApiResponses;

    public function languages()
    {
        return LanguageResource::collection(languages());
    }

    public function countries()
    {
        $countries = Country::query()
            ->with(['currency'])
            ->orderByDesc('created_at')
            ->status()
            ->get();
        return $this->success(CountryResource::collection($countries));
    }

    public function governorates($country)
    {
        $governorate = Governorate::query()
            ->orderByDesc('created_at')
            ->whereCountryId($country)
            ->status()
            ->get();
        return $this->success(GovernorateResource::collection($governorate));
    }

    public function regions($country, $governorate)
    {
        $regions = Region::query()
            ->where([
                'country_id' => $country,
                'governorate_id' => $governorate,
                'status' => Status::Active->value,
            ])
            ->orderByDesc('created_at')
            ->get();
        return $this->success(RegionResource::collection($regions));
    }

    public function socialMedia()
    {
        $socialMedia = SocialMedia::query()->status()->get();
        return $this->success(SocialMediaResource::collection($socialMedia));
    }

    public function setting()
    {
        $lang = request()->lang ?? locale();
        $setting = Utility::settings();
        $fluent = new Fluent($setting);
        $fluent->icon = getAvatar($fluent->icon);
        $fluent->web_logo = getAvatar($fluent->web_logo);
        $fluent->mobile_logo = getAvatar($fluent->mobile_logo);
        $fluent->favicon = getAvatar($fluent->favicon);
        $fluent->qr_link_google_play = getAvatar('qrCode/link_google_play.png');
        $fluent->qr_link_apple_store = getAvatar('qrCode/link_apple_store.png');
        $fluent->qr_link_website = getAvatar('qrCode/qr_link_website.png');
        $fluent->company_name = $fluent['company_name_' . $lang];
        $fluent->meta_keywords = $fluent['meta_keywords_' . $lang];
        $fluent->meta_description = $fluent['meta_description_' . $lang];
        $fluent->author = $fluent['author_' . $lang];
        foreach (locales() as $locale) {
            unset(
                $fluent['company_name_' . $locale],
                $fluent['meta_keywords_' . $locale],
                $fluent['meta_description_' . $locale],
                $fluent['author_' . $locale]
            );
        }
        unset($fluent['google_maps_api'], $fluent['FCM_SERVER_KEY'], $fluent['FCM_SERVER_URL']);
        return $this->success($fluent, message: _trans('Setting site list'));
    }

    public function categories()
    {
        $categories = Category::query()
            /*->with([
                'children' => fn($builder) => $builder->orderByDesc('ranking')->status()
            ])*/
            ->whereNull('parent_id')
            ->orderByDesc('ranking')
            ->status()
            ->get();
        return $this->success(CategoryResource::collection($categories));
    }

    public function subCategories($categoryId)
    {
        $categories = Category::query()
            ->with(['parent'])
            ->where('parent_id', '=', $categoryId)
            ->orderByDesc('ranking')
            ->status()
            ->get();
        return $this->success(SubcategoryResource::collection($categories));
    }

    public function questions()
    {
        $questions = Question::query()
            ->with(['user', 'country', 'governorate', 'region'])
            ->withCount(['answers'])
            ->orderByDesc('answers_count')
            ->paginate(Utility::getValByName('pagination_limit'))
            ->withQueryString();
        return $this->success(QuestionResource::collection($questions), $questions);
    }

    public function services()
    {
        $services = Service::query()
            ->orderByDesc('ranking')
            ->status()
            ->get();
        return $this->success(ServiceResource::collection($services));
    }

    public function features()
    {
        $features = Feature::query()
            ->orderByDesc('ranking')
            ->status()
            ->get();
        return $this->success(FeatureResource::collection($features));
    }

    public function currencies()
    {
        $currencies = Currency::query()
            ->orderByDesc('created_at')
            ->status()
            ->get();
        return $this->success(CurrencyResource::collection($currencies));
    }

    public function specials()
    {
        $specials = Special::query()
            ->with([
                'realEstates' => fn($builder) => $builder->with(['category', 'subCategory', 'currency', 'modelable'])->limit(10)->publish(Status::Active)
            ])
            ->orderByDesc('ranking')
            ->limit(4)
            ->status()
            ->get();
        return $this->success(SpecialResource::collection($specials));
    }

    public function realEstates()
    {
        $realEstates = RealEstate::query()->with(['modelable', 'special', 'currency']);
        $realEstates = RealEstate::filters($realEstates);
        $realEstates = $realEstates->orderByDesc('created_at')
            //->where('country_id', '=', request()->country_id)
            ->publish()
            ->paginate(Utility::getValByName('pagination_limit'))
            ->withQueryString();
        /*$realEstates->each(function ($realEstate) {
            if ($realEstate->modelable_type == Property::class) {
                $realEstate->modelable->load(['user' => ['country', 'governorate', 'region'], 'images', 'project']);
            } else {
                $realEstate->modelable->load(['service', 'images']);
            }
        });*/

        return $this->success(RealEstateResource::collection($realEstates), $realEstates);
    }

    public function realEstate($slug)
    {
        $realEstates = RealEstate::query()
            ->with([
                'modelable.user' => ['country', 'governorate', 'region'],
                'features',
                'special',
                'country',
                'governorate',
                'region',
                'category',
                'subCategory',
                'currency',
                'comments.user'
            ])
            ->withCount(['likes', 'comments', 'shares'])
            ->when(isNumbers($slug), function ($query) use ($slug) {
                $query->where('id', '=', $slug);
            })
            ->when(!isNumbers($slug), function ($query) use ($slug) {
                $query->whereTranslation('slug', $slug);
            })
            ->publish()
            //->where('country_id', '=', request()->country_id)
            ->first();
        return $this->success($realEstates ? RealEstateDetailResource::make($realEstates) : null);
    }

    public function realEstatesByUser($user)
    {
        $realEstates = RealEstate::query()
            ->with(['modelable', 'special', 'currency'])
            ->orderByDesc('created_at')
            ->publish()
            ->whereUserId($user)
            //->where('country_id', '=', request()->country_id)
            ->paginate(Utility::getValByName('pagination_limit'))
            ->withQueryString();
        /*$realEstates->each(function ($realEstate) {
            if ($realEstate->modelable_type == Property::class) {
                $realEstate->modelable->load(['user' => ['country', 'governorate', 'region'], 'images', 'project']);
            } else {
                $realEstate->modelable->load(['service', 'images']);
            }
        });*/

        return $this->success(RealEstateResource::collection($realEstates), $realEstates);
    }

    public function properties()
    {
        $properties = Property::query()
            ->whereRelation('realEstate', 'publish', '=', Status::Active)
            ->with(['realEstate' => ['special', 'category', 'subCategory', 'currency']]);
        //->whereRelation('realEstate', 'country_id', '=', request()->country_id)
        $properties = Property::filters($properties)
            ->paginate(Utility::getValByName('pagination_limit'))
            ->withQueryString();
        return $this->success(PropertyResource::collection($properties), $properties);
    }

    public function property($id)
    {
        $property = Property::query()
            ->whereRelation('realEstate', 'publish', '=', Status::Active)
            ->with(['realEstate' => ['special', 'category', 'subCategory', 'currency']])
            ->find($id);
        //->whereRelation('realEstate', 'country_id', '=', request()->country_id)
        return $this->success($property ? PropertyResource::make($property) : null);
    }

    public function projects()
    {
        $projects = Project::query()
            ->whereRelation('realEstate', 'publish', '=', Status::Active)
            ->with(['realEstate' => ['special', 'category', 'subCategory', 'currency']])
            //->whereRelation('realEstate', 'country_id', '=', request()->country_id)
            ->paginate(Utility::getValByName('pagination_limit'))
            ->withQueryString();
        return $this->success(ProjectResource::collection($projects), $projects);
    }

    public function project($id)
    {
        $project = Project::query()
            ->whereRelation('realEstate', 'publish', '=', Status::Active)
            ->with(['realEstate' => ['special', 'category', 'subCategory', 'currency']])
            //->whereRelation('realEstate', 'country_id', '=', request()->country_id)
            ->find($id);
        return $this->success($project ? ProjectResource::make($project) : null);
    }

    public function comments($real_estate_id)
    {
        $user = auth('sanctum')->user();
        $comments = Comment::mainComment()
            ->with(['user'])
            ->with(['childrenComments' => ['user', 'children']])
            ->where('real_estate_id', '=', $real_estate_id)
            ->paginate(Utility::getValByName('pagination_limit'))
            ->withQueryString();

        return $this->success(CommentResource::collection($comments), $comments);
    }

    public function companies()
    {
        $companies = Company::query()->withCount(['projects']);

        $companies = Company::filters($companies)
            ->paginate(Utility::getValByName('pagination_limit'))
            ->withQueryString();
        $companies->each(function ($company) {
            $projectIds = $company->projects()->select('id')->pluck('id')->toArray();
            $company->rent_count = Property::query()
                ->where('type', '=', PropertyType::Rent->value)
                ->whereIn('project_id', $projectIds)
                ->count();
            $company->sale_count = Property::query()
                ->where('type', '=', PropertyType::Sale->value)
                ->whereIn('project_id', $projectIds)
                ->count();
        });
        return $this->success(CompanyResource::collection($companies), $companies);
    }

    public function company($slug)
    {
        $company = Company::query()
            ->with(['country', 'governorate', 'region', 'category', 'subCategory'])
            ->when(isNumbers($slug), function ($query) use ($slug) {
                $query->where('id', '=', $slug);
            })
            ->when(!isNumbers($slug), function ($query) use ($slug) {
                $query->whereTranslation('slug', $slug);
            })
            ->first();

        $projects = Project::query()
            ->where('company_id', '=', $company?->id)
            ->whereRelation('realEstate', 'publish', '=', Status::Active)
            ->with(['realEstate' => ['special', 'category', 'subCategory', 'currency']])
            ->paginate(Utility::getValByName('pagination_limit'))
            ->withQueryString();
        return $this->success([
            'company' => $company ? CompanyDetailResource::make($company) : null,
            'projects' => ProjectResource::collection($projects),
            'projects_paginate' => $this->paginate($projects),
        ]);
    }

}
