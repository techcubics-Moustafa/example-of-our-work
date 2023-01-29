<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CPU\Models;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Traits\Api\ApiResponses;
use App\Traits\Helper\UploadFileTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use UploadFileTrait, ApiResponses;

    public function __construct()
    {
        $this->middleware(['permission:Category list,admin'])->only(['index']);
        $this->middleware(['permission:Category add,admin'])->only(['create', 'store']);
        $this->middleware(['permission:Category edit,admin'])->only(['edit', 'update', 'updateStatus']);
        $this->middleware(['permission:Category delete,admin'])->only(['destroy']);
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $categories = Category::query()->with(['parent']);
        $categories = Category::allCategories($categories);
        $columns = [
            'all' => _trans('All'),
            'code' => _trans('Category code'),
            'name' => _trans('Category name'),
            'ranking' => _trans('Category ranking'),
        ];
        return view('admin.category.index', compact('categories', 'columns'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = false;
        $parents = Models::categories();
        return view('admin.category.form', compact('edit', 'parents'));
    }


    public function store(CategoryRequest $request): string|\Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->upload([
                'file' => 'image',
                'path' => 'category',
                'upload_type' => 'single',
                'delete_file' => ''
            ]);
        }
        Category::query()->create($data);
        return redirect()->route('admin.category.index')->with('success', _trans('Done Save Data Successfully'));
    }

    public function show(Category $category)
    {
        //
    }

    public function edit(Category $category): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = true;
        $parents = Category::query()->whereNull('parent_id');
        if (!$category->parent_id) {
            $parents = $parents->where('id', '!=', $category->id);
        }
        $parents = $parents->orderByDesc('created_at')->get();
        return view('admin.category.form', compact('edit', 'parents', 'category'));
    }

    public function update(CategoryRequest $request, Category $category): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $parent = Category::query()->whereNull('parent_id');
        if (!$category->parent_id) {
            $parent = $parent->where('id', '!=', $category->id);
        }
        $parent = $parent->find($request->parent_id);
        if ($request->hasFile('image')) {
            $data['image'] = $this->upload([
                'file' => 'image',
                'path' => 'category',
                'upload_type' => 'single',
                'delete_file' => $category->image ?? ''
            ]);
        }
        $data['parent_id'] = $parent?->id;
        $category->update($data);
        return redirect()->route('admin.category.index')->with('success', _trans('Done Updated Data Successfully'));
    }

    public function destroy(Category $category)
    {
        //
    }

    public function updateStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $category = Category::query()->findOrFail($request->id);
        if (!$category) {
            return $this->success(_trans('Not Found'), status: false);
        }
        $status = !$category->status;
        $category->update(['status' => $status]);
        return $this->success(_trans('Done Updated Data Successfully'));
    }
}
