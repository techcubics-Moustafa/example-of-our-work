<?php

namespace App\Repositories\Property;


use App\Interfaces\Property\PropertyRepositoryInterface;
use App\Models\Property;
use App\Models\RealEstate;
use App\Traits\Helper\UploadFileTrait;

class PropertyRepository  implements PropertyRepositoryInterface
{
    use UploadFileTrait;

    public function __construct(public Property $model)
    {

    }

    public function store($request, $propertyData, $realEstateData): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        if ($request->hasFile('image')) {
            $realEstateData['image'] = $this->upload([
                'file' => 'image',
                'path' => 'property',
                'upload_type' => 'single',
                'delete_file' => ''
            ]);
        }
        if ($request->hasFile('youtube_video_thumbnail')) {
            $realEstateData['youtube_video_thumbnail'] = $this->upload([
                'file' => 'youtube_video_thumbnail',
                'path' => 'property',
                'upload_type' => 'single',
                'delete_file' => ''
            ]);
        }

        $property = Property::query()->create($propertyData);
        $realEstate = RealEstate::query()->updateOrCreate([
            'modelable_type' => Property::class,
            'modelable_id' => $property->id,
        ], $realEstateData);
        if ($request->has('images')) {
            $this->upload([
                'file' => 'images',
                'path' => "property/{$property->id}",
                'upload_type' => 'files',
                'multi_upload' => true,
                'relationable_id' => $property->id,
                'relationable_type' => Property::class,
            ]);
        }
        $realEstate->features()->attach($request->feature_id);
        return $property;
    }


    public function update($project, $request, $propertyData, $realEstateData)
    {
        if ($request->hasFile('image')) {
            $realEstateData['image'] = $this->upload([
                'file' => 'image',
                'path' => 'property',
                'upload_type' => 'single',
                'delete_file' => $property->realEstate?->image ?? ''
            ]);
        }
        if ($request->hasFile('youtube_video_thumbnail')) {
            $realEstateData['youtube_video_thumbnail'] = $this->upload([
                'file' => 'youtube_video_thumbnail',
                'path' => 'property',
                'upload_type' => 'single',
                'delete_file' => $property->realEstate?->youtube_video_thumbnail ?? ''
            ]);
        }

        $property->update($propertyData);
        $realEstate = RealEstate::query()->updateOrCreate([
            'modelable_type' => Property::class,
            'modelable_id' => $property->id,
        ], $realEstateData);
        if ($request->has('images')) {
            $this->upload([
                'file' => 'images',
                'path' => "property/{$property->id}",
                'upload_type' => 'files',
                'multi_upload' => true,
                'relationable_id' => $property->id,
                'relationable_type' => Property::class,
            ]);
        }
        $realEstate->features()->sync($request->feature_id);
        return $property;
    }
}
