<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Service;
use App\Traits\Api\ApiResponses;
use App\Traits\Helper\UploadFileTrait;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use UploadFileTrait, ApiResponses;

    public function __construct()
    {
        $this->middleware(['permission:Service list,admin'])->only(['index']);
        $this->middleware(['permission:Service add,admin'])->only(['create', 'store']);
        $this->middleware(['permission:Service edit,admin'])->only(['edit', 'update', 'updateStatus']);
        $this->middleware(['permission:Service delete,admin'])->only(['destroy']);
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $services = Service::query();
        $services = Service::allServices($services);
        $columns = [
            'all' => _trans('All'),
            'code' => _trans('Service code'),
            'name' => _trans('Service name'),
            'ranking' => _trans('Service ranking'),
        ];
        return view('admin.service.index', compact('services', 'columns'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = false;
        return view('admin.service.form', compact('edit'));
    }


    public function store(ServiceRequest $request): string|\Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        Service::query()->create($data);
        return redirect()->route('admin.service.index')->with('success', _trans('Done Save Data Successfully'));
    }

    public function show(Service $service)
    {
        //
    }

    public function edit(Service $service): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = true;
        return view('admin.service.form', compact('edit', 'service'));
    }

    public function update(ServiceRequest $request, Service $service): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $service->update($data);
        return redirect()->route('admin.service.index')->with('success', _trans('Done Updated Data Successfully'));
    }

    public function destroy(Service $service)
    {
        //
    }

    public function updateStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $service = Service::query()->findOrFail($request->id);
        if (!$service) {
            return $this->success(_trans('Not Found'), status: false);
        }
        $status = !$service->status;
        $service->update(['status' => $status]);
        return $this->success(_trans('Done Updated Data Successfully'));
    }
}
