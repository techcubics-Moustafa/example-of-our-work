<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CPU\Models;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JoinUsRequest;
use App\Models\JoinUs;
use App\Models\Owner;
use App\Models\Clinic;
use App\Traits\Helper\UploadFileTrait;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Propaganistas\LaravelPhone\PhoneNumber;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\Permission\Models\Role;

class JoinUsController extends Controller
{
    use UploadFileTrait;

    public function __construct()
    {
        $this->middleware(['permission:Join#Us list,admin'])->only(['index']);
        $this->middleware(['permission:Join#Us add,admin'])->only(['show']);
        $this->middleware(['permission:Join#Us delete,admin'])->only(['delete']);
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $data = JoinUs::allJoinUs();
            $columns = [
                'all' => _trans('All'),
                'clinic_name' => _trans('Clinic name'),
                'owner_name' => _trans('Owner name'),
                'owner_phone' => _trans('Owner phone'),
            ];
            return view('admin.join-us.index', compact('data', 'columns'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function show($id): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $clinic = JoinUs::query()->with(['country', 'specializations'])->findOrFail($id);
        $countries = Models::countries();
        $weeks = Models::weeks();
        $specializations = Models::specializations();
        $serviceTypes = Models::servicesTypes();
        $months = Models::months();
        if ($clinic->owner_phone) {
            $clinic->owner_phone = PhoneNumber::make($clinic->owner_phone, $clinic->country->code)
                ->formatForMobileDialingInCountry($clinic->country->code);
        }
        return view('admin.join-us.form', compact('countries', 'weeks', 'specializations',
            'clinic', 'serviceTypes', 'months'));
    }

    public function store(JoinUsRequest $request)
    {
        $userData = $request->only(['country_id', 'governorate_id', 'region_id']);
        $role = Role::findByName('owner', 'web');
        $userData['name'] = $request->owner_name;
        $userData['email'] = $request->owner_email;
        $userData['password'] = $request->password;
        $userData['role_id'] = $role->id;
        $dataOwner = $request->only(['address', 'brand_name']);
        $code = Models::country($request->country_id)?->code;
        if ($code) {
            $dataOwner['phone'] = (string)PhoneNumber::make($request->owner_phone, $code);
        }
        try {
            DB::beginTransaction();
            $owner = Owner::query()->create($dataOwner);
            $user = $owner->user()->create($userData);
            $user->assignRole($role);
            if ($user) {
                $clinicData = $request->validated();
                $clinicData['owner_id'] = $owner->id;
                if ($request->hasFile('logo')) {
                    $clinicData['logo'] = $this->upload([
                        'file' => 'logo',
                        'path' => 'clinic',
                        'upload_type' => 'single',
                        'delete_file' => ''
                    ]);
                }
                $clinic = Clinic::query()->create($clinicData);
                $clinic->specializations()->attach($request->specialization_id);
                $clinic->workingHours()->createMany($request->clinics);
                $encrypt = Crypt::encrypt($clinic->id);
                $url = route('download-app') . "?token=$encrypt";
                Models::qrCodeGenerator($url, 'clinic/clinic_id_' . $clinic['id'] . '.png');
                $clinic->update(['qr_code' => 'clinic/clinic_id_' . $clinic['id'] . '.png']);
                JoinUs::query()->find($request->join_id)->delete();
                DB::commit();
                return redirect()->route('admin.clinic.index')->with('success', _trans('Done Save Data Successfully'));
            }
            return redirect()->back()->with('warning', _trans('Please try again'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }


    public function delete($id): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $data = JoinUs::query()->findOrFail($id);
            $data->delete();
            return redirect()->back()->with('success', _trans('Done Delete information clinic'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

}
