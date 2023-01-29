<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Setting\Utility;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EmployeeRequest;
use App\Interfaces\Role\RoleRepositoryInterface;
use App\Models\Admin;
use App\Traits\Api\ApiResponses;
use App\Traits\Helper\UploadFileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    use UploadFileTrait, ApiResponses;

    private RoleRepositoryInterface $roleRepositoryInterface;

    public function __construct(RoleRepositoryInterface $roleRepositoryInterface)
    {
        $this->middleware(['permission:Employee list,admin'])->only(['index']);
        $this->middleware(['permission:Employee add,admin'])->only(['create']);
        $this->middleware(['permission:Employee edit,admin'])->only(['edit']);
        $this->middleware(['permission:Employee delete,admin'])->only(['destroy']);
        $this->roleRepositoryInterface = $roleRepositoryInterface;
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        // Employee
        $employees = Admin::query()->with(['role'])->whereNotIn('id', [1]);
        $employees = Admin::allAdmin($employees);
        $columns = [
            'all' => _trans('All'),
            'name' => _trans('Employee name'),
            'email' => _trans('Email'),
            'phone' => _trans('Phone'),
            'role_name' => _trans('Role name'),
        ];
        return view('admin.employee.index', compact('employees', 'columns'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = false;
        $roles = $this->roleRepositoryInterface->index('super_admin', 'admin')->orderByDesc('created_at')->get();
        return view('admin.employee.form', compact('edit', 'roles'));
    }

    public function store(EmployeeRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $role = $this->roleRepositoryInterface->edit($request->role_id, 'super_admin', 'admin');
        if (!$role) {
            return redirect()->back()->with('error', _trans('Please Select Role First'))->withInput($request->all());
        }

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->upload([
                'file' => 'avatar',
                'path' => 'admin',
                'upload_type' => 'single',
                'delete_file' => '',
            ]);
        }

        try {
            DB::beginTransaction();
            $admin = Admin::query()->create($data);
            $admin->assignRole($role);
            DB::commit();
            return redirect()->route('admin.employee.index')->with('success', _trans('Done Save Data Successfully'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())->withInput($request->all());
        }

    }

    public function show($id)
    {
        //
    }

    public function edit($id): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = true;
        $admin = Admin::query()->whereNotIn('id', [1])->findOrFail($id);
        $roles = $this->roleRepositoryInterface->index('super_admin', 'admin')->orderByDesc('created_at')->get();
        return view('admin.employee.form', compact('edit', 'roles', 'admin'));
    }

    public function update(EmployeeRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $data = $request->validated();
            $admin = Admin::query()->whereNotIn('id', [1])->findOrFail($id);
            $role = $this->roleRepositoryInterface->edit($request->role_id, 'super_admin', 'admin');
            if (!$role) {
                return redirect()->back()->with('error', _trans('Please Select Role First'))->withInput($request->all());
            }
            if ($request->hasFile('avatar')) {
                $data['avatar'] = $this->upload([
                    'file' => 'avatar',
                    'path' => 'admin',
                    'upload_type' => 'single',
                    'delete_file' => $admin->avatar ?? '',
                ]);
            }

            DB::beginTransaction();
            if ($role->id != $request->role_id) {
                $oldRole = $this->roleRepositoryInterface->index('super_admin', 'admin')
                    ->where('id', '=', $admin->role_id)->first();
                if (!empty($oldRole)) {
                    $admin->removeRole($oldRole);
                }
            }
            $admin->update($data);
            if ($role->id != $request->role_id) {
                $admin->assignRole($role);
            }
            DB::commit();
            return redirect()->route('admin.employee.index')->with('success', _trans('Done Updated Data Successfully'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())->withInput($request->all());
        }
    }

    public function destroy($id)
    {

    }

    public function updateStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $admin = Admin::query()->whereNotIn('id', [1])->find($request->id);
        if (!$admin) {
            return $this->success(_trans('Not Found'));
        }
        $status = !$admin->status;
        $admin->update(['status' => $status]);
        return $this->success(_trans('Done Updated Data Successfully'));
    }

    public function changePassword(EmployeeRequest $request): \Illuminate\Http\RedirectResponse
    {
        $employee = Admin::query()->whereNotIn('id', [1])->findOrFail($request->id);
        $employee->update(['password' => $request->password]);
        return redirect()->route('admin.employee.index')->with('success', _trans('Done change password successfully'));
    }
}
