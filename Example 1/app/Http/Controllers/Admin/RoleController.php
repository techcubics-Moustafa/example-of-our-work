<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PermissionAdmin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Interfaces\Role\RoleRepositoryInterface;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    private RoleRepositoryInterface $roleRepositoryInterface;

    public function __construct(RoleRepositoryInterface $roleRepositoryInterface)
    {
        $this->middleware(['permission:Role list,admin'])->only(['index']);
        $this->middleware(['permission:Role add,admin'])->only(['create']);
        $this->middleware(['permission:Role edit,admin'])->only(['edit']);
        $this->middleware(['permission:Role delete,admin'])->only(['destroy']);
        $this->roleRepositoryInterface = $roleRepositoryInterface;
    }

    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $roles = $this->roleRepositoryInterface->index('super_admin', 'admin');
            $roles = Role::allRoles($roles);
            $columns = [
                'all' => _trans('All'),
                'name' => _trans('Role name'),
            ];
            return view('admin.role.index', compact('roles', 'columns'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = false;
        $modules = PermissionAdmin::models();
        return view('admin.role.form', compact('edit', 'modules'));
    }

    public function store(RoleRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $data['guard_name'] = 'admin';
        try {
            DB::beginTransaction();
            $this->roleRepositoryInterface->store($request, $data, PermissionAdmin::models(), PermissionAdmin::lists(), $data['guard_name']);
            DB::commit();
            return redirect()->route('admin.role.index')->with('success', _trans('Done Save Data Successfully'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())->withInput($request->all());
        }
    }

    public function show($id): \Illuminate\Http\RedirectResponse
    {
        return redirect()->back()->with('error', _trans('Not Allow Access Here'));
    }

    public function edit($id): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = true;
        $modules = PermissionAdmin::models();
        $role = $this->roleRepositoryInterface->edit($id, 'super_admin', 'admin');
        return view('admin.role.form', compact('edit', 'modules', 'role'));
    }

    public function update(RoleRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        DB::beginTransaction();
        $data = $request->validated();
        try {
            $this->roleRepositoryInterface->update($request, $data, $id, 'super_admin', PermissionAdmin::models(), PermissionAdmin::lists(), 'admin', 'update');
            DB::commit();
            return redirect()->route('admin.role.index')->with('success', _trans('Done Updated Data Successfully'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())->withInput($request->all());
        }
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->roleRepositoryInterface->destroy($id, 'super_admin', 'admin');
            DB::commit();
            return redirect()->route('admin.role.index')->with('success', _trans('Done Deleted Data Successfully'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
