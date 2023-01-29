<?php

namespace App\Http\Requests\Admin;

use App\Helpers\CPU\Models;
use App\Models\Blog;
use App\Models\SpecialOffer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onData(): array
    {
        return [
            'category_id' => ['required', 'integer', Rule::exists('blog_categories', 'id')
                ->where('status', 1)],
            /*tags*/
            'tags' => ['nullable', 'array', 'min:1'],
        ];
    }

    protected function onCreate(): array
    {
        $rules = [
            'default_image' => array_merge(['required'], validationImage()),
            'images' => ['required', 'array', 'min:1', 'max:4'],
            'images.*' => array_merge(['required'], validationImage()),
        ];

        foreach (locales() as $locale) {
            $rules += [
                'tags.*.name:' . $locale => ['nullable', 'string'],
                $locale . '.title' => ['required', 'string', 'min:2', 'max:100'],
                $locale . '.content' => ['sometimes', 'nullable', 'string', 'min:2'],
            ];
        }
        return array_merge($this->onData(), $rules);
    }

    protected function onUpdate(): array
    {
        $rules = [
            'default_image' => array_merge(['nullable'], validationImage()),
        ];
        $files = getFiles(['id' => request()->segment(3), 'type' => Blog::class])->count();

        foreach (locales() as $locale) {
            $rules += [
                'tags.*.name:' . $locale => ['nullable', 'string', 'min:1', 'max:255'],
                $locale . '.title' => ['required', 'string', 'min:2', 'max:100'],
                $locale . '.content' => ['sometimes', 'nullable', 'string', 'min:2'],

            ];
        }
        return array_merge(array_merge($rules, Models::ruleImages($files)), $this->onData());
    }

    public function rules(): array
    {
        if (request()->routeIs('admin.blogs.store')) {
            return $this->onCreate();
        } elseif (request()->routeIs('admin.blogs.update')) {
            return $this->onUpdate();
        } else {
            return [];
        }
    }

    public function attributes(): array
    {
        $messages = [
            'category_id' => _trans('Category name'),
            'images' => _trans('Photos'),
            'images.*' => _trans('Photos'),
            'default_image' => _trans('Default Image'),
            'tags' => _trans('Tags'),
            'tags.*' => _trans('Tags'),
        ];
        foreach (locales() as $locale) {
            $messages += [
                $locale . '.title' => _trans('Blog title') . ' (' . ucfirst($locale) . ')',
                $locale . '.content' => _trans('Blog content') . ' (' . ucfirst($locale) . ')',
                'tags.*.name:' . $locale => _trans('Tag name') . ' (' . ucfirst($locale) . ')',
            ];
        }
        return $messages;
    }
}
