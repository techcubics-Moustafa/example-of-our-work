@extends('layouts.master')
@section('title',$edit ? _trans('Edit Page Setup') : _trans('Add Page Setup'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ $edit ? _trans('Edit Page Setup') : _trans('Add Page Setup') }}</h3>
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
                    @canany(['Page#Setup list','Page#Setup edit','Page#Setup delete'])
                        <li class="breadcrumb-item ">
                            <a href="{{ route('admin.page.index') }}">{{ _trans('Page Setup') }}</a>
                        </li>
                    @endcanany

                    <li class="breadcrumb-item active">
                        {{ $edit ? _trans('Edit Page Setup') : _trans('Add Page Setup') }}
                    </li>
                </ol>
            </div>

            <div class="col-xl-12 col-md-12 ">
                @if ($edit)
                    {{ Form::open(['route' => ['admin.page.update',$page->id],'method' => 'PUT','files' => true,'class' => 'form mb-15 form-submit','id' =>'page_form']) }}
                    {{ Form::hidden('id',$page->id) }}
                @else
                    {{ Form::open(['route' => 'admin.page.store','method' => 'POST','files' => true,'class' => 'form mb-15','id' =>'page_form']) }}
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="code">{{ _trans('Page Code') }}</label>
                        <input id="code"
                               disabled
                               type="text"
                               class="form-control"
                               placeholder="{{ _trans('Page Code') }}"
                               value="{{ getCodeTable('Page','pages',$edit,$edit ? $page->id : null) }}"
                        >
                    </div>

                    @if ($edit)
                        <div class="col-md-6">
                            <label class="form-label" for="date">{{ _trans('Date') }}</label>
                            <input id="date"
                                   disabled
                                   type="text"
                                   class="form-control"
                                   value="{{ $page->created_at->diffForHumans() }}"
                            >
                        </div>
                    @endif
                    <div class="col-md-6">
                        <label class="form-label" for="page_type">{{ _trans('Page Type') }}</label>
                        <select id="page_type" name="page_type"
                                class="js-example-basic-single @error('page_type') is-invalid @enderror">
                            <option value="">{{ _trans('Select page type') }}</option>
                            @foreach(\App\Enums\PageType::cases() as $row)
                                <option value="{{ $row->value }}"
                                    @selected(old('page_type',$edit ? $page->page_type : null) == $row->value)
                                >{{ _trans($row->name) }}</option>
                            @endforeach
                        </select>
                        @error('page_type')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    @foreach($languages as $lang)
                        <div class="col-md-6">
                            <label for="{{ $lang['code'] }}[name]" class="form-label">{{ _trans('Page name') }} ({{ ucfirst($lang['code']) }})</label>
                            <input id="{{ $lang['code'] }}[name]"
                                   type="text"
                                   name="{{ $lang['code'] }}[name]"
                                   class="form-control @error($lang['code'].'.name') is-invalid @enderror"
                                   placeholder="{{ _trans('Page name') }} ({{ ucfirst($lang['code']) }})"
                                   value="{{ old($lang['code'].'.name',$edit ? $page->translateOrDefault($lang['code'])?->name : null) }}"
                            >
                            @error($lang['code'].'.name')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>
                    @endforeach

                    @foreach($languages as $lang)
                        <div class="col-md-6">
                            <label for="{{ $lang['code'] }}[description]" class="form-label">{{ _trans('Page description') }} ({{ ucfirst($lang['code']) }})</label>
                            <textarea id="{{ $lang['code'] }}[description]"
                                      cols="3"
                                      rows="3"
                                      name="{{ $lang['code'] }}[description]"
                                      class="form-control @error($lang['code'].'.description') is-invalid @enderror textarea"
                                      placeholder="{{ _trans('Page description') }} ({{ ucfirst($lang['code']) }})"
                            >{{ old($lang['code'].'.description',$edit ? $page->translateOrDefault($lang['code'])?->description : null) }}</textarea>
                            @error($lang['code'].'.description')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>
                    @endforeach

                    <div class="col-md-6">
                        <div class="mb-1">
                            <label class="form-label">{{ _trans('Image') }}</label>
                        </div>
                        <div class="p-2 border border-dashed" style="max-width:230px;">
                            <div class="row" id="image"></div>
                        </div>
                        @error('image')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <div class="m-t-50 d-flex justify-content-end">
                            <button type="submit" id="btn-page" class="btn btn-primary">{{ $edit ?_trans('Update') : _trans('Save') }}</button>
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
    'image' => $edit ? getAvatar($page->image) :  asset('assets/images/img/400x400/img2.jpg')
    ])

@endpush
@include('layouts.partials.ckeditor',['inputClass' => 'textarea'])
@include('layouts.ajax.disabled-button-form',[
        'id' => 'page',
        ])
