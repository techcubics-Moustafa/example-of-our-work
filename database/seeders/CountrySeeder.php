<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Currency;
use App\Models\Governorate;
use App\Models\Region;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{

    private $governorates;

    public function __construct()
    {
        $jsonString = file_get_contents(__DIR__ . '/egypt/data.json');
        $governorates = json_decode($jsonString, true);
        $this->governorates = $governorates;
    }

    public function run()
    {
        $currencies = [
            'code' => 'EGP',
            'name:ar' => 'جنيه مصري',
            'name:en' => 'Egyptian Pounds',
        ];
        $currency = Currency::query()->updateOrCreate([
            'code' => 'EGP'
        ], $currencies);

        $countries = [
            'code' => 'EG',
            'name:ar' => 'مصر',
            'name:en' => 'Egypt',
            'nationality:ar' => 'مصرى',
            'nationality:en' => 'Egyptian',
            'currency_id' => $currency->id,
        ];

        $country = Country::query()->updateOrCreate([
            'code' => 'EG'
        ], $countries);

        $locales = locales();

        $governorates = $this->governorates;

        $formatDateGovernorates = [];
        $formatDateRegion = [];
        foreach ($governorates as $key => $governorate) {
            $formatDateGovernorates[$key] = [
                'country_id' => $country->id
            ];
            foreach ($locales as $locale) {
                $formatDateGovernorates[$key] += [
                    'name:' . $locale => $governorate['name_' . $locale],
                ];
            }

            $gov = Governorate::query()->create($formatDateGovernorates[$key]);

            foreach ($governorate['subregions'] as $item => $subregions) {
                $formatDateRegion[$item] = [
                    'country_id' => $country->id,
                    'governorate_id' => $gov->id
                ];
                foreach ($locales as $locale) {
                    $formatDateRegion[$item] += [
                        'name:' . $locale => $subregions['name_' . $locale],
                    ];
                }
                Region::query()->create($formatDateRegion[$item]);
            }

        }


    }
}
