<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Propaganistas\LaravelPhone\PhoneNumber;

class FormatPhone implements Rule
{
    // Format
    public function __construct(public $code, public $model, public $modelId = null, public $exist = false)
    {
        //
    }

    public function passes($attribute, $value): bool
    {
        $phone = (string)PhoneNumber::make($value, $this->code);

        $exists = $this->model->where(str_replace('owner_', '', $attribute), '=', $phone);
        // use ignore row in same model
        if ($this->modelId) {
            $exists = $exists->where('id', '!=', $this->modelId);
        }
        // check is exists or not
        if ($this->exist) {
            $exists = $exists->first();
            if ($exists) {
                return true;
            } else {
                return false;
            }
        }
        $exists = $exists->first();
        //dd($attribute,$value,$exists,!$exists);
        return !$exists;
    }

    public function message(): string
    {
        return _trans('Phone Has already been taken.');
    }
}
