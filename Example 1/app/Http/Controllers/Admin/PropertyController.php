<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CPU\Models;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PropertyRequest;
use App\Interfaces\Property\PropertyRepositoryInterface;
use App\Models\Property;
use App\Traits\Api\ApiResponses;
use App\Traits\Helper\UploadFileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{
    use UploadFileTrait, ApiResponses;

    public function __construct(public readonly PropertyRepositoryInterface $propertyRepository)
    {
        $this->middleware(['permission:Property list,admin'])->only(['index']);
        $this->middleware(['permission:Property add,admin'])->only(['create', 'store']);
        $this->middleware(['permission:Property edit,admin'])->only(['edit', 'update', 'updateStatus']);
        $this->middleware(['permission:Property delete,admin'])->only(['destroy']);
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $properties = Property::query()->with(['realEstate']);
        $properties = Property::allProperties($properties);
        $columns = [
            'all' => _trans('All'),
            'code' => _trans('Property code'),
            'name' => _trans('Property name'),
        ];
        return view('admin.property.index', compact('properties', 'columns'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = false;
        $specials = Models::special();
        $countries = Models::countries();
        $features = Models::features();
        $categories = Models::categories();
        $currencies = Models::currencies();
        $users = Models::users();
        $projects = Models::projects();
        return view('admin.property.form', compact('edit', 'specials', 'countries', 'users', 'features', 'categories', 'currencies', 'projects'));
    }

    public static function realEstateData($request)
    {
        $realEstateData = $request->only([
            'publish', 'user_id', 'special_id', 'country_id', 'governorate_id', 'region_id',
            'category_id', 'sub_category_id', 'currency_id', 'youtube_video_url',
            'start_date', 'end_date',
        ]);
        $locales = $request->only(locales());
        $realEstateData['location'] = "{$request->lat},{$request->lng}";
        return array_merge($locales, $realEstateData);
    }


    public function store(PropertyRequest $request)
    {
        $propertyData = $request->only([
            'type', 'status', 'moderation_status', 'project_id', 'number_bedrooms',
            'number_bathrooms', 'number_floors', 'square', 'price'
        ]);
        $realEstateData = $this->realEstateData($request);
        try {
            DB::beginTransaction();
            $this->propertyRepository->store($request, $propertyData, $realEstateData);
            DB::commit();
            return redirect()->route('admin.property.index')->with('success', _trans('Done Save Data Successfully'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())->withInput($request->all());
        }
    }

    public function show(Property $property)
    {
        //
    }

    public function edit(Property $property): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = true;
        $specials = Models::special();
        $countries = Models::countries();
        $features = Models::features();
        $categories = Models::categories();
        $currencies = Models::currencies();
        $users = Models::users();
        $projects = Models::projects();
        $property->load(['realEstate', 'images']);
        $property->loadCount(['images']);
        return view('admin.property.form', compact('edit', 'property', 'specials', 'countries', 'users', 'features', 'categories', 'currencies', 'projects'));
    }

    public function update(PropertyRequest $request, Property $property)
    {
        $propertyData = $request->validated();
        $realEstateData = $this->realEstateData($request);
        try {
            DB::beginTransaction();
            $this->propertyRepository->update($property, $request, $propertyData, $realEstateData);
            DB::commit();
            return redirect()->route('admin.property.index')->with('success', _trans('Done Save Data Successfully'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())->withInput($request->all());
        }
    }

    public function destroy(Property $property)
    {
        //
    }

    public function updatePublish(Request $request): \Illuminate\Http\JsonResponse
    {
        $property = Property::query()->has('realEstate')->find($request->id);
        if (!$property) {
            return $this->success(_trans('Not Found'), status: false);
        }
        $property->load('realEstate');
        $publish = !$property->realEstate->publish;
        $property->realEstate()->update(['publish' => $publish]);
        return $this->success(_trans('Done Updated Data Successfully'));
    }

    public function deleteImage(Request $request)
    {
        $file = Models::deleteFile([
            'id' => $request->id,
            'relationable_type' => Property::class,
            'relationable_id' => $request->relation_id,
        ]);
        if ($file) {
            $this->deleteFile($file->full_file);
            $file->delete();
            return $this->success(_trans('Done Deleted Image Successfully'));
        }
        return $this->success(_trans('Not found this image'), status: false);
    }
}
