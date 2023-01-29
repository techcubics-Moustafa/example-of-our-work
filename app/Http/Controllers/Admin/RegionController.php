<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RegionRequest;
use App\Models\Country;
use App\Models\Governorate;
use App\Models\Region;
use App\Traits\Api\ApiResponses;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    use ApiResponses;

    public function __construct()
    {
        $this->middleware(['permission:Region list,admin'])->only(['index']);
        $this->middleware(['permission:Region add,admin'])->only(['create']);
        $this->middleware(['permission:Region edit,admin'])->only(['edit']);
        $this->middleware(['permission:Region delete,admin'])->only(['destroy']);
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $regions = Region::query()->with(['country', 'governorate']);
        $regions = Region::allRegions($regions);
        $columns = [
            'all' => _trans('All'),
            'code' => _trans('Region Code'),
            'country' => _trans('Country Name'),
            'governorate' => _trans('Governorate Name'),
            'name' => _trans('Region name'),
        ];
        return view('admin.region.index', compact('regions', 'columns'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = false;
        $countries = Country::query()->status()->get();
        return view('admin.region.form', compact('edit', 'countries'));
    }

    public function store(RegionRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        Region::query()->create($data);
        return redirect()->route('admin.region.index')->with('success', _trans('Done Save Data Successfully'));
    }

    public function show(Region $region)
    {
        //
    }

    public function edit(Region $region): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = true;
        $countries = Country::query()->status()->get();
        return view('admin.region.form', compact('edit', 'region', 'countries'));
    }

    public function update(RegionRequest $request, Region $region): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $region->update($data);
        return redirect()->route('admin.region.index')->with('success', _trans('Done Updated Data Successfully'));
    }

    public function destroy(Region $region)
    {
        //
    }

    public function updateStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $region = Region::query()->findOrFail($request->id);
        if (!$region) {
            return $this->success(_trans('Not Found'), status: false);
        }
        $status = !$region->status;
        $region->update(['status' => $status]);
        return $this->success(_trans('Done Updated Data Successfully'));
    }
}
