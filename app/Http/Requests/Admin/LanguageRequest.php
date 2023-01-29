<?php

namespace App\Http\Requests\Admin;

use App\Enums\Direction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LanguageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onData(): array
    {
        return [
            'flag' => ['required', 'string', 'min:1', 'max:15'],
            'direction' => ['required', 'string', Rule::enum(Direction::class)],
        ];
    }

    protected function onCreate(): array
    {
        return array_merge($this->onData(), [
            'name' => ['required', 'string', 'min:2', 'max:50', Rule::unique('languages', 'name')],
            'code' => ['required', 'string', 'min:2', 'max:10',
                Rule::unique('languages', 'code'),
                'in:' . implode(',', array_keys(config('languages.languages')))
            ],
        ]);
    }

    protected function onUpdate(): array
    {
        return array_merge($this->onData(), [
            'name' => ['required', 'string', 'min:2', 'max:50',
                Rule::unique('languages', 'name')
                    ->ignore(request()->segment(3), 'id')
            ],
            'code' => ['required', 'string', 'min:2', 'max:10',
                Rule::unique('languages', 'code')
                    ->ignore(request()->segment(3), 'id'),
                'in:' . implode(',', array_keys(config('languages.languages')))
            ],
        ]);
    }

    public function rules(): array
    {
        if (request()->routeIs('admin.language.store')) {
            return $this->onCreate();
        } elseif (request()->routeIs('admin.language.update')) {
            return $this->onUpdate();
        } else {
            return [];
        }
    }
}
