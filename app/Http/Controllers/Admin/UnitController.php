<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UnitRequest;
use App\Models\Unit;
use App\Traits\Api\ApiResponses;
use App\Traits\Helper\UploadFileTrait;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    use ApiResponses, UploadFileTrait;

    public function __construct()
    {
        $this->middleware(['permission:Unit list'])->only(['index']);
        $this->middleware(['permission:Unit add'])->only(['create']);
        $this->middleware(['permission:Unit edit'])->only(['edit']);
        $this->middleware(['permission:Unit delete'])->only(['destroy']);
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $units = Unit::query();
        $units = Unit::allUnits($units);
        $columns = [
            'all' => _trans('All'),
            'code' => _trans('Unit code'),
            'name' => _trans('Unit name'),
        ];
        return view('admin.unit.index', compact('units', 'columns'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = false;
        return view('admin.unit.form', compact('edit'));
    }

    public function store(UnitRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        Unit::query()->create($data);
        return redirect()->route('admin.unit.index')->with('success', _trans('Done Save Data Successfully'));
    }

    public function show(Unit $unit)
    {
        //
    }

    public function edit(Unit $unit): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = true;
        return view('admin.unit.form', compact('edit', 'unit'));
    }

    public function update(UnitRequest $request, Unit $unit): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $unit->update($data);
        return redirect()->route('admin.unit.index')->with('success', _trans('Done Updated Data Successfully'));
    }


    public function destroy(Unit $unit)
    {

    }

    public function updateStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $unit = Unit::query()->findOrFail($request->id);
        if (!$unit) {
            return $this->success(_trans('Not Found'), status: false);
        }
        $status = !$unit->status;
        $unit->update(['status' => $status]);
        return $this->success(_trans('Done Updated Data Successfully'));
    }
}
