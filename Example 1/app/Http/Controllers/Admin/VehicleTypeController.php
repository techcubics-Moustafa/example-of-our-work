<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CPU\Models;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VehicleTypeRequest;
use App\Models\VehicleType;
use App\Traits\Api\ApiResponses;
use App\Traits\Helper\UploadFileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleTypeController extends Controller
{
    use ApiResponses, UploadFileTrait;

    public function __construct()
    {
        $this->middleware(['permission:Vehicle#Type list'])->only(['index']);
        $this->middleware(['permission:Vehicle#Type add'])->only(['create']);
        $this->middleware(['permission:Vehicle#Type edit'])->only(['edit']);
        $this->middleware(['permission:Vehicle#Type delete'])->only(['destroy']);
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $vehicleTypes = VehicleType::query()
            ->with(['unit'])
            ->withCount(['distances']);
        $vehicleTypes = VehicleType::allVehicleTypes($vehicleTypes);
        $columns = [
            'all' => _trans('All'),
            'name' => _trans('Vehicle type name'),
            'unit' => _trans('Unit name'),
        ];
        return view('admin.vehicle-type.index', compact('vehicleTypes', 'columns'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = false;
        $units = Models::units();
        return view('admin.vehicle-type.form', compact('edit', 'units'));
    }

    public function store(VehicleTypeRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        try {
            DB::beginTransaction();
            $vehicleType = VehicleType::query()->create($data);
            $vehicleType->distances()->createMany($request->distances);
            DB::commit();
            return redirect()->route('admin.vehicle-type.index')->with('success', _trans('Done Save Data Successfully'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())->withInput($request->all());
        }

    }

    public function show(VehicleType $vehicleType)
    {
        //
    }

    public function edit(VehicleType $vehicleType): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = true;
        $units = Models::units();
        $vehicleType->load('distances');
        return view('admin.vehicle-type.form', compact('edit', 'vehicleType', 'units'));
    }

    public function update(VehicleTypeRequest $request, VehicleType $vehicleType): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        try {
            DB::beginTransaction();
            $vehicleType->update($data);
            $vehicleType->distances()->delete();
            $vehicleType->distances()->createMany($request->distances);
            DB::commit();
            return redirect()->route('admin.vehicle-type.index')->with('success', _trans('Done Updated Data Successfully'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())->withInput($request->all());
        }

    }


    public function destroy(VehicleType $vehicleType)
    {

    }

    public function updateStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $vehicleType = VehicleType::query()->findOrFail($request->id);
        if (!$vehicleType) {
            return $this->success(_trans('Not Found'), status: false);
        }
        $status = !$vehicleType->status;
        $vehicleType->update(['status' => $status]);
        return $this->success(_trans('Done Updated Data Successfully'));
    }
}
