<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserType;
use App\Helpers\CPU\Models;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use App\Traits\Api\ApiResponses;
use App\Traits\Helper\UploadFileTrait;
use Illuminate\Http\Request;
use Propaganistas\LaravelPhone\PhoneNumber;

class UserController extends Controller
{
    use UploadFileTrait, ApiResponses;

    public function __construct()
    {
        $this->middleware(['permission:User list,admin'])->only(['index']);
        $this->middleware(['permission:User add,admin'])->only(['create', 'store']);
        $this->middleware(['permission:User edit,admin'])->only(['edit', 'update', 'updateStatus']);
        $this->middleware(['permission:User delete,admin'])->only(['destroy']);
    }

    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $users = User::query()->with(['country', 'governorate', 'region']);
        $users = User::allUsers($users);
        $users->each(function ($user) {
            if ($user->phone) {
                $user->phone = PhoneNumber::make($user->phone, $user->country->code)
                    ->formatForMobileDialingInCountry($user->country->code);
            }
            /*if ($user->user_type == UserType::Company->value) {
                $user->load(['company']);
            }*/
        });
        $columns = [
            'all' => _trans('All'),
            'code' => _trans('Code'),
            'user_type' => _trans('User type'),
            'name' => _trans('Full name'),
            'phone' => _trans('Phone'),
            'country' => _trans('Country name'),
            'governorate' => _trans('Governorate name'),
            'region' => _trans('Region name'),
        ];
        return view('admin.user.index', compact('users', 'columns'));
    }


    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = false;
        $countries = Models::countries();
        return view('admin.user.form', compact('edit', 'countries'));
    }

    public function store(UserRequest $request): string|\Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $code = Models::country($request->country_id)?->code;
        if ($code) {
            $data['phone'] = (string)PhoneNumber::make($request->phone, $code);
        }
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->upload([
                'file' => 'avatar',
                'path' => 'user',
                'upload_type' => 'single',
                'delete_file' => ''
            ]);
        }
        User::query()->create($data);
        return redirect()->route('admin.user.index')->with('success', _trans('Done Save Data Successfully'));
    }

    public function show($id)
    {

    }

    public function edit($id): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = true;
        $user = User::query()->findOrFail($id);
        if ($user->phone) {
            $user->load('country');
            $user->phone = PhoneNumber::make($user->phone, $user->country->code)
                ->formatForMobileDialingInCountry($user->country->code);
        }
        $countries = Models::countries();
        return view('admin.user.form', compact('edit', 'countries', 'user'));
    }


    public function update(UserRequest $request, User $user): string|\Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $code = Models::country($request->country_id)?->code;
        if ($code) {
            $data['phone'] = (string)PhoneNumber::make($request->phone, $code);
        }
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->upload([
                'file' => 'avatar',
                'path' => 'user',
                'upload_type' => 'single',
                'delete_file' => $user->avatar ?? ''
            ]);
        }
        $user->update($data);
        return redirect()->route('admin.user.index')->with('success', _trans('Done Updated Data Successfully'));
    }


    public function destroy($id)
    {
        //
    }

    public function updateStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = User::query()->find($request->id);
        if (!$user) {
            return $this->success(_trans('Not Found'), status: false);
        }
        $status = !$user->status;
        $user->update(['status' => $status]);
        return $this->success(_trans('Done Updated Data Successfully'));
    }

    public function changePassword(UserRequest $request): \Illuminate\Http\RedirectResponse
    {
        $user = User::query()->find($request->id);
        $user->update(['password' => $request->password]);
        return redirect()->route('admin.user.index')->with('success', _trans('Done Updated Data Successfully'));
    }
}
