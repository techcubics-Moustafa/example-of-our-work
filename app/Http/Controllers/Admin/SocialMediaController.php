<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Setting\Utility;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SocialMediaRequest;
use App\Models\SocialMedia;
use App\Traits\Api\ApiResponses;
use App\Traits\Helper\UploadFileTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    use UploadFileTrait, ApiResponses;

    public function __construct()
    {
        $this->middleware(['permission:Social#Media list,admin'])->only(['index']);
        $this->middleware(['permission:Social#Media add,admin'])->only(['create']);
        $this->middleware(['permission:Social#Media edit,admin'])->only(['edit']);
        $this->middleware(['permission:Social#Media delete,admin'])->only(['destroy']);
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $search = str_replace('SM#', '', request()->search);

        $socialMedia = SocialMedia::query()
            ->when(in_array(request()->column_name, ['slug', 'all']), function (Builder $builder) use ($search) {
                $builder->where('slug', 'LIKE', "%{$search}%");
            })
            ->paginate(Utility::getValByName('pagination_limit'))
            ->withQueryString();

        $columns = [
            'all' => _trans('All'),
            'slug' => _trans('Social Media name'),
        ];
        return view('admin.social-media.index', compact('socialMedia', 'columns'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = false;
        return view('admin.social-media.form', compact('edit'));
    }


    public function store(SocialMediaRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('icon')) {
            $data['icon'] = $this->upload([
                'file' => 'icon',
                'path' => 'social_media',
                'upload_type' => 'single',
                'delete_file' => ''
            ]);
        }
        SocialMedia::query()->create($data);
        return redirect()->route('admin.social-media.index')->with('success', _trans('Done Save Data Successfully'));
    }


    public function show(SocialMedia $socialProvider)
    {
        //
    }


    public function edit($id): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = true;
        $socialMedia = SocialMedia::query()->findOrFail($id);
        return view('admin.social-media.form', compact('edit', 'socialMedia'));
    }


    public function update(SocialMediaRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $socialMedia = SocialMedia::query()->findOrFail($id);
        if ($request->hasFile('icon')) {
            $data['icon'] = $this->upload([
                'file' => 'icon',
                'path' => 'social_media',
                'upload_type' => 'single',
                'delete_file' => $socialMedia->icon ?? ''
            ]);
        }
        $socialMedia->update($data);
        return redirect()->route('admin.social-media.index')->with('success', _trans('Done Updated Data Successfully'));
    }


    public function destroy($id)
    {
        $socialMedia = SocialMedia::query()->findOrFail($id);
        if (!$socialMedia) {
            return $this->success(message: _trans('Not found this social media'), status: false);
        }
        $icon = $socialMedia->icon;
        $socialMedia->delete();
        $this->deleteFile($icon ?? '');
        return $this->success(message: _trans('Done Deleted Data Successfully'));
    }


    public function updateStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $socialMedia = SocialMedia::query()->findOrFail($request->id);
        if (!$socialMedia) {
            return $this->success(_trans('Not Found'), status: false);
        }
        $status = !$socialMedia->status;
        $socialMedia->update(['status' => $status]);
        return $this->success(_trans('Done Updated Data Successfully'));
    }
}
