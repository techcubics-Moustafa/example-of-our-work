<?php

namespace App\Http\Requests\Admin;

use App\Enums\Status;
use App\Enums\UserType;
use App\Helpers\CPU\Models;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function onData(): array
    {
        return [
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')
                ->where('user_type', UserType::Company->value)
                ->where('status', Status::Active->value)
                ->where('blocked', Status::Not_Active->value)
            ],
            'phone' => ['required', 'string', 'phone:' . Models::country($this->country_id)?->code,],
            'whatsapp_number' => ['required', 'string', 'phone:' . Models::country($this->country_id)?->code,],
            'country_id' => ['required', 'integer', Rule::exists('countries', 'id')
                ->where('status', Status::Active->value)
            ],
            'governorate_id' => ['required', 'integer', Rule::exists('governorates', 'id')
                ->where('country_id', $this->country_id)
                ->where('status', Status::Active->value)
            ],
            'region_id' => ['required', 'integer', Rule::exists('regions', 'id')
                ->where('governorate_id', $this->governorate_id)
                ->where('status', Status::Active->value)
            ],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')
                ->whereNull('parent_id')
                ->where('status', Status::Active->value)
            ],
            'sub_category_id' => ['required', 'integer', Rule::exists('categories', 'id')
                ->where('parent_id', $this->category_id)
                ->where('status', Status::Active->value)
            ],
            'social_media' => ['required', 'array', 'min:1', 'max:7'],
            'social_media.*.name' => ['required', 'string', 'min:1', 'max:50'],
            'social_media.*.link' => ['required', 'string', 'min:1', 'url'],
            /*'lat' => ['required', 'string', 'regex:/^(\+|-)?(?:90(?:(?:\.0{1,8})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,8})?))$/'],
            'lng' => ['required', 'string', 'regex:/^(\+|-)?(?:180(?:(?:\.0{1,8})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,8})?))$/'],*/
            'lat' => ['required', 'string', 'max:70'],
            'lng' => ['required', 'string', 'max:70'],
            'status' => ['required', 'boolean', Rule::enum(Status::class)],
        ];
    }

    public function onNames(): array
    {

    }

    public function onCreate(): array
    {
        $rules = [
            'email' => ['required', 'email', 'string', 'min:2', 'max:255', /*Rule::unique('companies', 'email')*/],
            'logo' => validationImage(true),
        ];
        foreach (locales() as $locale) {
            $rules += [
                $locale . '.name' => ['required', 'string', 'min:1', 'max:50', Rule::unique('company_translations', 'name')],
                $locale . '.description' => ['required', 'string', 'min:2'],
                $locale . '.address' => ['nullable', 'string', 'min:2', 'max:100'],
            ];
        }
        return array_merge($this->onData(), $rules);
    }

    public function onUpdate(): array
    {
        //$company = $this->route()->parameter('company');
        $id = request()->segment(3);
        $rules = [
            'email' => ['required', 'email', 'string', 'min:2', 'max:255',
                Rule::unique('companies', 'email')
                    ->ignore($id, 'id')],
            'logo' => validationImage(),
        ];

        foreach (locales() as $locale) {
            $rules += [
                $locale . '.name' => ['required', 'string', 'min:1', 'max:50',
                    Rule::unique('company_translations', 'name')
                        ->ignore($id, 'company_id')
                ],
                $locale . '.description' => ['required', 'string', 'min:2'],
                $locale . '.address' => ['nullable', 'string', 'min:2', 'max:100'],
            ];
        }
        return array_merge($this->onData(), $rules);
    }

    public function rules(): array
    {
        if (request()->routeIs('admin.company.store')) {
            return $this->onCreate();
        } elseif (request()->routeIs('admin.company.update')) {
            return $this->onUpdate();
        } else {
            return [];
        }
    }

    public function attributes(): array
    {
        $messages = [
            'user_id' => _trans('user name'),
            'country_id' => _trans('Country name'),
            'governorate_id' => _trans('Governorate name'),
            'region_id' => _trans('Region name'),
            'category_id' => _trans('Category name'),
            'sub_category_id' => _trans('Sub category name'),
            'whatsapp_number' => _trans('Whatsapp number'),
            'social_media' => _trans('Social media'),
            'social_media.*.name' => _trans('Social media name'),
            'social_media.*.link' => _trans('Social media link'),
            'lat' => _trans('Latitude'),
            'lng' => _trans('Longitude'),
            'logo' => _trans('Logo'),
            'email' => _trans('Email'),
            'status' => _trans('Status'),
        ];
        foreach (locales() as $locale) {
            $messages += [
                $locale . '.name' => _trans('Company name') . ' (' . ucfirst($locale) . ')',
                $locale . '.description' => _trans('Company description') . ' (' . ucfirst($locale) . ')',
                $locale . '.address' => _trans('Company address') . ' (' . ucfirst($locale) . ')',
            ];
        }
        return $messages;
    }
}
