<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VehicleMakeRequest;
use App\Models\VehicleMake;
use App\Traits\Api\ApiResponses;
use App\Traits\Helper\UploadFileTrait;
use Illuminate\Http\Request;

class VehicleMakeController extends Controller
{
    use ApiResponses, UploadFileTrait;

    public function __construct()
    {
        $this->middleware(['permission:Vehicle#Make list'])->only(['index']);
        $this->middleware(['permission:Vehicle#Make add'])->only(['create']);
        $this->middleware(['permission:Vehicle#Make edit'])->only(['edit']);
        $this->middleware(['permission:Vehicle#Make delete'])->only(['destroy']);
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $vehicleMakes = VehicleMake::query()->with(['logo']);
        $vehicleMakes = VehicleMake::allVehicleMakes($vehicleMakes);
        $columns = [
            'all' => _trans('All'),
            'code' => _trans('Vehicle make code'),
            'name' => _trans('Vehicle make name'),
        ];
        return view('admin.vehicle-make.index', compact('vehicleMakes', 'columns'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = false;
        return view('admin.vehicle-make.form', compact('edit'));
    }

    public function store(VehicleMakeRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $vehicleMake = VehicleMake::query()->create($data);
        if ($request->hasFile('logo')) {
            $this->storeFile($request->logo, [
                'path' => 'vehicle-make',
                'relationable_id' => $vehicleMake->id,
                'relationable_type' => VehicleMake::class,
                'column_name' => 'logo',
            ]);
        }
        return redirect()->route('admin.vehicle-make.index')->with('success', _trans('Done Save Data Successfully'));
    }

    public function show(VehicleMake $vehicleMake)
    {
        //
    }

    public function edit(VehicleMake $vehicleMake): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = true;
        $vehicleMake->fresh(['logo']);
        return view('admin.vehicle-make.form', compact('edit', 'vehicleMake'));
    }

    public function update(VehicleMakeRequest $request, VehicleMake $vehicleMake): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $vehicleMake->update($data);
        $vehicleMake->fresh();
        if ($request->hasFile('logo')) {
            $this->deleteFile($vehicleMake->logo?->full_file);
            $this->storeFile($request->logo, [
                'path' => 'vehicle-make',
                'relationable_id' => $vehicleMake->id,
                'relationable_type' => VehicleMake::class,
                'column_name' => 'logo',
            ]);
        }
        return redirect()->route('admin.vehicle-make.index')->with('success', _trans('Done Updated Data Successfully'));
    }


    public function destroy(VehicleMake $vehicleMake)
    {

    }

    public function updateStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $vehicleMake = VehicleMake::query()->findOrFail($request->id);
        if (!$vehicleMake) {
            return $this->success(_trans('Not Found'), status: false);
        }
        $status = !$vehicleMake->status;
        $vehicleMake->update(['status' => $status]);
        return $this->success(_trans('Done Updated Data Successfully'));
    }
}
