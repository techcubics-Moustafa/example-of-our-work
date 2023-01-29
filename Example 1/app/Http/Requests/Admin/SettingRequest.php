<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onSaveSettingSite(): array
    {
        $rules = [
            'icon' => array_merge(['nullable'], validationImage()),
            'web_logo' => array_merge(['nullable'], validationImage()),
            'mobile_logo' => array_merge(['nullable'], validationImage()),
            'favicon' => array_merge(['nullable'], validationImage()),
            'company_email' => ['required', 'string', 'email', 'min:2', 'max:255'],
            'phone' => ['required', 'string', 'min:2', 'max:255'],
            'country' => ['nullable', 'string', 'min:2', 'max:255'],
            'city' => ['nullable', 'string', 'min:2', 'max:255'],
            'state' => ['nullable', 'string', 'min:2', 'max:255'],
            'address' => ['nullable', 'string', 'min:2', 'max:255'],
            'business_address_1' => ['nullable', 'string', 'min:2', 'max:255'],
            'business_address_2' => ['nullable', 'string', 'min:2', 'max:255'],
            'timezone' => ['required', 'string', 'min:2', 'max:255', 'timezone'],
            'pagination_limit' => ['required', 'integer'],
            'company_copyright_text' => ['required', 'string', 'min:2', 'max:255'],
            'latitude' => ['nullable', 'numeric',],
            'longitude' => ['nullable', 'numeric',],
            'currency' => ['nullable', 'string', 'min:2', 'max:255'],
            'currency_symbol' => ['nullable', 'string', 'min:2', 'max:255'],
            'link_google_play' => ['required', 'string', 'url', 'min:2', 'max:255'],
            'link_apple_store' => ['required', 'string', 'url', 'min:2', 'max:255'],
            'link_website' => ['required', 'string', 'url', 'min:2', 'max:255'],
            'google_maps_api' => ['required', 'string', 'min:2', 'max:255'],
            'FCM_SERVER_KEY' => ['required', 'string', 'min:2'],
            'date_format' => ['nullable', 'string', 'min:2', 'max:255'],
            'radius' => ['required', 'numeric'],
            'grace_period' => ['required', 'integer', 'min:1'],
            'version_android' => ['required', 'numeric', 'min:0',],
            'version_ios' => ['required', 'numeric', 'min:0',],
            'must_user_check_android_version' => ['required', 'string', 'in:yes,no',],
            'must_user_check_ios_version' => ['required', 'string', 'in:yes,no',],
            'the_number_of_allowed_answers_to_the_question' => ['required', 'integer', 'min:0'],
        ];
        foreach (locales() as $locale) {
            $rules += [
                'company_name_' . $locale => ['required', 'string', 'min:2', 'max:255'],
                'author_' . $locale => ['required', 'string', 'min:2', 'max:255'],
                'meta_keywords_' . $locale => ['required', 'string', 'min:2'],
                'meta_description_' . $locale => ['required', 'string', 'min:2'],
            ];
        }
        return $rules;
    }

    protected function onSaveEmailSettings(): array
    {
        return [
            'mail_mailer' => ['required', 'string', 'max:50'],
            'mail_host' => ['required', 'string', 'max:50'],
            'mail_port' => ['required', 'integer'],
            'mail_username' => ['required', 'string', 'max:50'],
            'mail_password' => ['required', 'string', 'max:50'],
            'mail_encryption' => ['required', 'string', 'max:50'],
            'mail_from_address' => ['required', 'string', 'max:50'],
        ];
    }

    protected function onSavePusherSettings(): array
    {
        return [
            'pusher_app_id' => ['required', 'string', 'min:1', 'max:255'],
            'pusher_app_key' => ['required', 'string', 'min:1', 'max:255'],
            'pusher_app_secret' => ['required', 'string', 'min:1', 'max:255'],
            'pusher_app_cluster' => ['required', 'string', 'min:1', 'max:255'],
        ];
    }


    public function rules(): array
    {
        if (request()->routeIs('admin.setting.store')) {
            return $this->onSaveSettingSite();
        } elseif (request()->routeIs('admin.setting.email')) {
            return $this->onSaveEmailSettings();
        } elseif (request()->routeIs('admin.setting.pusher')) {
            return $this->onSavePusherSettings();
        } else {
            return [];
        }
    }

    public function attributes()
    {
        $messages = [];
        foreach (locales() as $locale) {
            $messages += [
                'company_name_' . $locale => _trans('Company name') . ' (' . ucfirst($locale) . ')',
                'author_' . $locale => _trans('author name') . ' (' . ucfirst($locale) . ')',
                'meta_keywords_' . $locale => _trans('Meta keywords') . ' (' . ucfirst($locale) . ')',
                'meta_description_' . $locale => _trans('Meta description') . ' (' . ucfirst($locale) . ')',
            ];
        }
        return $messages;
    }
}
