@extends('layouts.master')
@section('title',_trans('Setting'))
@section('content')

    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ _trans('Setting') }}</h3>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid default-dash">
        <div class="row">
            <div class="col-12 col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </a></li>
                    <li class="breadcrumb-item active">{{ _trans('Settings') }} </li>
                </ol>
            </div>
            <div class="col-md-12">
                <ul class="nav nav-tabs nav-settings" id="myTab" role="tablist">
                    <li class="nav-item"><a class="nav-link active" id="setting-site-tab" data-bs-toggle="tab" href="#setting-site"
                                            role="tab" aria-controls="home" aria-selected="true">{{ _trans('Site Setting') }}</a>
                    </li>

                    <li class="nav-item"><a class="nav-link" id="email-setting-tabs" data-bs-toggle="tab" href="#email-setting" role="tab"
                                            aria-controls="email-setting" aria-selected="false">{{ _trans('Mailer Settings') }}</a></li>
                    <li class="nav-item"><a class="nav-link" id="pusher-setting-tab" data-bs-toggle="tab" href="#pusher-setting"
                                            role="tab" aria-controls="pusher-setting" aria-selected="false">{{ _trans('Pusher Settings') }}</a></li>
                </ul>
                <div class="tab-content" id="myTabContent">

                    <div class="tab-pane fade show active" id="setting-site" role="tabpanel" aria-labelledby="setting-site-tab">
                        <br>
                        <div class="col-md-12">
                            {{ Form::open(['route' => 'admin.setting.store','method' => 'POST','files' => true,'class' => 'form mb-15','id' =>'setting_form']) }}

                            <div class="row g-3">

                                <div class="col-md-3">
                                    <div class="form-group form-image">
                                        <label class="font-md">{{ _trans('Web logo') }}</label>
                                        <img src="{{ getAvatar($setting['web_logo']) }}"
                                             class="mb-2 web_logo-preview">
                                        <input id="web_logo"
                                               type="file"
                                               name="web_logo"
                                               class="form-control web_logo @error('web_logo') is-invalid @enderror">
                                        @error('web_logo')
                                        <span class="text-danger">{!! $message !!} </span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="col-md-3">
                                    <div class="form-group form-image">
                                        <label class="font-md">{{ _trans('Mobile logo') }}</label>
                                        <img src="{{ getAvatar($setting['mobile_logo']) }}"
                                             class="mb-2 mobile_logo-preview">
                                        <input id="mobile_logo"
                                               type="file"
                                               name="mobile_logo"
                                               class="form-control mobile_logo @error('mobile_logo') is-invalid @enderror">
                                        @error('mobile_logo')
                                        <span class="text-danger">{!! $message !!} </span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="col-md-3">
                                    <div class="form-group form-image">
                                        <label class="font-md">{{ _trans('Icon') }}</label>
                                        <img src="{{ getAvatar($setting['icon'])}}"
                                             class="mb-2 icon-preview">
                                        <input id="icon"
                                               type="file"
                                               name="icon"
                                               class="form-control icon @error('icon') is-invalid @enderror">
                                        @error('icon')
                                        <span class="text-danger">{!! $message !!} </span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="col-md-3">
                                    <div class="form-group form-image">
                                        <label class="font-md">{{ _trans('Favicon') }}</label>
                                        <img src="{{ getAvatar($setting['favicon'])  }}"
                                             class="mb-2 favicon-preview">
                                        <input id="favicon"
                                               type="file"
                                               name="favicon"
                                               class="form-control favicon @error('favicon') is-invalid @enderror">
                                        @error('favicon')
                                        <span class="text-danger">{!! $message !!} </span>
                                        @enderror
                                    </div>

                                </div>

                                @foreach($languages as $lang)
                                    <div class="col-md-6">
                                        <label class="form-label" for="author_{{ $lang['code'] }}">{{ _trans('Author name') }} ({{ ucfirst($lang['code']) }})</label>
                                        <input id="author_{{ $lang['code'] }}"
                                               type="text"
                                               name="author_{{ $lang['code'] }}"
                                               class="form-control @error('author_'.$lang['code']) is-invalid @enderror"
                                               placeholder="{{ _trans('Author name') }} ({{ ucfirst($lang['code']) }})"
                                               value="{{ old('author_'.$lang['code'],Arr::exists($setting,'author_'.$lang['code']) ?$setting['author_'.$lang['code']] : null ) }}"
                                        >
                                        @error('author_'.$lang['code'])
                                        <span class="text-danger">{!! $message !!} </span>
                                        @enderror
                                    </div>
                                @endforeach

                                @foreach($languages as $lang)
                                    <div class="col-md-6">
                                        <label class="form-label" for="company_name_{{ $lang['code'] }}">{{ _trans('Company name') }} ({{ ucfirst($lang['code']) }})</label>
                                        <input id="company_name_{{ $lang['code'] }}"
                                               type="text"
                                               name="company_name_{{ $lang['code'] }}"
                                               class="form-control @error('company_name_'.$lang['code']) is-invalid @enderror"
                                               placeholder="{{ _trans('Company name') }} ({{ ucfirst($lang['code']) }})"
                                               value="{{ old('company_name_'.$lang['code'],Arr::exists($setting,'company_name_'.$lang['code']) ?$setting['company_name_'.$lang['code']] : null ) }}"
                                        >
                                        @error('company_name_'.$lang['code'])
                                        <span class="text-danger">{!! $message !!} </span>
                                        @enderror
                                    </div>
                                @endforeach

                                @foreach($languages as $lang)
                                    <div class="col-md-6">
                                        <label class="form-label" for="meta_keywords_{{ $lang['code'] }}">{{ _trans('Meta Keywords') }} ({{ ucfirst($lang['code']) }})</label>
                                        <textarea id="meta_keywords_{{ $lang['code'] }}"
                                                  class="form-control @error('meta_keywords_'.$lang['code']) is-invalid @enderror"
                                                  name="meta_keywords_{{ $lang['code'] }}"
                                                  placeholder="{{ _trans('Meta Keywords') }} ({{ ucfirst($lang['code']) }})"
                                        >{{ old('meta_keywords_'.$lang['code'],Arr::exists($setting,'meta_keywords_'.$lang['code']) ? $setting['meta_keywords_'.$lang['code']] : null) }}</textarea>
                                        @error('meta_keywords_'.$lang['code'])
                                        <span class="text-danger">{!! $message !!} </span>
                                        @enderror
                                    </div>
                                @endforeach

                                @foreach($languages as $lang)
                                    <div class="col-md-6">
                                        <label class="form-label" for="meta_description_{{ $lang['code'] }}">{{ _trans('Meta Description') }} ({{ ucfirst($lang['code']) }})</label>
                                        <textarea id="meta_description_{{ $lang['code'] }}"
                                                  class="form-control @error('meta_description_'.$lang['code']) is-invalid @enderror"
                                                  name="meta_description_{{ $lang['code'] }}"
                                                  placeholder="{{ _trans('Meta Description') }} ({{ ucfirst($lang['code']) }})"
                                        >{{ old('meta_description_'.$lang['code'],Arr::exists($setting,'meta_description_'.$lang['code']) ? $setting['meta_description_'.$lang['code']] : null) }}</textarea>
                                        @error('meta_description_'.$lang['code'])
                                        <span class="text-danger">{!! $message !!} </span>
                                        @enderror
                                    </div>
                                @endforeach

                                <div class="col-md-4">
                                    <label class="form-label" for="company_email">{{ _trans('Company email') }}</label>
                                    <input id="company_email"
                                           type="email"
                                           name="company_email"
                                           class="form-control @error('company_email') is-invalid @enderror"
                                           placeholder="{{ _trans('Company email') }}"
                                           value="{{ old('company_email',$setting['company_email']) }}"
                                    >
                                    @error('company_name')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="phone">{{ _trans('Phone') }}</label>
                                    <input id="phone"
                                           type="text"
                                           name="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           placeholder="{{ _trans('Enter') }} {{ _trans('Phone') }}"
                                           value="{{ old('phone',$setting['phone']) }}"
                                    >
                                    @error('phone')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="city">{{ _trans('Country name') }}</label>
                                    <input id="country"
                                           type="text"
                                           name="country"
                                           class="form-control @error('country') is-invalid @enderror"
                                           placeholder="{{ _trans('Enter') }} {{ _trans('Country name') }}"
                                           value="{{ old('country',$setting['country']) }}"
                                    >
                                    @error('country')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="city">{{ _trans('City name') }}</label>
                                    <input id="city"
                                           type="text"
                                           name="city"
                                           class="form-control @error('city') is-invalid @enderror"
                                           placeholder="{{ _trans('Enter') }} {{ _trans('City name') }}"
                                           value="{{ old('city',$setting['city']) }}"
                                    >
                                    @error('city')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="state">{{ _trans('State name') }}</label>
                                    <input id="state"
                                           type="text"
                                           name="state"
                                           class="form-control @error('state') is-invalid @enderror"
                                           placeholder="{{ _trans('Enter') }} {{ _trans('State name') }}"
                                           value="{{ old('state',$setting['state']) }}"
                                    >
                                    @error('state')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="address">{{ _trans('Address') }}</label>
                                    <input id="address"
                                           type="text"
                                           name="address"
                                           class="form-control @error('address') is-invalid @enderror"
                                           placeholder="{{ _trans('Enter') }} {{ _trans('Address') }}"
                                           value="{{ old('address',$setting['address']) }}"
                                    >
                                    @error('address')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="business_address_1">{{ _trans('Business address 1') }}</label>
                                    <input id="business_address_1"
                                           type="text"
                                           name="business_address_1"
                                           class="form-control @error('business_address_1') is-invalid @enderror"
                                           placeholder="{{ _trans('Business address 1') }}"
                                           value="{{ old('business_address_1',$setting['business_address_1']) }}"
                                    >
                                    @error('business_address_1')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="business_address_2">{{ _trans('Business address 2') }}</label>
                                    <input id="business_address_2"
                                           type="text"
                                           name="business_address_2"
                                           class="form-control @error('business_address_2') is-invalid @enderror"
                                           placeholder="{{ _trans('Business address 2') }}"
                                           value="{{ old('business_address_2',$setting['business_address_2']) }}"
                                    >
                                    @error('business_address_2')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="timezone">{{ _trans('Timezone') }}</label>
                                    <small class="text-xs">
                                        {{ _trans('Note: Add TimeZone code as per three-letter ISO code.') }}
                                        <a href="https://www.php.net/manual/en/timezones.php" target="_blank">{{ _trans('you can find out here..') }}</a>
                                    </small>
                                    <input id="timezone"
                                           type="text"
                                           name="timezone"
                                           class="form-control @error('timezone') is-invalid @enderror"
                                           placeholder="{{ _trans('Timezone') }}"
                                           value="{{ old('timezone',!empty($setting['timezone']) ? $setting['timezone'] : config('app.timezone')) }}"
                                    >
                                    @error('timezone')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="pagination_limit">{{ _trans('Pagination Settings') }}</label>
                                    <input id="pagination_limit"
                                           type="number"
                                           min="1"
                                           step="1"
                                           name="pagination_limit"
                                           class="form-control @error('pagination_limit') is-invalid @enderror"
                                           placeholder="{{ _trans('Pagination Settings') }}"
                                           value="{{ old('pagination_limit',$setting['pagination_limit']) }}"
                                    >
                                    @error('pagination_limit')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="radius">{{ _trans('Restaurant radius') }}</label>
                                    <input id="radius"
                                           type="number"
                                           min="1"
                                           step="any"
                                           name="radius"
                                           class="form-control @error('radius') is-invalid @enderror"
                                           placeholder="{{ _trans('Restaurant radius') }}"
                                           value="{{ old('radius',$setting['radius']) }}"
                                    >
                                    @error('radius')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="company_copyright_text">{{ _trans('Rename company Copy right Text') }}</label>
                                    <input id="company_copyright_text"
                                           type="text"
                                           name="company_copyright_text"
                                           class="form-control @error('company_copyright_text') is-invalid @enderror"
                                           placeholder="{{ _trans('Rename company Copy right Text') }}"
                                           value="{{ old('company_copyright_text',$setting['company_copyright_text']) }}"
                                    >
                                    @error('company_copyright_text')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="latitude">{{ _trans('Latitude') }}</label>
                                    <input id="latitude"
                                           type="text"
                                           name="latitude"
                                           class="form-control @error('latitude') is-invalid @enderror"
                                           placeholder="{{ _trans('Latitude') }}"
                                           value="{{ old('latitude',$setting['latitude']) }}"
                                    >
                                    @error('latitude')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="longitude">{{ _trans('Longitude') }}</label>
                                    <input id="longitude"
                                           type="text"
                                           name="longitude"
                                           class="form-control @error('longitude') is-invalid @enderror"
                                           placeholder="{{ _trans('longitude') }}"
                                           value="{{ old('longitude',$setting['longitude']) }}"
                                    >
                                    @error('longitude')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="currency">{{ _trans('Currency') }}</label>
                                    <input id="currency"
                                           type="text"
                                           name="currency"
                                           class="form-control @error('currency') is-invalid @enderror"
                                           placeholder="{{ _trans('Currency') }}"
                                           value="{{ old('currency',$setting['currency']) }}"
                                    >
                                    <small class="text-xs">
                                        {{ _trans('Note: Add currency code as per three-letter ISO code.') }}
                                        <a href="https://stripe.com/docs/currencies" target="_blank">{{ _trans('you can find out here..') }}</a>
                                    </small>
                                    @error('currency')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="currency_symbol">{{ _trans('Currency Symbol') }}</label>
                                    <input id="currency_symbol"
                                           type="text"
                                           name="currency_symbol"
                                           class="form-control @error('currency_symbol') is-invalid @enderror"
                                           placeholder="{{ _trans('Currency Symbol') }}"
                                           value="{{ old('currency_symbol',$setting['currency_symbol']) }}"
                                    >
                                    @error('currency_symbol')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="link_google_play">{{ _trans('Link Google play') }}</label>
                                    <input id="link_google_play"
                                           type="text"
                                           name="link_google_play"
                                           class="form-control @error('link_google_play') is-invalid @enderror"
                                           placeholder="{{ _trans('Link google play') }}"
                                           value="{{ old('link_google_play',$setting['link_google_play']) }}"
                                    >
                                    @error('link_google_play')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="version_android">{{ _trans('Version Android') }}</label>
                                    <input id="version_android"
                                           type="text"
                                           name="version_android"
                                           class="form-control @error('version_android') is-invalid @enderror"
                                           placeholder="{{ _trans('Version Android') }}"
                                           value="{{ old('version_android',$setting['version_android']) }}"
                                    >
                                    @error('version_android')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="must_user_check_android_version">{{ _trans('Must user check android version') }}</label>
                                    <select id="must_user_check_android_version" name="must_user_check_android_version"
                                            class="js-example-basic-single @error('must_user_check_android_version') is-invalid @enderror">
                                        <option value="">{{ _trans('Select update android version') }}</option>
                                        @foreach(\App\Enums\Approve::cases() as $key => $value)
                                            <option value="{{ $value->value }}" @selected(old('must_user_check_android_version', $setting['must_user_check_android_version'] ) == $value->value)>{{ _trans($value->name) }}</option>
                                        @endforeach
                                    </select>
                                    @error('must_user_check_android_version')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="link_apple_store">{{ _trans('Link Apple store') }}</label>
                                    <input id="link_apple_store"
                                           type="text"
                                           name="link_apple_store"
                                           class="form-control @error('link_apple_store') is-invalid @enderror"
                                           placeholder="{{ _trans('Link apple store') }}"
                                           value="{{ old('link_apple_store',$setting['link_apple_store']) }}"
                                    >
                                    @error('link_apple_store')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="version_ios">{{ _trans('Version IOS') }}</label>
                                    <input id="version_ios"
                                           type="text"
                                           name="version_ios"
                                           class="form-control @error('version_ios') is-invalid @enderror"
                                           placeholder="{{ _trans('Version IOS') }}"
                                           value="{{ old('version_ios',$setting['version_ios']) }}"
                                    >
                                    @error('version_ios')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="must_user_check_ios_version">{{ _trans('Must user check ios version') }}</label>
                                    <select id="must_user_check_ios_version" name="must_user_check_ios_version"
                                            class="js-example-basic-single @error('must_user_check_ios_version') is-invalid @enderror">
                                        <option value="">{{ _trans('Select update ios version') }}</option>
                                        @foreach(\App\Enums\Approve::cases() as $key => $value)
                                            <option value="{{ $value->value }}" @selected(old('must_user_check_ios_version', $setting['must_user_check_ios_version'] ) == $value->value)>{{ _trans($value->name) }}</option>
                                        @endforeach
                                    </select>
                                    @error('must_user_check_ios_version')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="link_website">{{ _trans('Link Website') }}</label>
                                    <input id="link_website"
                                           type="text"
                                           name="link_website"
                                           class="form-control @error('link_website') is-invalid @enderror"
                                           placeholder="{{ _trans('Link website') }}"
                                           value="{{ old('link_website',$setting['link_website']) }}"
                                    >
                                    @error('link_website')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="FCM_SERVER_KEY">{{ _trans('Firebase server key') }}</label>
                                    <input id="FCM_SERVER_KEY"
                                           type="text"
                                           name="FCM_SERVER_KEY"
                                           class="form-control @error('FCM_SERVER_KEY') is-invalid @enderror"
                                           placeholder="{{ _trans('Firebase server key') }}"
                                           value="{{ old('FCM_SERVER_KEY',$setting['FCM_SERVER_KEY']) }}"
                                    >
                                    @error('FCM_SERVER_KEY')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="grace_period">{{ _trans('grace_period') }}</label>
                                    <input id="grace_period"
                                           type="number"
                                           min="1"
                                           step="1"
                                           name="grace_period"
                                           class="form-control @error('grace_period') is-invalid @enderror"
                                           placeholder="{{ _trans('grace_period') }}"
                                           value="{{ old('grace_period',$setting['grace_period']) }}"
                                    >
                                    @error('grace_period')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                {{--<div class="col-md-4">
                                    <label class="form-label" for="FCM_SERVER_URL">{{ _trans('Firebase url') }}</label>
                                    <input id="FCM_SERVER_URL"
                                           type="text"
                                           name="FCM_SERVER_URL"
                                           class="form-control @error('FCM_SERVER_URL') is-invalid @enderror"
                                           placeholder="{{ _trans('Firebase server key') }}"
                                           value="{{ old('FCM_SERVER_URL',$setting['FCM_SERVER_URL']) }}"
                                    >
                                    @error('FCM_SERVER_URL')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>--}}

                                <div class="col-md-4">
                                    <label class="form-label" for="google_maps_api">{{ _trans('Google map api') }}</label>
                                    <input id="google_maps_api"
                                           type="text"
                                           name="google_maps_api"
                                           class="form-control @error('google_maps_api') is-invalid @enderror"
                                           placeholder="{{ _trans('Google map key') }}"
                                           value="{{ old('google_maps_api',$setting['google_maps_api']) }}"
                                    >
                                    @error('google_maps_api')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="">{{ _trans('Date format') }}</label>
                                    <select id="date_format" name="date_format"
                                            class="js-example-basic-single @error('date_format') is-invalid @enderror">
                                        <option value="">{{ _trans('Select date format') }}</option>
                                        @foreach($date as $key => $value)
                                            <option value="{{ $key }}" @selected(old('date_format', $setting['date_format'] ) == $setting['date_format'])>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('date_format')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <div class="m-t-50 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">{{ _trans('Update') }}</button>
                                    </div>
                                </div>

                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="email-setting" role="tabpanel" aria-labelledby="email-setting-tab">
                        <br>
                        <div class="col-md-12">
                            {{ Form::open(['route' => 'admin.setting.email','method' => 'POST','files' => true,'class' => 'form mb-15','id' =>'kt_contact_form']) }}

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="mail_mailer">{{ _trans('Mail Mailer') }}</label>
                                    <input id="mail_mailer"
                                           type="text"
                                           name="mail_mailer"
                                           class="form-control @error('mail_mailer') is-invalid @enderror"
                                           placeholder="{{ _trans('Enter') }} {{ _trans('Mail Mailer') }}"
                                           value="{{ old('mail_mailer',env('MAIL_MAILER')) }}"
                                    >
                                    @error('mail_mailer')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="mail_host">{{ _trans('Mail Host') }}</label>
                                    <input id="mail_host"
                                           type="text"
                                           name="mail_host"
                                           class="form-control @error('mail_host') is-invalid @enderror"
                                           placeholder="{{ _trans('Enter') }} {{ _trans('Mail Host') }}"
                                           value="{{ old('mail_host',env('MAIL_HOST')) }}"
                                    >
                                    @error('mail_host')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="mail_port">{{ _trans('Mail Port') }}</label>
                                    <input id="mail_port"
                                           type="text"
                                           name="mail_port"
                                           class="form-control @error('mail_port') is-invalid @enderror"
                                           placeholder="{{ _trans('Enter') }} {{ _trans('Mail Port') }}"
                                           value="{{ old('mail_port',env('MAIL_PORT')) }}"
                                    >
                                    @error('mail_port')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="mail_username">{{ _trans('Mail Username') }}</label>
                                    <input id="mail_username"
                                           type="text"
                                           name="mail_username"
                                           class="form-control @error('mail_username') is-invalid @enderror"
                                           placeholder="{{ _trans('Enter') }} {{ _trans('Mail Username') }}"
                                           value="{{ old('mail_username',env('MAIL_USERNAME')) }}"
                                    >
                                    @error('mail_username')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="mail_password">{{ _trans('Mail Password') }}</label>
                                    <input id="mail_password"
                                           type="text"
                                           name="mail_password"
                                           class="form-control @error('mail_password') is-invalid @enderror"
                                           placeholder="{{ _trans('Enter') }} {{ _trans('Mail Password') }}"
                                           value="{{ old('mail_password',env('MAIL_PASSWORD')) }}"
                                    >
                                    @error('mail_password')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="mail_encryption">{{ _trans('Mail Encryption') }}</label>
                                    <input id="mail_encryption"
                                           type="text"
                                           name="mail_encryption"
                                           class="form-control @error('mail_encryption') is-invalid @enderror"
                                           placeholder="{{ _trans('Enter') }} {{ _trans('Mail Encryption') }}"
                                           value="{{ old('mail_encryption',env('MAIL_ENCRYPTION')) }}"
                                    >
                                    @error('mail_encryption')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="mail_from_address">{{ _trans('Mail Form Address') }}</label>
                                    <input id="mail_from_address"
                                           type="text"
                                           name="mail_from_address"
                                           class="form-control @error('mail_from_address') is-invalid @enderror"
                                           placeholder="{{ _trans('Enter') }} {{ _trans('Email') }}"
                                           value="{{ old('mail_from_address',env('MAIL_FROM_ADDRESS')) }}"
                                    >
                                    @error('mail_from_address')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <div class="m-t-50 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">{{ _trans('Update') }}</button>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="m-t-50 d-flex justify-content-end">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#test_email" class="btn btn-primary">{{ _trans('Send Test Email') }}</button>
                                    </div>
                                </div>
                            </div>

                            {{ Form::close() }}


                        </div>
                    </div>

                    <div class="tab-pane fade" id="pusher-setting" role="tabpanel" aria-labelledby="pusher-setting-tab">
                        <br>
                        <div class="col-md-12">
                            {{ Form::open(['route' => 'admin.setting.pusher','method' => 'POST','files' => true,'class' => 'form mb-15','id' =>'kt_contact_form']) }}

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="pusher_app_id">{{ _trans('Pusher App ID') }}</label>
                                    <input id="pusher_app_id"
                                           type="text"
                                           name="pusher_app_id"
                                           class="form-control @error('pusher_app_id') is-invalid @enderror"
                                           placeholder="{{ _trans('Enter') }} {{ _trans('Pusher App ID') }}"
                                           value="{{ old('pusher_app_id',env('PUSHER_APP_ID')) }}"
                                    >
                                    @error('pusher_app_id')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="mail_host">{{ _trans('Pusher App Key') }}</label>
                                    <input id="pusher_app_key"
                                           type="text"
                                           name="pusher_app_key"
                                           class="form-control @error('pusher_app_key') is-invalid @enderror"
                                           placeholder="{{ _trans('Enter') }} {{ _trans('Pusher App Key') }}"
                                           value="{{ old('pusher_app_key',env('PUSHER_APP_KEY')) }}"
                                    >
                                    @error('pusher_app_key')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="pusher_app_secret">{{ _trans('Pusher App Secret') }}</label>
                                    <input id="pusher_app_secret"
                                           type="text"
                                           name="pusher_app_secret"
                                           class="form-control @error('pusher_app_secret') is-invalid @enderror"
                                           placeholder="{{ _trans('Enter') }} {{ _trans('Pusher App Secret') }}"
                                           value="{{ old('pusher_app_secret',env('PUSHER_APP_SECRET')) }}"
                                    >
                                    @error('pusher_app_secret')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="pusher_app_cluster">{{ _trans('Pusher App Cluster') }}</label>
                                    <input id="pusher_app_cluster"
                                           type="text"
                                           name="pusher_app_cluster"
                                           class="form-control @error('pusher_app_cluster') is-invalid @enderror"
                                           placeholder="{{ _trans('Enter') }} {{ _trans('Pusher App Cluster') }}"
                                           value="{{ old('pusher_app_cluster',env('PUSHER_APP_CLUSTER')) }}"
                                    >
                                    @error('pusher_app_cluster')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>


                                <div class="col-md-12">
                                    <div class="m-t-50 d-flex justify-content-end">
                                        <button type="submit" id="btn-setting" class="btn btn-primary">{{ _trans('Update') }}</button>
                                    </div>
                                </div>


                            </div>

                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <!-- Container-fluid Ends-->

    <!-- Model forget password -->
    <div class="modal fade" id="test_email" tabindex="-1" role="dialog" aria-labelledby="test_email" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ _trans('Testing Send Mail') }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {{ Form::open(['route' => 'admin.setting.send-mail','method' => 'POST','files' => true,'id' => 'mail_form']) }}

                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label" for="email">{{ _trans('Email Address') }}</label>
                                <input id="email"
                                       type="email"
                                       name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       placeholder="{{ _trans('Enter') }} {{ _trans('Email Address') }}"
                                       value="{{ old('email') }}"
                                >
                                @error('email')
                                <span class="text-danger">{!! $message !!} </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="btn-mail" type="submit">{{ _trans('Send') }}</button>
                </div>

                {{ Form::close() }}

            </div>
        </div>
    </div>

@endsection

@include('layouts.partials.read-photo',['inputName' => 'icon'])
@include('layouts.partials.read-photo',['inputName' => 'web_logo'])
@include('layouts.partials.read-photo',['inputName' => 'mobile_logo'])
@include('layouts.partials.read-photo',['inputName' => 'favicon'])

@include('layouts.ajax.disabled-button-form',[
        'id' => 'setting',
        ])
@include('layouts.ajax.disabled-button-form',[
        'id' => 'mail',
        ])
