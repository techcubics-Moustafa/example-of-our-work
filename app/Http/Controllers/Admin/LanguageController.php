<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CPU\CreateFileLanguages;
use App\Helpers\Setting\Utility;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LanguageRequest;
use App\Models\Language;
use App\Traits\Api\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LanguageController extends Controller
{
    use ApiResponses;

    public function __construct()
    {
        $this->middleware(['permission:Language list,admin'])->only(['index']);
        $this->middleware(['permission:Language add,admin'])->only(['create']);
        $this->middleware(['permission:Language edit,admin'])->only(['edit']);
        $this->middleware(['permission:Language delete,admin'])->only(['destroy']);
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $languages = Language::allLanguages();
        $columns = [
            'all' => _trans('All'),
            'code' => _trans('Language Code'),
            'name' => _trans('Language name'),
        ];
        return view('admin.language.index', compact('languages', 'columns'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = false;
        $files = File::files(base_path('public/flags'));
        $languages = config('languages.languages');
        return view('admin.language.form', compact('edit', 'files', 'languages'));
    }

    public function store(LanguageRequest $request)
    {
        $data = $request->validated();
        Language::query()->create($data);
        CreateFileLanguages::file($request['code']);
        return redirect()->route('admin.language.index')->with('success', _trans('Done Save Data Successfully'));
    }

    public function show(Language $language)
    {
        //
    }

    public function edit(Language $language): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = true;
        $files = File::files(base_path('public/flags'));
        $languages = config('languages.languages');
        return view('admin.language.form', compact('edit', 'files', 'language', 'languages'));
    }

    public function update(LanguageRequest $request, Language $language)
    {
        $data = $request->validated();
        $language->update($data);
        CreateFileLanguages::file($request['code']);
        return redirect()->route('admin.language.index')->with('success', _trans('Done Update Data Successfully'));
    }

    public function updateStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $language = Language::query()->find($request->id);
        if (!$language) {
            return $this->success(_trans('Not Found'), status: false);
        }
        if ($language->default) {
            return $this->success(_trans('You can not disabled is language'), status: false);
        }

        $status = !$language->status;
        $language->update(['status' => $status]);
        return $this->success(_trans('Done Updated Data Successfully'));

    }

    public function updateDefaultStatus(Request $request)
    {
        $language = Language::query()->find($request->id);
        if (!$language) {
            return $this->success(_trans('Not found this language'), false);
        }
        $language->update(['default' => true, 'status' => true]);
        $language->fresh();
        Language::query()
            ->where([
                'default' => true,
                ['id', "!=", $language->id]
            ])
            ->update(['default' => 0]);
        return $this->success(_trans('Done Updated Data Successfully'));
    }

    public function destroy($id)
    {
        $language = Language::query()->find($id);
        if (!$language) {
            return $this->success(message: _trans('Not found this language'), status: false);
        }
        if ($language->default) {
            return $this->success(message: _trans('You can not delete is language, please select another default language'), status: false);
        }
        $language->delete();
        /*$dir = base_path('lang/' . $language->code);
                   $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
                   $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
                   foreach ($files as $file) {
                       if ($file->isDir()) {
                           rmdir($file->getRealPath());
                       } else {
                           unlink($file->getRealPath());
                       }
                   }
                   rmdir($dir);*/
        return $this->success(message: _trans('Done delete language successfully'));

    }

    public function translate(Request $request, $lang): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $columns = [
            'all' => _trans('All'),
            'key' => _trans('Key'),
            'value' => _trans('Value'),
        ];

        $full_data = include(base_path('lang/' . $lang . '/message.php'));
        $data = [];
        ksort($full_data);

        foreach ($full_data as $key => $value) {
            $data[] = ['key' => $key, 'value' => $value];
        }
        if ($request->filled('column_name')) {
            $data = collect($data)->filter(function ($item) use ($request) {
                if ($request->column_name == 'key') {
                    return stripos($item['key'], $request->search) !== false;
                } elseif ($request->column_name == 'value') {
                    return stripos($item['value'], $request->search) !== false;
                } else {
                    return stripos($item['key'], $request->search) !== false || stripos($item['value'], $request->search) !== false;
                }
            });
        }

        $data = $this->paginate($data);
        $data->withPath('');

        return view('admin.language.translate', compact('lang', 'data', 'columns'));
    }

    public function translate_submit(Request $request, $lang): \Illuminate\Http\JsonResponse
    {
        $full_data = include(base_path('lang/' . $lang . '/message.php'));
        $full_data[$request['key']] = $request['value'];
        $str = "<?php return " . var_export($full_data, true) . ";";
        file_put_contents(base_path('lang/' . $lang . '/message.php'), $str);
        return $this->success(_trans('Done Updated Data Successfully'));
    }

    public function paginate($items, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, Utility::getValByName('pagination_limit')), $items->count(), Utility::getValByName('pagination_limit'), $page, $options);
    }
}
