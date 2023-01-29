<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Setting\Utility;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PropertyRequest;
use App\Http\Resources\Property\PropertyResource;
use App\Interfaces\Property\PropertyRepositoryInterface;
use App\Models\Property;
use App\Traits\Api\ApiResponses;
use App\Traits\Helper\UploadFileTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Admin\PropertyController as PropertyAdminController;

class PropertyController extends Controller
{
    use ApiResponses, UploadFileTrait;

    public function __construct(public readonly PropertyRepositoryInterface $propertyRepository)
    {

    }

    public function index()
    {
        $properties = Property::query()
            ->withWhereHas('realEstate', fn(Builder $builder) => $builder->where('user_id', '=', auth('sanctum')->id()));
        $properties = Property::filters($properties)
            ->paginate(Utility::getValByName('pagination_limit'))
            ->withQueryString();
        return $this->success(PropertyResource::collection($properties), $properties);
    }

    public function store(PropertyRequest $request)
    {
        $user = auth('sanctum')->user();
        $propertyData = $request->validated();
        $realEstateData = PropertyAdminController::realEstateData($request);
        $realEstateData['user_id'] = $user->id;
        unset($realEstateData['publish']);
        try {
            DB::beginTransaction();
            $property = $this->propertyRepository->store($request, $propertyData, $realEstateData);
            DB::commit();
            return $this->success(PropertyResource::make($property->load('realEstate')));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->failure($exception->getMessage());
        }
    }

    public function show($id)
    {
        $property = Property::query()
            ->whereRelation('realEstate', 'user_id', '=', auth('sanctum')->id())
            ->with([
                'realEstateDetail' => ['special', 'country', 'governorate', 'region', 'category', 'subCategory', 'currency', 'features'],
                'project',
                'images',
            ])
            ->find($id);
        return $this->success($property ? PropertyResource::make($property) : null);
    }

    public function update(PropertyRequest $request, $id)
    {
        $property = Property::query()
            ->whereRelation('realEstate', 'user_id', '=', auth('sanctum')->id())
            ->find($id);
        if (!$property)
            return $this->failure(_trans('Not found this property'));
        $propertyData = $request->validated();
        $realEstateData = PropertyAdminController::realEstateData($request);
        unset($realEstateData['publish'], $realEstateData['user_id']);
        try {
            DB::beginTransaction();
            $property = $this->propertyRepository->update($property, $request, $propertyData, $realEstateData);
            DB::commit();
            return $this->success(PropertyResource::make($property->load('realEstate')));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->failure($exception->getMessage());
        }
    }
}
