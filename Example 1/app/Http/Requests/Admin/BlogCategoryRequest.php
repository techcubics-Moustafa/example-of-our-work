<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onCreate(): array
    {
        $rules = [
            'image' => array_merge(['required'], validationImage()),
        ];
        foreach (locales() as $locale) {
            $rules += [
                $locale . '.name' => ['required', 'string', 'min:2', 'max:100', Rule::unique('blog_category_translations', 'name')],
            ];
        }
        return $rules;
    }

    protected function onUpdate(): array
    {
        $rules = [
            'id' => ['required', 'integer', Rule::exists('blog_categories', 'id')],
            'image' => array_merge(['nullable'], validationImage())
        ];
        foreach (locales() as $locale) {
            $rules += [
                $locale . '.name' => ['required', 'string', 'min:2', 'max:100',
                    Rule::unique('blog_category_translations', 'name')->ignore($this->id, 'category_id')],
            ];
        }
        return $rules;
    }

    public function rules(): array
    {
        if (request()->routeIs('admin.blog-category.store')) {
            return $this->onCreate();
        } elseif (request()->routeIs('admin.blog-category.update')) {
            return $this->onUpdate();
        } else {
            return [];
        }
    }
}
