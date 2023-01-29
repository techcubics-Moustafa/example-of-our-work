<?php

namespace App\Rules;

use App\Enums\Status;
use App\Models\RealEstate;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Str;

class LikeRule implements InvokableRule
{
    public function __construct(public $modelableType)
    {
    }

    public function __invoke($attribute, $value, $fail)
    {
        $headline = Str::headline($this->modelableType);
        $class = "App\\Models\\" . Str::replace(' ', '', $headline);
        if ($class == RealEstate::class) {
            $model = $class::query()
                ->where([
                    'publish' => Status::Active->value,
                    'id' => $value,
                ])->first();
        } else {
            $model = $class::query()
                ->where([
                    'id' => $value,
                ])->first();
        }
        if (!$model) {
            $fail(_trans("Not found this {$this->modelableType}"));
        }
    }
}
