<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Country\CountryResource;
use App\Http\Resources\Governorate\GovernorateResource;
use App\Http\Resources\Region\RegionResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;
use Propaganistas\LaravelPhone\PhoneNumber;

class UserResource extends JsonResource
{

    public function toArray($request): array
    {
        $routes = [
            'api.register',
            'api.login',
            'api.check-social',
            'api.login-social',
        ];
        $image = asset('assets/images/male.jpeg');
        if ($this->userable?->gender == 'female') {
            $image = asset('assets/images/female.jpeg');
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ? $this->phone($this->phone, $this->country) : null,
            'avatar' => $this->avatar ? getAvatar($this->avatar) : $image,
            'country' => CountryResource::make($this->country),
            'governorate' => GovernorateResource::make($this->governorate),
            'region' => RegionResource::make($this->region),
            'address' => $this->address,
            'gender' => $this->gender,
            $this->mergeWhen(in_array(Route::currentRouteName(), $routes), [
                'token' => 'Bearer ' . $this->token,
            ]),
        ];
    }

    public function phone($phone, $country): string
    {
        if ($country) {
            return PhoneNumber::make($phone, $country->code)->formatForMobileDialingInCountry($country->code);
        }
        return $phone;
    }
}
