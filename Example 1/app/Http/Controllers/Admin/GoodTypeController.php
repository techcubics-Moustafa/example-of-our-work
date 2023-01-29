<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GoodTypeRequest;
use App\Models\GoodType;
use App\Traits\Api\ApiResponses;
use Illuminate\Http\Request;

class GoodTypeController extends Controller
{
    use ApiResponses;

    public function __construct()
    {
        $this->middleware(['permission:Good#Type list'])->only(['index']);
        $this->middleware(['permission:Good#Type add'])->only(['create']);
        $this->middleware(['permission:Good#Type edit'])->only(['edit']);
        $this->middleware(['permission:Good#Type delete'])->only(['destroy']);
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $goodTypes = GoodType::query();
        $goodTypes = GoodType::allGoodTypes($goodTypes);
        $columns = [
            'all' => _trans('All'),
            'name' => _trans('Good type name'),
        ];
        return view('admin.good-type.index', compact('goodTypes', 'columns'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = false;
        return view('admin.good-type.form', compact('edit'));
    }

    public function store(GoodTypeRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        GoodType::query()->create($data);
        return redirect()->route('admin.good-type.index')->with('success', _trans('Done Save Data Successfully'));
    }

    public function show(GoodType $goodType)
    {
        //
    }

    public function edit(GoodType $goodType): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = true;
        return view('admin.good-type.form', compact('edit', 'goodType'));
    }

    public function update(GoodTypeRequest $request, GoodType $goodType): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $goodType->update($data);
        return redirect()->route('admin.good-type.index')->with('success', _trans('Done Updated Data Successfully'));
    }


    public function destroy(GoodType $goodType)
    {

    }

    public function updateStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $goodType = GoodType::query()->findOrFail($request->id);
        if (!$goodType) {
            return $this->success(_trans('Not Found'), status: false);
        }
        $status = !$goodType->status;
        $goodType->update(['status' => $status]);
        return $this->success(_trans('Done Updated Data Successfully'));
    }
}
