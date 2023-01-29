@extends('layouts.master')
@section('title',$edit ? _trans('Edit Category') : _trans('Add Category'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ $edit ? _trans('Edit Category') : _trans('Add Category') }}</h3>
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
                    @canany(['Category list','Category edit','Category delete'])
                        <li class="breadcrumb-item ">
                            <a href="{{ route('admin.category.index') }}">{{ _trans('Categories') }}</a>
                        </li>
                    @endcanany

                    <li class="breadcrumb-item active">
                        {{ $edit ? _trans('Edit Category') : _trans('Add Category') }}
                    </li>
                </ol>
            </div>

            <div class="col-xl-12 col-md-12 ">
                @if ($edit)
                    {{ Form::open(['route' => ['admin.category.update',$category->id],'method' => 'PUT','files' => true,'class' => 'form mb-15 form-submit','id' =>'category_form']) }}
                @else
                    {{ Form::open(['route' => 'admin.category.store','method' => 'POST','files' => true,'class' => 'form mb-15','id' =>'category_form']) }}
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="code">{{ _trans('Category Code') }}</label>
                        <input id="code"
                               disabled
                               type="text"
                               class="form-control"
                               placeholder="{{ _trans('Category Code') }}"
                               value="{{ getCodeTable('Category','categories',$edit,$edit ? $category->id : null) }}"
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="ranking" class="form-label">{{ _trans('Category ranking') }}</label>
                        <input id="ranking"
                               name="ranking"
                               type="number"
                               step="1"
                               min="1"
                               class="form-control @error('ranking') is-invalid @enderror"
                               placeholder="{{ _trans('Category ranking') }}"
                               value="{{ old('ranking',$edit ? $category->ranking : null) }}"
                        >
                        @error('ranking')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    @foreach($languages as $lang)
                        <div class="col-md-6">
                            <label class="form-label" for="{{ $lang['code'] }}[name]">{{_trans('Category name') }} ({{ ucfirst($lang['code']) }})</label>
                            <input id="{{ $lang['code'] }}[name]"
                                   type="text"
                                   name="{{ $lang['code'] }}[name]"
                                   class="form-control @error($lang['code'].'.name') is-invalid @enderror"
                                   placeholder="{{_trans('Category name') }} ({{ ucfirst($lang['code']) }})"
                                   value="{{ old($lang['code'].'.name',$edit ? $category->translateOrDefault($lang['code'])?->name : null) }}"
                                   required="">
                            @error($lang['code'].'.name')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>
                    @endforeach

                    <div class="col-md-6">
                        <label class="form-label" for="parent_id">{{ _trans('Main Category Name') }}</label>
                        <select id="parent_id" name="parent_id"
                                class="js-example-basic-single @error('parent_id') is-invalid @enderror">
                            <option value="">{{ _trans('Select main category name') }}</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}"
                                    @selected(old('parent_id',$edit ? $category->parent_id : null) == $parent->id)>{{ $parent->translateOrDefault(locale())?->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_id')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

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
                            <button type="submit" id="btn-category" class="btn btn-primary">{{ $edit ?_trans('Update') : _trans('Save') }}</button>
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
    'image' => $edit ? getAvatar($category->image) :  asset('assets/images/img/400x400/img2.jpg')
    ])
@endpush

@include('layouts.ajax.disabled-button-form',[
        'id' => 'category',
        ])
