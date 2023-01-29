<?php

namespace App\Http\Middleware;

use App\Enums\Status;
use App\Traits\Api\ApiResponses;
use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CountryId
{
    use ApiResponses;

    public function handle($request, Closure $next)
    {
        $country = request()->header('country-id');
        $request->merge(['country_id' => (int)$country]);
        $validator = Validator::make($request->all(), [
            'country_id' => ['required', 'integer', Rule::exists('countries', 'id')
                ->where('status', Status::Active->value)
            ],
        ], customAttributes: [
            'country_id' => 'country-id'
        ]);

        if ($validator->fails()) {
            return $this->failure(message: $validator->errors()->first());
        }
        return $next($request);
    }
}
