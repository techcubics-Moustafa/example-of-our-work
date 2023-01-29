@extends('layouts.master')
@section('title',$edit ? _trans('Edit Country') : _trans('Add Country'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ $edit ? _trans('Edit Country') : _trans('Add Country') }}</h3>
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid default-dash">
        <div class="row">
            <div class="col-md-12">
                <div class="col-12 col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                            </a>
                        </li>
                        <li class="breadcrumb-item ">
                            @canany(['Country list','Country edit','Country delete'])
                                <a href="{{ route('admin.country.index') }}">{{ _trans('Countries') }}</a>
                            @endcanany
                        </li>
                        <li class="breadcrumb-item active">
                            {{ $edit ? _trans('Edit Country') : _trans('Add Country') }}
                        </li>
                    </ol>
                </div>

                @if ($edit)
                    {{ Form::open(['route' => ['admin.country.update',$country->id],'method' => 'PUT','files' => true,'class' => 'form mb-15 form-submit','id' =>'country_form']) }}
                @else
                    {{ Form::open(['route' => 'admin.country.store','method' => 'POST','files' => true,'class' => 'form mb-15','id' =>'country_form']) }}
                @endif

                <div class="row g-4">
                    <div class="col-md-4">
                        <label for="code" class="form-label">{{ _trans('Country Code') }}</label>
                        <input id="code"
                               disabled
                               type="text"
                               class="form-control"
                               placeholder="{{ _trans('Country Code') }}"
                               value="{{ getCodeTable('Count','countries',$edit,$edit ? $country->id : null) }}"
                        >
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="code">{{ _trans('Code') }}</label>
                        <select id="code"
                                name="code"
                                class="js-example-basic-single @error('code') is-invalid @enderror">
                            <option value="">{{ _trans('Select code') }}</option>
                            @foreach($codes as $lang =>  $row)
                                <option value="{{ $row['code'] }}"
                                    @selected(old('code',$edit ? $country->code : null) == $row['code'])>
                                    {{ $row['name'] }} ({{ $row['dial_code'] }}) ({{ $row['code'] }})
                                </option>
                            @endforeach
                        </select>
                        @error('code')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="currency_id">{{ _trans('Currency') }}</label>
                        <select id="currency_id" name="currency_id"
                                class="js-example-basic-single @error('currency_id') is-invalid @enderror">
                            <option value="">{{ _trans('Select Currency') }}</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}"
                                    @selected(old('currency_id',$edit ? $country->currency_id : null) == $currency->id)>
                                    {{ $currency->translateOrDefault(locale())?->name }}</option>
                            @endforeach
                        </select>
                        @error('currency_id')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    @foreach($languages as $lang)
                        <div class="col-md-6">
                            <label for="{{ $lang['code'] }}[name]" class="form-label">{{ _trans('Country').' '._trans('Name') }} ({{ ucfirst($lang['code']) }})</label>
                            <input id="{{ $lang['code'] }}[name]"
                                   type="text"
                                   name="{{ $lang['code'] }}[name]"
                                   class="form-control @error($lang['code'].'.name') is-invalid @enderror"
                                   placeholder="{{ _trans('Country name') }} ({{ ucfirst($lang['code']) }})"
                                   value="{{ old($lang['code'].'.name',$edit ? $country->translateOrDefault($lang['code'])?->name : null) }}"
                                   required="">
                            @error($lang['code'].'.name')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>

                    @endforeach
                    @foreach($languages as $lang)
                        <div class="col-md-6">
                            <label for="{{ $lang['code'] }}[nationality]" class="form-label">{{ _trans('Nationality name') }} ({{ ucfirst($lang['code']) }})</label>
                            <input id="{{ $lang['code'] }}[nationality]"
                                   type="text"
                                   name="{{ $lang['code'] }}[nationality]"
                                   class="form-control @error($lang['code'].'.name') is-invalid @enderror"
                                   placeholder="{{ _trans('Nationality name')}} ({{ ucfirst($lang['code']) }})"
                                   value="{{ old($lang['code'].'.nationality',$edit ? $country->translateOrDefault($lang['code'])?->nationality : null) }}"
                                   required="">
                            @error($lang['code'].'.nationality')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>
                    @endforeach

                    <div class="col-md-6">
                        <div class="mb-1">
                            <label class="form-label">{{ _trans('Flag') }}</label>
                        </div>
                        <div class="p-2 border border-dashed" style="max-width:230px;">
                            <div class="row" id="icon"></div>
                        </div>
                        @error('icon')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <div class="m-t-50 d-flex justify-content-end">
                            <button type="submit" id="btn-country" class="btn btn-primary">{{ $edit ?_trans('Update') : _trans('Save') }}</button>
                        </div>
                    </div>
                </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/spartan-multi-image-picker.js') }}"></script>
    @include('layouts.partials.spartan-multi-image',[
    'single' => true,
    'file_name' => 'icon',
    'image' => $edit ? getAvatar($country->icon) :  asset('assets/images/img/400x400/img2.jpg')
    ])

@endpush

@include('layouts.ajax.disabled-button-form',[
        'id' => 'country',
        ])
