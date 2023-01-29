<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use Propaganistas\LaravelPhone\PhoneNumber;

class InfoUserResource extends JsonResource
{
    public function toArray($request): array
    {
        $image = asset('assets/images/male.jpeg');
        if ($this->userable?->gender == 'female') {
            $image = asset('assets/images/female.jpeg');
        }
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ? $this->phone($this->phone, $this->country) : null,
            'avatar' => $this->avatar ? getAvatar($this->avatar) : $image,
            'gender' => $this->gender,
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
