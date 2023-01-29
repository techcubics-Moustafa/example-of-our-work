<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FeatureRequest;
use App\Models\Feature;
use App\Traits\Api\ApiResponses;
use App\Traits\Helper\UploadFileTrait;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    use UploadFileTrait, ApiResponses;

    public function __construct()
    {
        $this->middleware(['permission:Feature list,admin'])->only(['index']);
        $this->middleware(['permission:Feature add,admin'])->only(['create', 'store']);
        $this->middleware(['permission:Feature edit,admin'])->only(['edit', 'update', 'updateStatus']);
        $this->middleware(['permission:Feature delete,admin'])->only(['destroy']);
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $features = Feature::query();
        $features = Feature::allFeatures($features);
        $columns = [
            'all' => _trans('All'),
            'code' => _trans('Feature code'),
            'name' => _trans('Feature name'),
            'ranking' => _trans('Feature ranking'),
        ];
        return view('admin.feature.index', compact('features', 'columns'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = false;
        return view('admin.feature.form', compact('edit'));
    }


    public function store(FeatureRequest $request): string|\Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        Feature::query()->create($data);
        return redirect()->route('admin.feature.index')->with('success', _trans('Done Save Data Successfully'));
    }

    public function show(Feature $feature)
    {
        //
    }

    public function edit(Feature $feature): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = true;
        return view('admin.feature.form', compact('edit', 'feature'));
    }

    public function update(FeatureRequest $request, Feature $feature): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $feature->update($data);
        return redirect()->route('admin.feature.index')->with('success', _trans('Done Updated Data Successfully'));
    }

    public function destroy(Feature $feature)
    {
        //
    }

    public function updateStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $feature = Feature::query()->findOrFail($request->id);
        if (!$feature) {
            return $this->success(_trans('Not Found'), status: false);
        }
        $status = !$feature->status;
        $feature->update(['status' => $status]);
        return $this->success(_trans('Done Updated Data Successfully'));
    }
}
