@extends('layouts.master')
@section('title',$edit ? _trans('Edit Currency') : _trans('Add Currency'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ $edit ? _trans('Edit Currency') : _trans('Add Currency') }}</h3>
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
                    @canany(['Currency list','Currency edit','Currency delete'])
                        <li class="breadcrumb-item ">
                            <a href="{{ route('admin.currency.index') }}">{{ _trans('Currencies') }}</a>
                        </li>
                    @endcanany

                    <li class="breadcrumb-item active">
                        {{ $edit ? _trans('Edit Currency') : _trans('Add Currency') }}
                    </li>
                </ol>
            </div>

            <div class="col-xl-12 col-md-12 ">
                @if ($edit)
                    {{ Form::open(['route' => ['admin.currency.update',$currency->id],'method' => 'PUT','files' => true,'class' => 'form mb-15 form-submit','id' =>'currency_form']) }}
                @else
                    {{ Form::open(['route' => 'admin.currency.store','method' => 'POST','files' => true,'class' => 'form mb-15','id' =>'currency_form']) }}
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="code">{{ _trans('Currency Code') }}</label>
                        <input id="code"
                               disabled
                               type="text"
                               class="form-control"
                               placeholder="{{ _trans('Currency Code') }}"
                               value="{{ getCodeTable('Currency','currencies',$edit,$edit ? $currency->id : null) }}"
                        >
                    </div>
                    <div class="col-md-6">
                        <label id="code" class="form-label" for="">{{ _trans('Currency Code') }}</label>
                        <input id="code"
                               type="text"
                               name="code"
                               class="form-control"
                               placeholder="{{ _trans('Currency Code') }}"
                               value="{{ old('code',$edit ? $currency->code : null) }}"
                        >
                        @error('code')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    @foreach($languages as $lang)
                        <div class="col-md-6">
                            <label id="{{ $lang['code'] }}[name]" class="form-label" for="">{{ _trans('Name').' '._trans('Currency') }} ({{ ucfirst($lang['code']) }})</label>
                            <input id="{{ $lang['code'] }}[name]"
                                   type="text"
                                   name="{{ $lang['code'] }}[name]"
                                   class="form-control @error($lang['code'].'.name') is-invalid @enderror"
                                   placeholder="{{ _trans('Name').' '._trans('Currency') }}"
                                   value="{{ old($lang['code'].'.name',$edit ? $currency->translateOrDefault($lang['code'])?->name : null) }}"
                                   required="">
                            @error($lang['code'].'.name')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>
                    @endforeach

                    <div class="col-md-12">
                        <div class="m-t-50 d-flex justify-content-end">
                            <button type="submit" id="btn-currency" class="btn btn-primary">{{ $edit ?_trans('Update') : _trans('Save') }}</button>
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
        'id' => 'currency',
        ])
