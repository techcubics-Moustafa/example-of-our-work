<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class CountryCode implements Rule
{

    public function passes($attribute, $value): bool
    {
        $codes = File::get(base_path('database/seeders/egypt/CountryCodes.json'));
        $codes = json_decode($codes, true);

        $filtered = Arr::first($codes, function ($code) use ($value) {
            return $code['code'] == $value ? $code : null;
        });
        if ($filtered) {
            return true;
        } else {
            return false;
        }
    }


    public function message(): string
    {
        return _trans('Please country code not exists');
    }
}
