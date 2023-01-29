<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GovernorateRequest;
use App\Models\Country;
use App\Models\Governorate;
use App\Models\GovernorateTranslation;
use App\Traits\Api\ApiResponses;
use Illuminate\Http\Request;

class GovernorateController extends Controller
{
    use ApiResponses;

    public function __construct()
    {
        $this->middleware(['permission:Governorate list,admin'])->only(['index']);
        $this->middleware(['permission:Governorate add,admin'])->only(['create']);
        $this->middleware(['permission:Governorate edit,admin'])->only(['edit']);
        $this->middleware(['permission:Governorate delete,admin'])->only(['destroy']);
    }


    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $governorates = Governorate::query()->with(['country']);
        $governorates = Governorate::allGovernorates($governorates);
        $columns = [
            'all' => _trans('All'),
            'code' => _trans('Governorate Code'),
            'country' => _trans('Country name'),
            'name' => _trans('Governorate name'),
        ];
        return view('admin.governorate.index', compact('governorates', 'columns'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = false;
        $countries = Country::query()->get();
        return view('admin.governorate.form', compact('edit', 'countries'));
    }

    public function store(GovernorateRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        Governorate::query()->create($data);
        return redirect()->route('admin.governorate.index')->with('success', _trans('Done Save Data Successfully'));
    }

    public function show(Governorate $governorate)
    {
        //
    }

    public function edit(Governorate $governorate): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = true;
        $countries = Country::query()->get();
        return view('admin.governorate.form', compact('edit', 'governorate', 'countries'));
    }

    public function update(GovernorateRequest $request, Governorate $governorate): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $governorate->update($data);
        return redirect()->route('admin.governorate.index')->with('success', _trans('Done Updated Data Successfully'));
    }


    public function destroy(Governorate $governorate)
    {
        //
    }

    public function updateStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $governorate = Governorate::query()->findOrFail($request->id);
        if (!$governorate) {
            return $this->success(_trans('Not Found'), status: false);
        }
        $status = !$governorate->status;
        $governorate->update(['status' => $status]);
        return $this->success(_trans('Done Updated Data Successfully'));
    }
}
