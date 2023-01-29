<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Setting\Utility;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReportCommentRequest;
use App\Models\ReportComment;
use App\Traits\Api\ApiResponses;
use App\Traits\Helper\UploadFileTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReportCommentController extends Controller
{
    use ApiResponses, UploadFileTrait;

    public function __construct()
    {
        $this->middleware(['permission:Report#Comment list,admin'])->only(['index']);
        $this->middleware(['permission:Report#Comment add,admin'])->only(['create']);
        $this->middleware(['permission:Report#Comment edit,admin'])->only(['edit']);
        $this->middleware(['permission:Report#Comment delete,admin'])->only(['destroy']);
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $search = str_replace('RC#', '', request('search'));
        $reportComments = ReportComment::query()
            ->withCount(['reportCommentUsers'])
            ->when(request()->column_name == 'code', function (Builder $builder) use ($search) {
                $builder->where('id', '=', $search);
            })
            ->when(request()->column_name == 'title', function (Builder $builder) use ($search) {
                $builder->whereTranslationLike('title', "%{$search}%", default_lang());
            })
            ->when(request()->column_name == 'all', function (Builder $builder) use ($search) {
                $builder->orWhere('id', '=', $search)
                    ->orWhereTranslationLike('title', "%{$search}%", default_lang());
            })
            ->orderByDesc('ranking')
            ->paginate(Utility::getValByName('pagination_limit'))
            ->withQueryString();
        $columns = [
            'all' => _trans('All'),
            'code' => _trans('Report Comment Code'),
            'title' => _trans('Report Comment title'),
        ];

        return view('admin.report-comment.index', compact('reportComments', 'columns'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = false;
        return view('admin.report-comment.form', compact('edit'));
    }

    public function store(ReportCommentRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        ReportComment::query()->create($data);
        return redirect()->route('admin.report-comment.index')->with('success', _trans('Done Save Data Successfully'));
    }

    public function show(ReportComment $reportComment)
    {
        //
    }

    public function edit(ReportComment $reportComment): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = true;
        return view('admin.report-comment.form', compact('edit', 'reportComment'));
    }

    public function update(ReportCommentRequest $request, ReportComment $reportComment): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $reportComment->update($data);
        return redirect()->route('admin.report-comment.index')->with('success', _trans('Done Updated Data Successfully'));
    }

    public function destroy($id)
    {
        $reportComment = ReportComment::query()->find($id);
        if (!$reportComment) {
            return $this->success(message: _trans('Not Found this Report comment'), status: false);
        }
        $logo = $reportComment->logo;
        $reportComment->delete();
        $this->deleteFile($logo ?? '');
        return $this->success(message: _trans('Done Deleted Data Successfully'));
    }

    public function updateStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $reportComment = ReportComment::query()->findOrFail($request->id);
        if (!$reportComment) {
            return $this->success(_trans('Not Found'), status: false);
        }
        $status = !$reportComment->status;
        $reportComment->update(['status' => $status]);
        return $this->success(_trans('Done Updated Data Successfully'));
    }
}
