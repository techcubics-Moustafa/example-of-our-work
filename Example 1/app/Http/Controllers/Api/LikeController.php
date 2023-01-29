<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Setting\Utility;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LikeRequest;
use App\Http\Resources\Like\LikeResource;
use App\Models\RealEstate;
use App\Traits\Api\ApiResponses;
use Illuminate\Support\Str;

class LikeController extends Controller
{
    use ApiResponses;

    public function index()
    {
        $user = auth('sanctum')->user();
        $likes = $user->likes()->where('modelable_type', '=', RealEstate::class)
            ->paginate(Utility::getValByName('pagination_limit'))
            ->withQueryString();
        /*$likes->each(function ($like) {
            if ($like->modelable_type == RealEstate::class) {

            }
        });*/
        return $this->success(LikeResource::collection($likes), $likes);

    }

    public function store(LikeRequest $request)
    {
        $headline = Str::headline($request->modelable_type);
        $class = Str::replace(' ', '', $headline);
        $user = auth('sanctum')->user();
        $data = [
            'modelable_type' => "App\\Models\\{$class}",
            'modelable_id' => $request->modelable_id,
        ];
        $model = $user->likes()->where($data)->first();
        if (!$model) {
            $user->likes()->create($data);
            return $this->success(_trans("Done like in this {$request->modelable_type}"));
        }
        $model->delete();
        return $this->success(_trans("Done unlike in this {$request->modelable_type}"));

    }

}
