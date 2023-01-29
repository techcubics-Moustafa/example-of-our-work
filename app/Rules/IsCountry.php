<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

class IsCountry implements Rule
{
    public $countryId;
    public $model;

    public function __construct($countryId, $model)
    {
        $this->countryId = $countryId;
        $this->model = $model;
    }

    public function passes($attribute, $value): bool
    {
        $model = $this->model->whereRelation('clinic', 'country_id', '=', $this->countryId)->find($value);
        if ($model) {
            return true;
        }
        return false;
    }


    public function message(): string
    {
        return _trans('Country name not match');
    }
}
