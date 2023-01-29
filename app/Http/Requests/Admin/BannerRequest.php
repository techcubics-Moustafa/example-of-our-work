<?php

namespace App\Http\Requests\Admin;

use App\Rules\ResourceId;
use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onData(): array
    {
        return [
            'resource_type' => ['required_unless:banner_type,==,Popup'],
            'banner_type' => ['required', 'string', 'in:Main,Footer,Popup'],
            'resource_id' => ['nullable', 'integer', new ResourceId($this->resource_type)],
            'link' => ['nullable', 'url', 'string', 'min:2', 'max:255'],
        ];
    }

    protected function onCreate(): array
    {
        $rules = [
            'image' => array_merge(['required'], validationImage()),
        ];
        foreach (locales() as $locale) {
            $rules += [
                $locale . '.content' => ['nullable', 'string', 'min:2'],
            ];
        }
        return array_merge($this->onData(), $rules);
    }

    protected function onUpdate(): array
    {
        $rules = [
            'image' => array_merge(['nullable'], validationImage()),
        ];
        foreach (locales() as $locale) {
            $rules += [
                $locale . '.content' => ['nullable', 'string', 'min:2'],
            ];
        }
        return array_merge($this->onData(), $rules);
    }

    public function rules(): array
    {
        if (request()->routeIs('admin.banner.store')) {
            return $this->onCreate();
        } elseif (request()->routeIs('admin.banner.update')) {
            return $this->onUpdate();
        } else {
            return [];
        }
    }
}
