<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ShareRequest;
use App\Models\Share;
use App\Traits\Api\ApiResponses;

class ShareController extends Controller
{
    use ApiResponses;

    public function __invoke(ShareRequest $request)
    {
        $user = auth('sanctum')->user();
        $data = $request->validated();
        $data['user_id'] = $user->id;
        Share::query()->updateOrCreate($data, $data);
        return $this->success(_trans('Done share real estate successfully'));

    }

}
