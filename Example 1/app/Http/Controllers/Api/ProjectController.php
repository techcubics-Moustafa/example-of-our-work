<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Setting\Utility;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProjectRequest;
use App\Http\Resources\Project\ProjectResource;
use App\Interfaces\Project\ProjectRepositoryInterface;
use App\Models\Project;
use App\Traits\Api\ApiResponses;
use App\Traits\Helper\UploadFileTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    use ApiResponses, UploadFileTrait;

    public function __construct(public readonly ProjectRepositoryInterface $projectRepository)
    {

    }

    public function index()
    {
        $projects = Project::query()
            ->withWhereHas('realEstate', fn(Builder $builder) => $builder->where('user_id', '=', auth('sanctum')->id()))
            ->paginate(Utility::getValByName('pagination_limit'))
            ->withQueryString();
        return $this->success(ProjectResource::collection($projects), $projects);
    }

    public function store(ProjectRequest $request)
    {
        $user = auth('sanctum')->user();
        $projectData = $request->validated();
        $realEstateData = PropertyController::realEstateData($request);
        $realEstateData['user_id'] = $user->id;
        unset($realEstateData['publish']);
        try {
            DB::beginTransaction();
            $project = $this->projectRepository->store($request, $projectData, $realEstateData);
            DB::commit();
            return $this->success(ProjectResource::make($project->load('realEstate')));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->failure($exception->getMessage());
        }
    }

    public function show($id)
    {
        $project = Project::query()
            ->whereRelation('realEstate', 'user_id', '=', auth('sanctum')->id())
            ->with([
                'realEstateDetail' => ['special', 'country', 'governorate', 'region', 'category', 'subCategory', 'currency', 'features'],
                'images'
            ])
            ->find($id);
        return $this->success($project ? ProjectResource::make($project) : null);
    }

    public function update(ProjectRequest $request, $id)
    {
        $project = Project::query()
            ->whereRelation('realEstate', 'user_id', '=', auth('sanctum')->id())
            ->find($id);
        if (!$project)
            return $this->failure(_trans('Not found this project'));
        $projectData = $request->validated();
        $realEstateData = PropertyController::realEstateData($request);
        unset($realEstateData['publish'], $realEstateData['user_id']);
        try {
            DB::beginTransaction();
            $project = $this->projectRepository->update($project, $request, $projectData, $realEstateData);
            DB::commit();
            return $this->success(ProjectResource::make($project->load('realEstate')));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->failure($exception->getMessage());
        }
    }
}
