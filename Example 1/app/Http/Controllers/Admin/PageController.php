<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;
use App\Models\Page;
use App\Traits\Api\ApiResponses;
use App\Traits\Helper\UploadFileTrait;
use Illuminate\Http\Request;

class PageController extends Controller
{
    use UploadFileTrait, ApiResponses;

    public function __construct()
    {
        $this->middleware(['permission:Page#Setup list,admin'])->only(['index']);
        $this->middleware(['permission:Page#Setup add,admin'])->only(['create']);
        $this->middleware(['permission:Page#Setup edit,admin'])->only(['edit']);
        $this->middleware(['permission:Page#Setup delete,admin'])->only(['destroy']);
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $data = Page::allPages();
        $columns = [
            'all' => _trans('All'),
            'page_type' => _trans('Page type'),
            'name' => _trans('Page name'),
        ];
        return view('admin.page.index', compact('data', 'columns'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = false;
        return view('admin.page.form', compact('edit'));
    }

    public function store(PageRequest $request): string|\Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->upload([
                'file' => 'image',
                'path' => 'page',
                'upload_type' => 'single',
                'delete_file' => ''
            ]);
        }
        Page::query()->updateOrCreate([
            'page_type' => $request->page_type,
        ], $data);
        return redirect()->route('admin.page.index')->with('success', _trans('Done Save Data Successfully'));
    }

    public function show(Page $page)
    {
        //
    }

    public function edit(Page $page): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = true;
        return view('admin.page.form', compact('edit', 'page'));
    }

    public function update(PageRequest $request, Page $page): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->upload([
                'file' => 'image',
                'path' => 'page',
                'upload_type' => 'single',
                'delete_file' => $page->image ?? ''
            ]);
        }
        $page->update($data);
        return redirect()->route('admin.page.index')->with('success', _trans('Done Updated Data Successfully'));
    }

    public function destroy($id)
    {
        $page = Page::query()->findOrFail($id);
        $this->deleteFile($page->image);
        $page->delete();
        return redirect()->route('admin.page.index')->with('success', _trans('Done Deleted Data Successfully'));
    }

    public function updateStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $page = Page::query()->findOrFail($request->id);
        if (!$page) {
            return $this->success(_trans('Not Found'), status: false);
        }
        $status = !$page->status;
        $page->update(['status' => $status]);
        return $this->success(_trans('Done Updated Data Successfully'));
    }
}
