<?php

namespace App\Http\Controllers;

use App\Enums\Direction;
use App\Models\Blog;
use App\Traits\Api\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    use ApiResponses;

    public function language($local, $guard = null): \Illuminate\Http\RedirectResponse
    {
        try {
            $direction = Direction::RTL->value;
            $language = languages();
            $lang = 'ar';

            foreach ($language as $data) {
                if ($data->code == $local) {
                    $direction = $data->direction ?? Direction::RTL->value;
                    $lang = $data->code;
                }
            }
            if (Arr::exists(config('auth.guards'), $guard)) {
                if (Auth::guard($guard)->check()) {
                    Auth::guard($guard)->user()->update(['lang' => $lang]);
                }
            }
            session()->put('local', $local);
            session()->put('direction', $direction);
            App::setLocale($lang);
            return redirect()->back();
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function getSharedLinkPreview(Request $request)
    {
        $request->validate([
            'type' => ['required', 'string', Rule::in(['service', 'clinic', 'special-offer', 'medical-offer', 'discount', 'blog'])],
            'redirect' => ['required', 'string', 'url', 'max:10000'],
            'item_id' => ['required', 'string', 'max:255'],
        ]);
        switch ($request->type) {
            case 'clinic':
                $item = Clinic::query()->whereTranslation('slug', $request->item_id, $request->lang ?? locale())->first();
                if (!$item) {
                    return $this->failure(_trans('Not found this page'));
                }
                $arr_path = explode('/', $item->logo);
                $data['image'] = getReducedAvatar($item->logo, end($arr_path));
                break;
            case 'special-offer':
                $item = SpecialOffer::query()->with(['image'])->whereTranslation('slug', $request->item_id, $request->lang ?? locale())->first();
                if (!$item) {
                    return $this->failure(_trans('Not found this page'));
                }
                $data['image'] = getReducedAvatar($item->image->full_file, $item->image->file);
                break;
            case 'medical-offer':
                $item = MedicalOffer::query()->with(['image'])->whereTranslation('slug', $request->item_id, $request->lang ?? locale())->first();
                if (!$item) {
                    return $this->failure(_trans('Not found this page'));
                }
                $data['image'] = getReducedAvatar($item->image->full_file, $item->image->file);
                break;
            case 'discount':
                $discount = Discount::query()->find($request->item_id);
                if (!$discount) {
                    return $this->failure(_trans('Not found this page'));
                }
                $item = Service::query()->with(['image'])->findOrFail($discount->service_id);
                $data['image'] = getReducedAvatar($item->image->full_file, $item->image->file);
                break;
            case 'service':
                $item = Service::query()->with(['image'])->whereTranslation('slug', $request->item_id, $request->lang ?? locale())->first();
                if (!$item) {
                    return $this->failure(_trans('Not found this page'));
                }
                $data['image'] = getReducedAvatar($item->image->full_file, $item->image->file);
                break;
            case 'blog':
                $item = Blog::query()->with(['image'])->whereTranslation('slug', $request->item_id, $request->lang ?? locale())->first();
                if (!$item) {
                    return $this->failure(_trans('Not found this page'));
                }
                $data['image'] = getReducedAvatar($item->image->full_file, $item->image->file);
                break;
        }
        if ($request->type === 'blog') {
            $data['name'] = wordwrap($item?->translate($request->lang ?? locale())?->title, 35);
            $data['description'] = wordwrap($item?->translate($request->lang ?? locale())?->content, 30);
        } else {
            $data['name'] = wordwrap($item?->translate($request->lang ?? locale())?->name, 35);
            $data['description'] = wordwrap($item?->translate($request->lang ?? locale())?->description, 30);
        }
        $data['description']=removeHtmlTags($data['description']);
        $data['redirect'] = $request->input('redirect');
        return view('for-frontend.link-preview', compact('data'));
    }
}
