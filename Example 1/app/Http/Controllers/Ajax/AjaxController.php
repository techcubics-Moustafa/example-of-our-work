<?php

namespace App\Http\Controllers\Ajax;

use App\Helpers\CPU\Models;
use App\Http\Controllers\Controller;
use App\Http\Resources\Company\CompanyResource;
use App\Http\Resources\Governorate\GovernorateResource;
use App\Http\Resources\Category\SubcategoryResource;
use App\Http\Resources\Region\RegionResource;
use App\Traits\Api\ApiResponses;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AjaxController extends Controller
{
    use ApiResponses;

    public function governorates(Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $governorates = Models::governorates($request->country_id);
        return $this->success(GovernorateResource::collection($governorates));
    }

    public function regions(Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $governorates = Models::regions($request->governorate_id);
        return $this->success(RegionResource::collection($governorates));
    }

    public function roles(Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $roles = Role::query()
            ->select(['id', 'name', 'created_by'])
            ->where([
                'created_by' => $request->owner_id,
                'guard_name' => 'web',
            ])
            ->whereNotIn('name', ['super_admin', 'owner'])
            ->orderByDesc('created_at')
            ->get();
        return $this->success($roles);
    }

    public function subCategories(Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $subCategories = Models::subCategories($request->category_id);
        return $this->success(SubcategoryResource::collection($subCategories));
    }

    public function companies(Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $companies = Models::companies($request->user_id);
        return $this->success(CompanyResource::collection($companies));
    }
}
