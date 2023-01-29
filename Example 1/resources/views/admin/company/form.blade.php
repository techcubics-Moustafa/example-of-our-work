@extends('layouts.master')

@section('title',$edit ? _trans('Edit Company') : _trans('Add Company'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ $edit ? _trans('Edit Company') : _trans('Add Company') }}</h3>
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid default-dash">
        <div class="row">
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
                    @canany(['Company list','Company edit','Company delete'])
                        <li class="breadcrumb-item ">
                            <a href="{{ route('admin.company.index') }}">{{ _trans('Companies') }}</a>
                        </li>
                    @endcanany
                    <li class="breadcrumb-item active">
                        {{ $edit ? _trans('Edit Company') : _trans('Add Company') }}
                    </li>
                </ol>
            </div>

            <div class="col-md-12">
                @if ($edit)
                    {{ Form::open(['route' => ['admin.company.update',$company->id],'method' => 'PUT','files' => true,'class' => 'form mb-15 form-submit','id' =>'company_form']) }}
                @else
                    {{ Form::open(['route' => 'admin.company.store','method' => 'POST','files' => true,'class' => 'form mb-15','id' =>'company_form']) }}
                @endif

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label" for="code">{{ _trans('Company Code') }}</label>
                        <input id="code"
                               disabled
                               type="text"
                               class="form-control"
                               placeholder="{{ _trans('Enter') }} {{ _trans('Company Code') }}"
                               value="{{ getCodeTable('Company','companies',$edit,$edit ? $company->id : null) }}"
                        >
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="user_id">
                            {{ _trans('User name') }}
                            @can('User add')
                                <a href="{{ route('admin.user.create') }}" target="_blank" class="btn-sm btn-primary">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                </a>
                            @endcan
                        </label>
                        <select id="user_id" name="user_id"
                                class="js-example-basic-single @error('user_id') is-invalid @enderror">
                            <option value="">{{ _trans('Select user name') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(old('user_id',$edit ? $company->user_id : null) == $user->id)>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="email">{{ _trans('E-mail') }}</label>
                        <input id="email"
                               type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="{{ _trans('E-mail') }}"
                               value="{{ old('email',$edit ? $company->email : null) }}"
                        >
                        @error('email')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    @foreach($languages as $lang)
                        <div class="col-md-6">
                            <label for="{{ $lang['code'] }}[name]" class="form-label">{{ _trans('Company name') }} ({{ ucfirst($lang['code']) }})</label>
                            <input id="{{ $lang['code'] }}[name]"
                                   type="text"
                                   name="{{ $lang['code'] }}[name]"
                                   class="form-control @error($lang['code'].'.name') is-invalid @enderror"
                                   placeholder="{{ _trans('Enter') }} {{ _trans('Company name') }} ({{ ucfirst($lang['code']) }})"
                                   value="{{ old($lang['code'].'.name',$edit ? $company->translateOrDefault($lang['code'])?->name : null) }}"
                            >
                            @error($lang['code'].'.name')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>
                    @endforeach

                    @foreach($languages as $lang)
                        <div class="col-md-6">
                            <label class="form-label" for="{{ $lang['code'] }}[description]">{{ _trans('Company description') }} ({{ ucfirst($lang['code']) }})</label>
                            <textarea id="{{ $lang['code'] }}[description]"
                                      cols="3"
                                      rows="3"
                                      name="{{ $lang['code'] }}[description]"
                                      class="form-control @error($lang['code'].'.description') is-invalid @enderror "
                                      placeholder="{{ _trans('Company description') }} ({{ ucfirst($lang['code']) }})"
                            >{{ old($lang['code'].'.description',$edit ? $company->translateOrDefault($lang['code'])?->description : null) }}</textarea>
                            @error($lang['code'].'.description')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>
                    @endforeach


                    <div class="col-md-4">
                        <label class="form-label" for="phone">{{ _trans('Phone') }}</label>
                        <input id="phone"
                               type="text"
                               name="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               placeholder="{{ _trans('Phone') }}"
                               value="{{ old('phone',$edit ? $company->phone : null) }}"
                        >
                        @error('phone')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="whatsapp_number">{{ _trans('Whatsapp number') }}</label>
                        <input id="whatsapp_number"
                               type="text"
                               name="whatsapp_number"
                               class="form-control @error('whatsapp_number') is-invalid @enderror"
                               placeholder="{{ _trans('Whatsapp number') }}"
                               value="{{ old('whatsapp_number',$edit ? $company->whatsapp_number : null) }}"
                        >
                        @error('whatsapp_number')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <x-category :categories="$categories" :model="$edit ? $company : null" scripts="scripts" col="4"/>

                    <x-country :countries="$countries" :model="$edit ? $company : null" scripts="scripts" col="4"/>

                    @foreach($languages as $key =>  $lang)
                        <div class="col-md-4">
                            <label for="address_{{ $lang['code'] }}" class="form-label">{{ _trans('Address') }} ({{ ucfirst($lang['code']) }})</label>
                            <input id="address_{{ $lang['code'] }}"
                                   type="text"
                                   name="{{ $lang['code'] }}[address]"
                                   class="form-control @error($lang['code'].'.address') is-invalid @enderror"
                                   placeholder="{{ _trans('Enter') }} {{ _trans('Address') }} ({{ ucfirst($lang['code']) }})"
                                   value="{{ old($lang['code'].'.address',$edit ? $company->translateOrDefault($lang['code'])?->address : null) }}"
                            >
                            @error($lang['code'].'.address')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>
                    @endforeach

                    <div class="col-md-4">
                        <div class="mb-1">
                            <label class="form-label">{{ _trans('Logo') }}</label>
                        </div>
                        <div class="p-2 border border-dashed" style="max-width:230px;">
                            <div class="row" id="logo"></div>
                        </div>
                        @error('logo')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <x-switch :model="$edit ? $company->status : 0" name="status" scripts="scripts" col="4" />

                    <div class="col-md-4">
                        <input type="text" id="search_location" class="form-control"
                               placeholder="{{ _trans('Search location') }}"
                               value="{{ old('search_location') }}"
                               name="search_location"/>
                    </div>

                    <div class="col-md-12">
                        <div id="divInfo" style="font-family: Arial; font-size: 12px; color: Red;"></div>
                        <div id="geomap"></div>
                    </div>

                    <input type="hidden" name="lat" value="{{ old('lat',$edit ? $company->lat : null)  }}" class="search_latitude"/>
                    <input type="hidden" name="lng" value="{{ old('lng',$edit ? $company->lng : null)  }}" class="search_longitude"/>

                    <div class="col-md-12">
                        <h5>{{ _trans('Working Hours') }}</h5>
                        <hr>
                        @include('admin.company.form-repeater',['data' => $edit ? old('social_media',$company->social_media) :  (!empty(old('social_media')) ? (old('social_media')) : []) ])
                    </div>

                    <div class="col-md-12">
                        <div class="m-t-50 d-flex justify-content-end">
                            <button type="submit" id="btn-company" class="btn btn-primary">{{ $edit ?_trans('Update') : _trans('Save') }}</button>
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
    <script src="{{ asset('assets/repeater/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('assets/repeater/form-repeater.min.js') }}"></script>

    @include('layouts.partials.spartan-multi-image',[
    'single' => true,
    'file_name' => 'logo',
    'image' => $edit ? getAvatar($company->logo) :  asset('assets/images/img/400x400/img2.jpg')
    ])
@endpush

@include('layouts.partials.location-with-map',[
    'scripts' => 'scripts',
    'styles' => 'styles',
])

@include('layouts.ajax.disabled-button-form',[
        'id' => 'company',
        ])

