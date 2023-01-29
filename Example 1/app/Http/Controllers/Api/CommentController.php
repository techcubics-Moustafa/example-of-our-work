<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Setting\Utility;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CommentRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Traits\Api\ApiResponses;

class CommentController extends Controller
{
    use ApiResponses;

    public function index()
    {
        $user = auth('sanctum')->user();
        $comments = $user->comments()
            ->with(['user'])
            ->orderByDesc('created_at')
            ->paginate(Utility::getValByName('pagination_limit'))
            ->withQueryString();

        $comments->each(function ($comment) use ($user) {
            if ($comment->user_id != $user->id) {
                $comment->load('user');
            } else {
                $comment->setRealtion('user', $user);
            }
        });
        return $this->success([
            'comments' => CommentResource::collection($comments),
            'total_comments' => $comments->total(),
        ], $comments);
    }

    public function store(CommentRequest $request)
    {
        $user = auth('sanctum')->user();
        $data = $request->validated();
        $data['user_id'] = $user->id;
        $comment = $user->comments()->create($data);
        return $this->success(CommentResource::make($comment));
    }

    public function update(CommentRequest $request, $id)
    {
        $user = auth('sanctum')->user();
        $comment = $user->comments()->find($id);
        if (!$comment) {
            return $this->failure(_trans('Not found this comment'));
        }
        $data = $request->validated();
        $comment->update($data);
        $question = $comment->fresh();
        return $this->success(CommentResource::make($question));
    }
}
