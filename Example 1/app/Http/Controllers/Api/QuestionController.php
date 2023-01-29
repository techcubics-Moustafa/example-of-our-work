<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Setting\Utility;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\QuestionRequest;
use App\Http\Resources\Question\QuestionResource;
use App\Traits\Api\ApiResponses;

class QuestionController extends Controller
{
    use ApiResponses;

    public function index()
    {
        $user = auth('sanctum')->user();
        $questions = $user->questions()
            ->with(['country', 'governorate', 'region'])
            ->withCount(['answers'])
            ->orderByDesc('answers_count')
            ->paginate(Utility::getValByName('pagination_limit'))
            ->withQueryString();

        return $this->success(QuestionResource::collection($questions), $questions);
    }

    public function store(QuestionRequest $request)
    {
        $user = auth('sanctum')->user();
        $data = $request->validated();
        $data['user_id'] = $user->id;
        $question = $user->questions()->create($data);
        $question = $question->loadCount('answers')->load(['country', 'governorate', 'region']);
        return $this->success(QuestionResource::make($question));
    }

    public function show($id)
    {
        $user = auth('sanctum')->user();
        $question = $user->questions()->find($id);
        if (!$question) {
            return $this->failure(_trans('Not found this question'));
        }
        $question = $question->loadCount('answers')->load(['country', 'governorate', 'region']);
        return $this->success(QuestionResource::make($question));
    }

    public function update(QuestionRequest $request, $id)
    {
        $user = auth('sanctum')->user();
        $question = $user->questions()->find($id);
        if (!$question) {
            return $this->failure(_trans('Not found this question'));
        }
        $data = $request->validated();
        $question->update($data);
        $question = $question->loadCount('answers')->load(['country', 'governorate', 'region']);
        return $this->success(QuestionResource::make($question));
    }

    public function destroy($id)
    {
        $user = auth('sanctum')->user();
        $question = $user->questions()->find($id);
        if (!$question) {
            return $this->failure(_trans('Not found this question'));
        }
        $question->answers()->delete();
        $question->delete();
        return $this->success(_trans('Done delete question successfully'));
    }

}
