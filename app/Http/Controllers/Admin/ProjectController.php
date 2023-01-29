<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserType;
use App\Helpers\CPU\Models;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectRequest;
use App\Interfaces\Project\ProjectRepositoryInterface;
use App\Models\Project;
use App\Models\RealEstate;
use App\Traits\Api\ApiResponses;
use App\Traits\Helper\UploadFileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    use UploadFileTrait, ApiResponses;

    public function __construct(public readonly ProjectRepositoryInterface $projectRepository)
    {
        $this->middleware(['permission:Project list,admin'])->only(['index']);
        $this->middleware(['permission:Project add,admin'])->only(['create', 'store']);
        $this->middleware(['permission:Project edit,admin'])->only(['edit', 'update', 'updateStatus']);
        $this->middleware(['permission:Project delete,admin'])->only(['destroy']);
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $projects = Project::query()->with(['realEstate', 'company']);
        $projects = Project::allProjects($projects);
        $columns = [
            'all' => _trans('All'),
            'code' => _trans('Project code'),
            'name' => _trans('Project title'),
        ];
        return view('admin.project.index', compact('projects', 'columns'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = false;
        $specials = Models::special();
        $countries = Models::countries();
        $features = Models::features();
        $categories = Models::categories();
        $currencies = Models::currencies();
        $users = Models::users(UserType::Company);
        return view('admin.project.form', compact('edit', 'countries', 'specials', 'features', 'categories', 'currencies', 'users'));
    }

    public function store(ProjectRequest $request)
    {
        $projectData = $request->validated();
        $realEstateData = PropertyController::realEstateData($request);
        try {
            DB::beginTransaction();
            $this->projectRepository->store($request, $projectData, $realEstateData);
            DB::commit();
            return redirect()->route('admin.project.index')->with('success', _trans('Done Save Data Successfully'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())->withInput($request->all());
        }
    }

    public function show(Project $project)
    {
        //
    }

    public function edit(Project $project): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = true;
        $specials = Models::special();
        $countries = Models::countries();
        $features = Models::features();
        $categories = Models::categories();
        $currencies = Models::currencies();
        $users = Models::users(UserType::Company);
        return view('admin.project.form', compact('edit', 'project', 'specials', 'countries', 'features',
            'categories', 'currencies', 'users'));
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $projectData = $request->validated();
        $realEstateData = PropertyController::realEstateData($request);

        try {
            DB::beginTransaction();
            $this->projectRepository->update($project, $request, $projectData, $realEstateData);
            DB::commit();
            return redirect()->route('admin.project.index')->with('success', _trans('Done Save Data Successfully'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())->withInput($request->all());
        }
    }

    public function destroy(Project $project)
    {
        //
    }

    public function updatePublish(Request $request): \Illuminate\Http\JsonResponse
    {
        $project = Project::query()->has('realEstate')->find($request->id);
        if (!$project) {
            return $this->success(_trans('Not Found'), status: false);
        }
        $project->load('realEstate');
        $publish = !$project->realEstate->publish;
        $project->realEstate()->update(['publish' => $publish]);
        return $this->success(_trans('Done Updated Data Successfully'));
    }

    public function deleteImage(Request $request)
    {
        $file = Models::deleteFile([
            'id' => $request->id,
            'relationable_type' => Project::class,
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
