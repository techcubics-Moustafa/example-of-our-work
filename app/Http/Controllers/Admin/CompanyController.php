<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserType;
use App\Helpers\CPU\Models;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompanyRequest;
use App\Models\Company;
use App\Traits\Api\ApiResponses;
use App\Traits\Helper\UploadFileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    use UploadFileTrait, ApiResponses;

    public function __construct()
    {
        $this->middleware(['permission:Company list,admin'])->only(['index']);
        $this->middleware(['permission:Company add,admin'])->only(['create', 'store']);
        $this->middleware(['permission:Company edit,admin'])->only(['edit', 'update', 'updateStatus']);
        $this->middleware(['permission:Company delete,admin'])->only(['destroy']);
    }

    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $companies = Company::query()->with(['user', 'country', 'governorate', 'region']);
        $companies = Company::allCompanies($companies);
        $columns = [
            'all' => _trans('All'),
            'code' => _trans('Company Code'),
            'name' => _trans('Company name'),
            'user' => _trans('User name'),
            'country' => _trans('Country name'),
            'governorate' => _trans('Governorate name'),
            'region' => _trans('Region name'),
        ];
        return view('admin.company.index', compact('companies', 'columns'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = false;
        $countries = Models::countries();
        $users = Models::users(UserType::Company);
        $categories = Models::categories();
        return view('admin.company.form', compact('edit', 'countries', 'users', 'categories'));
    }

    public function store(CompanyRequest $request): string|\Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('logo')) {
            $data['logo'] = $this->upload([
                'file' => 'logo',
                'path' => 'company',
                'upload_type' => 'single',
                'delete_file' => ''
            ]);
        }
        $data['location'] = "{$request->lat},{$request->lng}";
        try {
            DB::beginTransaction();
            $company = Company::query()->create($data);
            /* qr-code */
            /* $encrypt = Crypt::encrypt($company->id);
             $url = route('download-app') . "?token=$encrypt";
             Models::qrCodeGenerator($url, 'company/company_id_' . $company['id'] . '.png');
             $company->update(['qr_code' => 'company/company_id_' . $company['id'] . '.png']);*/
            DB::commit();
            return redirect()->route('admin.company.index')->with('success', _trans('Done Save Data Successfully'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())->withInput($request->all());
        }
    }

    public function show($id)
    {

    }


    public function edit(Company $company): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = true;
        $countries = Models::countries();
        $users = Models::users(UserType::Company);
        $categories = Models::categories();
        return view('admin.company.form', compact('edit', 'company', 'countries', 'users', 'categories'));
    }

    public function update(CompanyRequest $request, Company $company): string|\Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('logo')) {
            $data['logo'] = $this->upload([
                'file' => 'logo',
                'path' => 'company',
                'upload_type' => 'single',
                'delete_file' => $company->logo ?? ''
            ]);
        }
        $data['location'] = "{$request->lat},{$request->lng}";
        try {
            DB::beginTransaction();
            $company->update($data);
            /* if (!$company->qr_code) {
                 $encrypt = Crypt::encrypt($company->id);
                 $url = route('download-app') . "?token=$encrypt";
                 Models::qrCodeGenerator($url, 'company/company_id_' . $company['id'] . '.png');
                 $company->update(['qr_code' => 'company/company_id_' . $company['id'] . '.png']);
             }*/
            DB::commit();
            return redirect()->route('admin.company.index')->with('success', _trans('Done Save Data Successfully'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())->withInput($request->all());
        }
    }

    public function destroy($id)
    {
        //
    }

    public function updateStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $company = Company::query()->findOrFail($request->id);
        if (!$company) {
            return $this->success(_trans('Not Found'), status: false);
        }
        $status = !$company->status;
        $company->update(['status' => $status]);
        return $this->success(_trans('Done Updated Data Successfully'));
    }

    function downloadQrCode($id)
    {
        $id = Crypt::decrypt($id);
        $company = Company::query()->findOrFail($id);
        if ($company->qr_code) {
            if (Storage::disk('public')->exists($company->qr_code)) {
                return Storage::download($company->qr_code, $company->translateOrDefault(locale())?->name . '.png');
            }
            return redirect()->back()->with('warning', _trans('Not found this qr code') . ' ' . $company->translateOrDefault(locale())?->name);
        }
        return redirect()->back()->with('warning', _trans('Please update in company') . ' ' . $company->translateOrDefault(locale())?->name);
    }
}
