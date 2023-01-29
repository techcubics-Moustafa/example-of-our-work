<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CPU\Models;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VehicleModelRequest;
use App\Models\VehicleModel;
use App\Traits\Api\ApiResponses;
use App\Traits\Helper\UploadFileTrait;
use Illuminate\Http\Request;

class VehicleModelController extends Controller
{
    use ApiResponses, UploadFileTrait;

    public function __construct()
    {
        $this->middleware(['permission:Vehicle#Model list'])->only(['index']);
        $this->middleware(['permission:Vehicle#Model add'])->only(['create']);
        $this->middleware(['permission:Vehicle#Model edit'])->only(['edit']);
        $this->middleware(['permission:Vehicle#Model delete'])->only(['destroy']);
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $vehicleModels = VehicleModel::query()->with(['logo', 'manufacture']);
        $vehicleModels = VehicleModel::allVehicleModels($vehicleModels);
        $columns = [
            'all' => _trans('All'),
            'code' => _trans('Vehicle model code'),
            'name' => _trans('Vehicle model name'),
            'manufacture' => _trans('Manufacture name'),
        ];
        return view('admin.vehicle-model.index', compact('vehicleModels', 'columns'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = false;
        $manufactures = Models::manufactures();
        return view('admin.vehicle-model.form', compact('edit', 'manufactures'));
    }

    public function store(VehicleModelRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $vehicleModel = VehicleModel::query()->create($data);
        if ($request->hasFile('logo')) {
            $this->storeFile($request->logo, [
                'path' => 'vehicle-model',
                'relationable_id' => $vehicleModel->id,
                'relationable_type' => VehicleModel::class,
                'column_name' => 'logo',
            ]);
        }
        return redirect()->route('admin.vehicle-model.index')->with('success', _trans('Done Save Data Successfully'));
    }

    public function show(VehicleModel $vehicleModel)
    {
        //
    }

    public function edit(VehicleModel $vehicleModel): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = true;
        $vehicleModel->fresh(['logo']);
        $manufactures = Models::manufactures();
        return view('admin.vehicle-model.form', compact('edit', 'vehicleModel', 'manufactures'));
    }

    public function update(VehicleModelRequest $request, VehicleModel $vehicleModel): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $vehicleModel->update($data);
        $vehicleModel->fresh();
        if ($request->hasFile('logo')) {
            $this->deleteFile($vehicleModel->logo?->full_file);
            $this->storeFile($request->logo, [
                'path' => 'vehicle-model',
                'relationable_id' => $vehicleModel->id,
                'relationable_type' => VehicleModel::class,
                'column_name' => 'logo',
            ]);
        }
        return redirect()->route('admin.vehicle-model.index')->with('success', _trans('Done Updated Data Successfully'));
    }


    public function destroy(VehicleModel $vehicleModel)
    {

    }

    public function updateStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $vehicleModel = VehicleModel::query()->findOrFail($request->id);
        if (!$vehicleModel) {
            return $this->success(_trans('Not Found'), status: false);
        }
        $status = !$vehicleModel->status;
        $vehicleModel->update(['status' => $status]);
        return $this->success(_trans('Done Updated Data Successfully'));
    }
}
