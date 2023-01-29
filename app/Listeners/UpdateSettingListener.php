<?php

namespace App\Listeners;

use App\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class UpdateSettingListener
{
    public function handle($event): void
    {
        $data = Setting::query()->get();

        $settings = [
            "icon" => "",
            "web_logo" => "",
            "mobile_logo" => "",
            "favicon" => "",
            "company_email" => "",
            "phone" => "",
            "country" => "",
            "city" => "",
            "state" => "",
            "address" => "",
            "business_address_1" => "",
            "business_address_2" => "",
            "timezone" => "Africa/Cairo",
            "pagination_limit" => 10,
            "company_copyright_text" => "",
            "latitude" => "",
            "longitude" => "",
            "currency" => "",
            "currency_symbol" => "",
            "date_format" => "",
            "link_google_play" => "google",
            "link_apple_store" => "apple_store",
            "link_website" => "waleed",
            "google_maps_api" => "AIzaSyB6w0wn1qVvvBCJoyI0Bu46g6vP1SKk_SM", //  AIzaSyBGNB1noDBHyu5VfUKONwJATgyFA-8Mkv4
            "radius" => 400,
            "FCM_SERVER_KEY" => 'AAAA3O5pj9Q:APA91bHIDxWlt61Yq4j2kS4tLjG5hrobI46ba8S4JcLFJeEK05lNTJBXKP0B7T9lpejfW_2dcqw4_3zHBRV2n5RMtodUE2qfdXAPROHX66Mgkgac49-oPhrc6O4G_rLqOPvFlcMrbekV',
            "FCM_SERVER_URL" => 'https://fcm.googleapis.com/fcm/send',
            "grace_period" => 3,
            "version_android" => 0,
            "version_ios" => 0,
            "must_user_check_android_version" => "no",
            "must_user_check_ios_version" => "no",
            "the_number_of_allowed_answers_to_the_question" => 5,
        ];

        foreach ($data as $row) {
            $settings[$row->key] = $row->value;
        }
        $lang = [];
        foreach (locales() as $locale) {
            $lang [] = [
                'company_name_' . $locale,
                'meta_keywords_' . $locale,
                'meta_description_' . $locale,
                'author_' . $locale,
            ];
        }
        foreach (Arr::flatten($lang) as $value) {
            $settings += [
                $value => ''
            ];
        }
        $file = 'site.json';
        $destinationPath = storage_path() . "/setting/";
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
            File::put($destinationPath . $file, json_encode($settings));
        }
        File::put($destinationPath . $file, json_encode($settings));
    }
}
