<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CountryRequest;
use App\Models\Country;
use App\Models\Currency;
use App\Traits\Api\ApiResponses;
use App\Traits\Helper\UploadFileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CountryController extends Controller
{
    use ApiResponses, UploadFileTrait;

    public function __construct()
    {
        $this->middleware(['permission:Country list,admin'])->only(['index']);
        $this->middleware(['permission:Country add,admin'])->only(['create']);
        $this->middleware(['permission:Country edit,admin'])->only(['edit']);
        $this->middleware(['permission:Country delete,admin'])->only(['destroy']);
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $countries = Country::query()
            ->with(['currency:id,code'])
            ->withCount(['governorates']);
        $countries = Country::allCountries($countries);
        $columns = [
            'all' => _trans('All'),
            'code' => _trans('Country Code'),
            'name' => _trans('Country name'),
        ];
        return view('admin.country.index', compact('countries', 'columns'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = false;
        $codes = File::get(base_path('database/seeders/egypt/CountryCodes.json'));
        $codes = json_decode($codes, true);
        $currencies = Currency::query()->status()->get();
        return view('admin.country.form', compact('edit', 'currencies', 'codes'));
    }

    public function store(CountryRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('icon')) {
            $data['icon'] = $this->upload([
                'file' => 'icon',
                'path' => 'country',
                'upload_type' => 'single',
                'delete_file' => '',
            ]);
        }
        Country::query()->create($data);
        return redirect()->route('admin.country.index')->with('success', _trans('Done Save Data Successfully'));
    }

    public function show(Country $country)
    {
        //
    }

    public function edit(Country $country): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = true;
        $currencies = Currency::query()->status()->get();
        $codes = File::get(base_path('database/seeders/egypt/CountryCodes.json'));
        $codes = json_decode($codes, true);
        return view('admin.country.form', compact('edit', 'currencies', 'codes', 'country'));
    }

    public function update(CountryRequest $request, Country $country): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('icon')) {
            $data['icon'] = $this->upload([
                'file' => 'icon',
                'path' => 'country',
                'upload_type' => 'single',
                'delete_file' => $country->icon ?? '',
            ]);
        }
        $country->update($data);
        return redirect()->route('admin.country.index')->with('success', _trans('Done Updated Data Successfully'));
    }

    public function destroy(Country $country)
    {
        //
    }

    public function updateStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $country = Country::query()->findOrFail($request->id);
        if (!$country) {
            return $this->success(_trans('Not Found'), status: false);
        }
        $status = !$country->status;
        $country->update(['status' => $status]);
        return $this->success(_trans('Done Updated Data Successfully'));

    }
}
