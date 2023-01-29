<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SpecialRequest;
use App\Models\Special;
use App\Traits\Api\ApiResponses;
use App\Traits\Helper\UploadFileTrait;
use Illuminate\Http\Request;

class SpecialController extends Controller
{
    use UploadFileTrait, ApiResponses;

    public function __construct()
    {
        $this->middleware(['permission:Special list,admin'])->only(['index']);
        $this->middleware(['permission:Special add,admin'])->only(['create', 'store']);
        $this->middleware(['permission:Special edit,admin'])->only(['edit', 'update', 'updateStatus']);
        $this->middleware(['permission:Special delete,admin'])->only(['destroy']);
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $specials = Special::query();
        $specials = Special::allSpecials($specials);
        $columns = [
            'all' => _trans('All'),
            'code' => _trans('Special code'),
            'name' => _trans('Special name'),
            'ranking' => _trans('Special ranking'),
        ];
        return view('admin.special.index', compact('specials', 'columns'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = false;
        return view('admin.special.form', compact('edit'));
    }


    public function store(SpecialRequest $request): string|\Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        Special::query()->create($data);
        return redirect()->route('admin.special.index')->with('success', _trans('Done Save Data Successfully'));
    }

    public function show(Special $special)
    {
        //
    }

    public function edit(Special $special): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = true;
        return view('admin.special.form', compact('edit', 'special'));
    }

    public function update(SpecialRequest $request, Special $special): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $special->update($data);
        return redirect()->route('admin.special.index')->with('success', _trans('Done Updated Data Successfully'));
    }

    public function destroy(Special $special)
    {
        //
    }

    public function updateStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $special = Special::query()->findOrFail($request->id);
        if (!$special) {
            return $this->success(_trans('Not Found'), status: false);
        }
        $status = !$special->status;
        $special->update(['status' => $status]);
        return $this->success(_trans('Done Updated Data Successfully'));
    }
}
