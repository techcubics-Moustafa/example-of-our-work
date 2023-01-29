<?php

namespace App\Http\Controllers\Api;

use App\Enums\Status;
use App\Helpers\Setting\Utility;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ServiceRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Category\SubcategoryResource;
use App\Http\Resources\Country\CountryResource;
use App\Http\Resources\Governorate\GovernorateResource;
use App\Http\Resources\Language\LanguageResource;
use App\Http\Resources\Region\RegionResource;
use App\Http\Resources\SocialMedia\SocialMediaResource;
use App\Http\Resources\Special\SpecialResource;
use App\Models\Category;
use App\Models\Country;
use App\Models\Governorate;
use App\Models\Region;
use App\Models\Service;
use App\Models\SocialMedia;
use App\Models\Special;
use App\Traits\Api\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Fluent;
use Propaganistas\LaravelPhone\PhoneNumber;

class ServiceController extends Controller
{
    use ApiResponses;

    public function __invoke(ServiceRequest $request)
    {
        $data = $request->validated();
        $data['phone'] = (string)PhoneNumber::make($request->phone, $request->country_code);
        $data['user_id'] = auth('sanctum')->id();
        Service::query()->create($data);
        return $this->success(_trans('Done send service successfully'));
    }

}
