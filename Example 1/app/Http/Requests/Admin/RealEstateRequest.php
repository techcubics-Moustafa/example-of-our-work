<?php

namespace App\Http\Requests\Admin;

use App\Enums\Status;
use Illuminate\Validation\Rule;

class RealEstateRequest
{
    public static function rules(): array
    {
        $rules = [
            'publish' => ['required', 'boolean', Rule::enum(Status::class)],
            'user_id' => ['required', 'integer',
                Rule::exists('users', 'id')
                    ->where('status', Status::Active->value)
                    ->where('blocked', Status::Not_Active->value)
            ],
            'special_id' => ['required', 'integer', Rule::exists('specials', 'id')
                ->where('status', Status::Active->value)
            ],
            'country_id' => ['required', 'integer', Rule::exists('countries', 'id')
                ->where('status', Status::Active->value)
            ],
            'governorate_id' => ['required', 'integer', Rule::exists('governorates', 'id')
                ->where('country_id', request()->country_id)
                ->where('status', Status::Active->value)
            ],
            'region_id' => ['required', 'integer', Rule::exists('regions', 'id')
                ->where('governorate_id', request()->governorate_id)
                ->where('status', Status::Active->value)
            ],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')
                ->whereNull('parent_id')
                ->where('status', Status::Active->value)
            ],
            'sub_category_id' => ['required', 'integer', Rule::exists('categories', 'id')
                ->where('parent_id', request()->category_id)
                ->where('status', Status::Active->value)
            ],
            'currency_id' => ['sometimes', 'nullable', 'integer', Rule::exists('currencies', 'id')
                ->where('status', Status::Active->value)
            ],
            'feature_id' => ['sometimes', 'nullable', 'array'],
            'feature_id.*' => ['sometimes', 'nullable', 'integer',
                Rule::exists('features', 'id')
                    ->where('status', Status::Active->value)
            ],
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric'],
            'youtube_video_thumbnail' => validationImage(),
            'youtube_video_url' => ['nullable', 'string', 'url', 'max:255'],

        ];
        foreach (locales() as $locale) {
            $rules += [
                $locale . '.title' => ['required', 'string', 'min:1', 'max:100'],
                $locale . '.description' => ['required', 'string', 'min:1', 'max:600'],
                $locale . '.content' => ['required', 'string', 'min:1'],
                $locale . '.address' => ['nullable', 'string', 'min:1', 'max:255'],
                $locale . '.seo_title' => ['nullable', 'string', 'min:1', 'max:255'],
                $locale . '.seo_description' => ['nullable', 'string', 'min:1', 'max:500'],
            ];
        }
        return $rules;
    }

    public static function validationDate($model, $start): array
    {
        $rules = [];
        if ($model->start_date != $start) {
            $rules += [
                'start_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:' . now()->format('Y-m-d')],
                'end_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            ];
        } else {
            $rules += [
                'start_date' => ['required', 'date_format:Y-m-d'],
                'end_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            ];
        }
        return $rules;
    }

    public static function attributes(): array
    {
        $messages = [
            'publish' => _trans('publish'),
            'special_id' => _trans('special name'),
            'country_id' => _trans('country name'),
            'governorate_id' => _trans('governorate name'),
            'region_id' => _trans('region name'),
            'category_id' => _trans('category name'),
            'sub_category_id' => _trans('sub category name'),
            'currency' => _trans('currency name'),
            'lat' => _trans('latitude'),
            'lng' => _trans('longitude'),
            'youtube_video_thumbnail' => _trans('youtube video thumbnail'),
            'youtube_video_url' => _trans('youtube video url'),
        ];
        foreach (locales() as $locale) {
            $messages += [
                $locale . '.title' => _trans('Title') . ' (' . ucfirst($locale) . ')',
                $locale . '.description' => _trans('Description') . ' (' . ucfirst($locale) . ')',
                $locale . '.content' => _trans('Content') . ' (' . ucfirst($locale) . ')',
                $locale . '.address' => _trans('Address') . ' (' . ucfirst($locale) . ')',
                $locale . '.seo_title' => _trans('SEO Title') . ' (' . ucfirst($locale) . ')',
                $locale . '.seo_description' => _trans('SEO Description') . ' (' . ucfirst($locale) . ')',
            ];
        }
        return $messages;
    }
}
