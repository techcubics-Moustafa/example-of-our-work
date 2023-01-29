@extends('layouts.master')
@section('title',_trans('Good Types'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ $edit ? _trans('Edit Good Type') : _trans('Add Good Type') }}</h3>
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
                            @canany(['Good#Type list','Good#Type edit','Good#Type delete'])
                                <a href="{{ route('admin.good-type.index') }}">{{ _trans('Good Types') }}</a>
                            @endcanany
                        </li>
                        <li class="breadcrumb-item active">
                            {{ $edit ? _trans('Edit Good Type') : _trans('Add Good Type') }}
                        </li>
                    </ol>
                </div>

                @if ($edit)
                    {{ Form::open(['route' => ['admin.good-type.update',$goodType->id],'method' => 'PUT','class' => 'form mb-15 form-submit','id' =>'good_type_form']) }}
                @else
                    {{ Form::open(['route' => 'admin.good-type.store','method' => 'POST','class' => 'form mb-15','id' =>'good_type_form']) }}
                @endif

                <div class="row g-4">
                    @if ($edit)
                        <div class="col-md-6">
                            <label class="form-label" for="date">{{ _trans('Date') }}</label>
                            <input disabled
                                   id="date"
                                   type="text"
                                   class="form-control"
                                   value="{{ $goodType->created_at->format('d-m-Y') }}"
                            >
                        </div>
                    @endif
                    @foreach($languages as $lang)
                        <div class="col-md-6">
                            <label class="form-label" for="{{ $lang['code'] }}[name]">{{ _trans('Good type name') }} ({{ ucfirst($lang['code']) }})</label>
                            <input id="{{ $lang['code'] }}[name]"
                                   type="text"
                                   name="{{ $lang['code'] }}[name]"
                                   class="form-control @error($lang['code'].'.name') is-invalid @enderror"
                                   placeholder="{{ _trans('Good type name') }}"
                                   value="{{ old($lang['code'].'.name',$edit ? $goodType->translateOrDefault($lang['code'])?->name : null) }}"
                                   required="">
                            @error($lang['code'].'.name')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>
                    @endforeach

                    <div class="col-md-12">
                        <div class="m-t-50 d-flex justify-content-end">
                            <button type="submit" id="btn-good_type" class="btn btn-primary">{{ $edit ?_trans('Update Good Type') : _trans('Save Good Type') }}</button>
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
        'id' => 'good_type',
        ])

