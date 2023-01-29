<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\Country;
use App\Models\Governorate;
use App\Models\User;
use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class UserSeeder extends Seeder
{

    public function run()
    {
        $country = Country::query()->status()->pluck('id')->toArray();
        $data = [
            'country_id' => Arr::random($country),
        ];
        $governorate = Governorate::query()->whereCountryId($data['country_id'])->status()->pluck('id')->toArray();
        $data += [
            'governorate_id' => Arr::random($governorate),
        ];
        $regions = Region::query()->whereGovernorateId($data['governorate_id'])->status()->pluck('id')->toArray();
        $data += [
            'region_id' => Arr::random($regions),
        ];
        $user1 = [
            'user_type' => UserType::Company->value,
            'first_name' => 'waleed',
            'last_name' => 'said',
            'email' => 'w1@gmail.com',
            'phone' => '+201096981025',
            'password' => '123456789',
            'address' => 'welcome',
        ];
        User::query()->create(array_merge($data, $user1));


        $user2 = [
            'user_type' => UserType::Individual->value,
            'first_name' => 'ahmed',
            'last_name' => 'kamel',
            'email' => 'a1@gmail.com',
            'phone' => '+201096981024',
            'password' => '123456789',
            'address' => 'welcome',
        ];
        User::query()->create(array_merge($data, $user2));
    }
}
