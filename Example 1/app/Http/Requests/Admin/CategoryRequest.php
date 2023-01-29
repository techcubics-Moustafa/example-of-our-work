<?php

namespace App\Http\Requests\Admin;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onData(): array
    {
        return [
            'ranking' => ['required', 'integer', 'min:1'],
            'parent_id' => ['sometimes', 'nullable', 'integer',
                Rule::exists('categories', 'id')
                    ->whereNull('parent_id')
                    ->where('status', Status::Active->value)
            ],

        ];
    }

    protected function onCreate(): array
    {
        $rules = [
            'image' => validationImage(true),
        ];
        foreach (locales() as $locale) {
            $rules += [
                $locale . '.name' => ['required', 'string', 'min:2', 'max:100', Rule::unique('category_translations', 'name')],
            ];
        }
        return array_merge($this->onData(),$rules);
    }

    protected function onUpdate(): array
    {
        $rules = [
            'image' => validationImage(),
        ];
        foreach (locales() as $locale) {
            $rules += [
                $locale . '.name' => ['required', 'string', 'min:2', 'max:100',
                    Rule::unique('category_translations', 'name')
                        ->ignore(request()->segment(3), 'category_id')
                ],
            ];
        }
        return array_merge($this->onData(),$rules);
    }

    public function rules(): array
    {
        if (request()->routeIs('admin.category.store')) {
            return $this->onCreate();
        } elseif (request()->routeIs('admin.category.update')) {
            return $this->onUpdate();
        } else {
            return [];
        }
    }

    public function attributes(): array
    {
        $messages = [
            'ranking' => _trans('Category ranking'),
            'parent_id' => _trans('Main Category name'),
            'image' => _trans('image'),
        ];
        foreach (locales() as $locale) {
            $messages += [
                $locale . '.name' => _trans('Category name') . ' (' . ucfirst($locale) . ')',
            ];
        }
        return $messages;
    }
}
