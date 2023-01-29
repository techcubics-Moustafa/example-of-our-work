@extends('layouts.master')
@section('title',$edit ? _trans('Edit Governorate') : _trans('Add Governorate'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ $edit ? _trans('Edit Governorate') : _trans('Add Governorate') }}</h3>
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
                    <li class="breadcrumb-item ">
                        @canany(['Governorate list','Governorate edit','Governorate delete'])
                            <a href="{{ route('admin.governorate.index') }}">{{ _trans('Governorates') }}</a>
                        @endcanany
                    </li>
                    <li class="breadcrumb-item active">
                        {{ $edit ? _trans('Edit Governorate') : _trans('Add Governorate') }}
                    </li>
                </ol>
            </div>

            <div class="col-md-12">
                @if ($edit)
                    {{ Form::open(['route' => ['admin.governorate.update',$governorate->id],'method' => 'PUT','class' => 'form mb-15 form-submit','id' =>'governorate_form']) }}
                @else
                    {{ Form::open(['route' => 'admin.governorate.store','method' => 'POST','class' => 'form mb-15','id' =>'governorate_form']) }}
                @endif

                <div class="row g-4">
                    <div class="col-md-6">
                        <label id="code" class="form-label" for="">{{ _trans('Governorate Code') }}</label>
                        <input id="code"
                               disabled
                               type="text"
                               class="form-control"
                               placeholder="{{ _trans('Governorate Code') }}"
                               value="{{ getCodeTable('Gov','governorates',$edit,$edit ? $governorate->id : null) }}"
                        >
                    </div>

                    @if ($edit)
                        <div class="col-md-6">
                            <label class="form-label" for="date">{{ _trans('Date') }}</label>
                            <input id="date"
                                   disabled
                                   type="text"
                                   class="form-control"
                                   value="{{ $governorate->created_at->diffForHumans() }}"
                            >
                        </div>
                    @endif
                    <div class="col-md-6">
                        <label class="form-label" for="country_id">
                            {{ _trans('Country name') }}
                            @can('Country add')
                                <a href="{{ route('admin.country.create') }}" target="_blank" class="btn-sm btn-primary">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                </a>
                            @endcan
                        </label>
                        <select id="country_id" name="country_id"
                                class="js-example-basic-single @error('country_id') is-invalid @enderror" required="">
                            <option value="">{{ _trans('Select Country Name') }}</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" @selected(old('country_id',$edit ? $governorate->country_id : null) == $country->id)>{{ $country->translateOrDefault(locale())?->name }}</option>
                            @endforeach
                        </select>
                        @error('country_id')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    @foreach($languages as $lang)
                        <div class="col-md-6">
                            <label for="{{ $lang['code'] }}[name]" class="form-label">{{ _trans('Governorate name') }} ({{ ucfirst($lang['code']) }})</label>
                            <input id="{{ $lang['code'] }}[name]"
                                   type="text"
                                   name="{{ $lang['code'] }}[name]"
                                   class="form-control @error($lang['code'].'.name') is-invalid @enderror"
                                   placeholder="{{ _trans('Governorate name') }}({{ ucfirst($lang['code']) }})"
                                   value="{{ old($lang['code'].'.name',$edit ? $governorate->translateOrDefault($lang['code'])?->name : null) }}"
                                   required="">
                            @error($lang['code'].'.name')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>
                    @endforeach

                    <div class="col-md-12">
                        <div class="m-t-50 d-flex justify-content-end">
                            <button type="submit" id="btn-governorate" class="btn btn-primary">
                                @if ($edit)
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                @else
                                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                @endif
                                {{ $edit ?_trans('Update') : _trans('Save') }}
                            </button>
                        </div>
                    </div>


                </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection
@include('layouts.ajax.disabled-button-form',[
        'id' => 'governorate',
        ])
