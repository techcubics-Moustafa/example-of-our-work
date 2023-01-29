<?php

namespace App\Repositories\Project;

use App\Interfaces\Project\ProjectRepositoryInterface;
use App\Models\Project;
use App\Models\RealEstate;
use App\Traits\Helper\UploadFileTrait;

class ProjectRepository implements ProjectRepositoryInterface
{
    use UploadFileTrait;

    public function __construct(public Project $model)
    {

    }

    public function store($request, $projectData, $realEstateData): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        if ($request->hasFile('image')) {
            $realEstateData['image'] = $this->upload([
                'file' => 'image',
                'path' => 'project',
                'upload_type' => 'single',
                'delete_file' => ''
            ]);
        }
        if ($request->hasFile('youtube_video_thumbnail')) {
            $realEstateData['youtube_video_thumbnail'] = $this->upload([
                'file' => 'youtube_video_thumbnail',
                'path' => 'project',
                'upload_type' => 'single',
                'delete_file' => ''
            ]);
        }

        $project = $this->model->query()->create($projectData);
        $realEstate = $project->realEstate()->create($realEstateData);
        if ($request->has('images')) {
            $this->upload([
                'file' => 'images',
                'path' => "project/{$project->id}",
                'upload_type' => 'files',
                'multi_upload' => true,
                'relationable_id' => $project->id,
                'relationable_type' => Project::class,
            ]);
        }
        $realEstate->features()->attach($request->feature_id);
        return $project;
    }


    public function update($project, $request, $projectData, $realEstateData)
    {
        if ($request->hasFile('image')) {
            $realEstateData['image'] = $this->upload([
                'file' => 'image',
                'path' => 'project',
                'upload_type' => 'single',
                'delete_file' => $project->realEstate?->image ?? ''
            ]);
        }
        if ($request->hasFile('youtube_video_thumbnail')) {
            $realEstateData['youtube_video_thumbnail'] = $this->upload([
                'file' => 'youtube_video_thumbnail',
                'path' => 'project',
                'upload_type' => 'single',
                'delete_file' => $project->realEstate?->youtube_video_thumbnail ?? ''
            ]);
        }

        $project->update($projectData);
        $realEstate = RealEstate::query()->updateOrCreate([
            'modelable_type' => Project::class,
            'modelable_id' => $project->id,
        ], $realEstateData);
        if ($request->has('images')) {
            $this->upload([
                'file' => 'images',
                'path' => "project/{$project->id}",
                'upload_type' => 'files',
                'multi_upload' => true,
                'relationable_id' => $project->id,
                'relationable_type' => Project::class,
            ]);
        }
        $realEstate->features()->sync($request->feature_id);
        return $project;
    }
}
