<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OurPartnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onData(): array
    {
        return [
            'link' => ['required', 'string', 'url', 'max:255'],
            'ranking' => ['required', 'numeric', 'min:0'],
        ];
    }

    protected function onCreate(): array
    {
        return array_merge($this->onData(), [
            'logo' => array_merge(['required'], validationImage()),
        ]);
    }

    protected function onUpdate(): array
    {
        return array_merge($this->onData(), [
            'logo' => array_merge(['nullable'], validationImage()),
        ]);
    }

    public function rules(): array
    {
        if (request()->routeIs('admin.our-partner.store')) {
            return $this->onCreate();
        } elseif (request()->routeIs('admin.our-partner.update')) {
            return $this->onUpdate();
        } else {
            return [];
        }
    }

    public function attributes(): array
    {
        return [
            'logo' => _trans('Logo'),
            'link' => _trans('Link'),
        ];
    }
}
