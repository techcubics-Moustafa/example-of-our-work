<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReportCommentRequest;
use App\Http\Resources\ReportComment\ReportCommentResource;
use App\Models\ReportComment;
use App\Models\ReportCommentUser;
use App\Traits\Api\ApiResponses;

class ReportCommentController extends Controller
{
    use ApiResponses;

    public function __construct()
    {
        $this->middleware([
            'auth:sanctum', 'user-status-blocked'
        ])->only('store');
    }

    public function index()
    {
        $reportComments = ReportComment::query()
            ->orderByDesc('ranking')
            ->status()
            ->get();
        return $this->success(ReportCommentResource::collection($reportComments));
    }


    public function store(ReportCommentRequest $request)
    {

        ReportCommentUser::query()->updateOrCreate([
            'user_id' => auth('sanctum')->id(),
            'comment_id' => $request->comment_id,
            'report_comment_id' => $request->report_comment_id,
        ], [
            'user_id' => auth('sanctum')->id(),
            'comment_id' => $request->comment_id,
            'report_comment_id' => $request->report_comment_id,
        ]);
        return $this->success(message: _trans('Done report comment successfully'));
    }
}
