<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SocialMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onCreate(): array
    {
        return [
            'slug' => ['required', 'string', 'min:1', 'max:50', Rule::unique('social_media', 'slug')],
            'icon' => validationImage(),
            'url' => ['required', 'string', 'min:1', 'max:255', 'url'],
        ];
    }

    protected function onUpdate(): array
    {
        return [
            'slug' => ['required', 'string', 'min:1', 'max:50',
                Rule::unique('social_media', 'slug')
                    ->ignore(request()->segment(3), 'id')
            ],
            'icon' => validationImage(),
            'url' => ['required', 'string', 'min:1', 'max:255', 'url'],
        ];
    }

    public function rules(): array
    {
        if (request()->routeIs('admin.social-media.store')) {
            return $this->onCreate();
        } elseif (request()->routeIs('admin.social-media.update')) {
            return $this->onUpdate();
        } else {
            return [];
        }
    }

    public function attributes(): array
    {
        return [
            'slug' => _trans('Name social media'),
            'url' => _trans('Link social media'),
            'icon' => _trans('Icon'),
        ];
    }
}
