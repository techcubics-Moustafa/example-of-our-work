@extends('layouts.master')
@section('title',$edit ? _trans('Edit Special') : _trans('Add Special'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ $edit ? _trans('Edit Special') : _trans('Add Special') }}</h3>
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
                        </a>
                    </li>
                    @canany(['Special list','Special edit','Special delete'])
                        <li class="breadcrumb-item ">
                            <a href="{{ route('admin.special.index') }}">{{ _trans('Specials') }}</a>
                        </li>
                    @endcanany

                    <li class="breadcrumb-item active">
                        {{ $edit ? _trans('Edit Special') : _trans('Add Special') }}
                    </li>
                </ol>
            </div>

            <div class="col-xl-12 col-md-12 ">
                @if ($edit)
                    {{ Form::open(['route' => ['admin.special.update',$special->id],'method' => 'PUT','files' => true,'class' => 'form mb-15 form-submit','id' =>'special_form']) }}
                @else
                    {{ Form::open(['route' => 'admin.special.store','method' => 'POST','files' => true,'class' => 'form mb-15','id' =>'special_form']) }}
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="code">{{ _trans('Special Code') }}</label>
                        <input id="code"
                               disabled
                               type="text"
                               class="form-control"
                               placeholder="{{ _trans('Special Code') }}"
                               value="{{ getCodeTable('Special','specials',$edit,$edit ? $special->id : null) }}"
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="ranking" class="form-label">{{ _trans('Special ranking') }}</label>
                        <input id="ranking"
                               name="ranking"
                               type="number"
                               step="1"
                               min="1"
                               class="form-control @error('ranking') is-invalid @enderror"
                               placeholder="{{ _trans('Special ranking') }}"
                               value="{{ old('ranking',$edit ? $special->ranking : null) }}"
                        >
                        @error('ranking')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    @foreach($languages as $lang)
                        <div class="col-md-6">
                            <label class="form-label" for="{{ $lang['code'] }}[name]">{{_trans('Special name') }} ({{ ucfirst($lang['code']) }})</label>
                            <input id="{{ $lang['code'] }}[name]"
                                   type="text"
                                   name="{{ $lang['code'] }}[name]"
                                   class="form-control @error($lang['code'].'.name') is-invalid @enderror"
                                   placeholder="{{_trans('Special name') }} ({{ ucfirst($lang['code']) }})"
                                   value="{{ old($lang['code'].'.name',$edit ? $special->translateOrDefault($lang['code'])?->name : null) }}"
                                   required="">
                            @error($lang['code'].'.name')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>
                    @endforeach

                    <div class="col-md-12">
                        <div class="m-t-50 d-flex justify-content-end">
                            <button type="submit" id="btn-special" class="btn btn-primary">{{ $edit ?_trans('Update') : _trans('Save') }}</button>
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
    'file_name' => 'image',
    'image' => $edit ? getAvatar($special->image) :  asset('assets/images/img/400x400/img2.jpg')
    ])
@endpush

@include('layouts.ajax.disabled-button-form',[
        'id' => 'special',
        ])
