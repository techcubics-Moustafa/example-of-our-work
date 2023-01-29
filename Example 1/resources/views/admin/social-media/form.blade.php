@extends('layouts.master')

@section('title',_trans('Social Media'))


@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ $edit ? _trans('Edit Social Media') : _trans('Add Social Media') }}</h3>
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid default-dash">
        <div class="row">
            <div class="col-12 col-sm-6">
                <ol class="breadcrumb">
                    <x-link-home/>
                    @canany(['Social#Media list','Social#Media edit','Social#Media delete'])
                        <li class="breadcrumb-item ">
                            <a href="{{ route('admin.social-media.index') }}">{{ _trans('Social Media') }}</a>
                        </li>
                    @endcanany

                    <li class="breadcrumb-item active">
                        {{ $edit ? _trans('Edit Social Media') : _trans('Add Social Media') }}
                    </li>
                </ol>
            </div>

            <div class="col-xl-12 col-md-12 ">
                @if ($edit)
                    {{ Form::open(['url' => route('admin.social-media.update',$socialMedia->id),'method' => 'PUT','files' => true,'class' => 'form mb-15 form-submit','id' =>'social_form']) }}
                @else
                    {{ Form::open(['route' => 'admin.social-media.store','method' => 'POST','files' => true,'class' => 'form mb-15','id' =>'social_form']) }}
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="code" class="form-label">{{ _trans('Social Media Code') }}</label>
                        <input id="code"
                               disabled
                               type="text"
                               class="form-control"
                               placeholder="{{ _trans('Social Media Code') }}"
                               value="{{ getCodeTable('SM','social_media',$edit,$edit ? $socialMedia->id : null) }}"
                        >
                    </div>

                    <div class="col-md-6">
                        <div class="form-group form-image">
                            <label for="slug" class="font-md">{{ _trans('Name social media') }}</label>
                            <input id="slug"
                                   type="text"
                                   name="slug"
                                   placeholder="{{ _trans('Name social media') }}"
                                   value="{{ old('slug',$edit ? $socialMedia->slug : null) }}"
                                   class="form-control @error('slug') is-invalid @enderror">
                            @error('slug')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group form-image">
                            <label for="url" class="font-md">{{ _trans('Link social media') }}</label>
                            <input id="url"
                                   type="url"
                                   name="url"
                                   placeholder="{{ _trans('Link social media') }}"
                                   value="{{ old('url',$edit ? $socialMedia->url : null) }}"
                                   class="form-control @error('url') is-invalid @enderror">
                            @error('url')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-1">
                            <label class="form-label">{{ _trans('Icon') }}</label>
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
                            <button type="submit" id="btn-social" class="btn btn-primary">{{ $edit ?_trans('Update') : _trans('Save') }}</button>
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
   'image' => $edit ? getAvatar($socialMedia->icon) :  asset('assets/images/img/400x400/img2.jpg')
   ])
@endpush
@include('layouts.ajax.disabled-button-form',[
        'id' => 'social',
        ])
