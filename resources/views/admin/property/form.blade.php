@extends('layouts.master')
@section('title',$edit ? _trans('Edit Property') : _trans('Add Property'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ $edit ? _trans('Edit Property') : _trans('Add Property') }}</h3>
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
                    @canany(['Property list','Property edit','Property delete'])
                        <li class="breadcrumb-item ">
                            <a href="{{ route('admin.property.index') }}">{{ _trans('Properties') }}</a>
                        </li>
                    @endcanany

                    <li class="breadcrumb-item active">
                        {{ $edit ? _trans('Edit Property') : _trans('Add Property') }}
                    </li>
                </ol>
            </div>

            <div class="col-xl-12 col-md-12 ">
                @if ($edit)
                    {{ Form::open(['route' => ['admin.property.update',$property->id],'method' => 'PUT','files' => true,'class' => 'form mb-15 form-submit','id' =>'property_form']) }}
                @else
                    {{ Form::open(['route' => 'admin.property.store','method' => 'POST','files' => true,'class' => 'form mb-15','id' =>'property_form']) }}
                @endif

                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label" for="code">{{ _trans('Property Code') }}</label>
                        <input id="code"
                               disabled
                               type="text"
                               class="form-control"
                               placeholder="{{ _trans('Property Code') }}"
                               value="{{ getCodeTable('Property','properties',$edit,$edit ? $property->id : null) }}"
                        >
                    </div>

                    <div class="col-md-1">
                        <label class="form-label" for="switch-publish-enable">{{ _trans('Is publish ?') }}</label>
                        <div class="media-body icon-state">
                            <label class="switch">
                                <input id="switch-publish-enable"
                                       type="checkbox"
                                       class="switch-publish-enable"
                                    @checked(old('publish',$edit ? $property->realEstate?->publish : 0)  == 1)
                                >
                                <span class="switch-state"></span>
                            </label>
                        </div>
                        @error('publish')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                        <input id="publish"
                               type="hidden"
                               name="publish"
                               class="form-control"
                               value="{{ old('publish',$edit ? $property->realEstate?->publish : 0) }}"
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
                        <select id="user_id"
                                name="user_id"
                                class="js-example-basic-single @error('user_id') is-invalid @enderror">
                            <option value="">{{ _trans('Select user name') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(old('user_id',$edit ? $property->realEstate?->user_id : null) == $user->id)>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="special_id">
                            {{ _trans('Special name') }}
                            @can('Special add')
                                <a href="{{ route('admin.special.create') }}" target="_blank" class="btn-sm btn-primary">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                </a>
                            @endcan
                        </label>
                        <select id="special_id"
                                name="special_id"
                                class="js-example-basic-single @error('special_id') is-invalid @enderror">
                            <option value="">{{ _trans('Select special name') }}</option>
                            @foreach($specials as $special)
                                <option value="{{ $special->id }}" @selected(old('special_id',$edit ? $property->realEstate?->special_id : null) == $special->id)>
                                    {{ $special->translateOrDefault(locale())?->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('special_id')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    @foreach($languages as $lang)
                        <div class="col-md-6">
                            <label class="form-label" for="{{ $lang['code'] }}[title]">{{_trans('Property title') }} ({{ ucfirst($lang['code']) }})</label>
                            <input id="{{ $lang['code'] }}[title]"
                                   type="text"
                                   name="{{ $lang['code'] }}[title]"
                                   class="form-control @error($lang['code'].'.title') is-invalid @enderror"
                                   placeholder="{{_trans('Property title') }} ({{ ucfirst($lang['code']) }})"
                                   value="{{ old($lang['code'].'.title',$edit ? $property->realEstate?->translateOrDefault($lang['code'])?->title : null) }}"
                                   required="">
                            @error($lang['code'].'.title')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>
                    @endforeach

                    @foreach($languages as $lang)
                        <div class="col-md-6">
                            <label class="form-label" for="{{ $lang['code'] }}[description]">{{ _trans('Property short description') }} ({{ ucfirst($lang['code']) }})</label>
                            <textarea id="{{ $lang['code'] }}[description]"
                                      cols="3"
                                      rows="3"
                                      name="{{ $lang['code'] }}[description]"
                                      class="form-control @error($lang['code'].'.description') is-invalid @enderror "
                                      placeholder="{{ _trans('Property short description') }} ({{ ucfirst($lang['code']) }})"
                            >{{ old($lang['code'].'.description',$edit ? $property->realEstate?->translateOrDefault($lang['code'])?->description : null) }}</textarea>
                            @error($lang['code'].'.description')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>
                    @endforeach

                    @foreach($languages as $lang)
                        <div class="col-md-6">
                            <label class="form-label" for="{{ $lang['code'] }}[content]">{{ _trans('Content') }} ({{ ucfirst($lang['code']) }})</label>
                            <textarea id="{{ $lang['code'] }}[content]"
                                      cols="3"
                                      rows="3"
                                      name="{{ $lang['code'] }}[content]"
                                      class="form-control @error($lang['code'].'.content') is-invalid @enderror textarea"
                                      placeholder="{{ _trans('Content') }} ({{ ucfirst($lang['code']) }})"
                            >{{ old($lang['code'].'.content',$edit ? $property->realEstate?->translateOrDefault($lang['code'])?->content : null) }}</textarea>
                            @error($lang['code'].'.content')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>
                    @endforeach

                    <div class="col-md-4">
                        <label class="form-label" for="type">{{ _trans('Property type') }}</label>
                        <select id="type"
                                name="type"
                                class="js-example-basic-single @error('type') is-invalid @enderror">
                            <option value="">{{ _trans('Select property type') }}</option>
                            @foreach(\App\Enums\PropertyType::cases() as $type)
                                <option value="{{ $type->value }}"
                                    @selected(old('type',$edit ? $property->type : null) == $type->value)
                                >{{ _trans($type->name) }}</option>
                            @endforeach
                        </select>
                        @error('type')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="status">{{ _trans('Property status') }}</label>
                        <select id="status"
                                name="status"
                                class="js-example-basic-single @error('status') is-invalid @enderror">
                            <option value="">{{ _trans('Select property status') }}</option>
                            @foreach(\App\Enums\PropertyStatus::cases() as $type)
                                <option value="{{ $type->value }}"
                                    @selected(old('status',$edit ? $property->status : null) == $type->value)
                                >{{ _trans($type->name) }}</option>
                            @endforeach
                        </select>
                        @error('status')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="moderation_status">{{ _trans('Moderation status') }}</label>
                        <select id="moderation_status"
                                name="moderation_status"
                                class="js-example-basic-single @error('moderation_status') is-invalid @enderror">
                            <option value="">{{ _trans('Select property moderation status') }}</option>
                            @foreach(\App\Enums\ModerationStatus::cases() as $type)
                                <option value="{{ $type->value }}"
                                    @selected(old('moderation_status',$edit ? $property->moderation_status : null) == $type->value)
                                >{{ _trans($type->name) }}</option>
                            @endforeach
                        </select>
                        @error('moderation_status')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="project_id">
                            {{ _trans('Project name') }}
                            @can('Project add')
                                <a href="{{ route('admin.project.create') }}" target="_blank" class="btn-sm btn-primary">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                </a>
                            @endcan
                        </label>
                        <select id="project_id"
                                name="project_id"
                                class="js-example-basic-single @error('project_id') is-invalid @enderror">
                            <option value="">{{ _trans('Select project name') }}</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" @selected(old('project_id',$edit ? $property->project_id : null) == $project->id)>
                                    {{ $project->realEstate?->translateOrDefault(locale())?->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="number_bedrooms">{{ _trans('Number bedrooms') }}</label>
                        <input id="number_bedrooms"
                               type="number"
                               min="0"
                               step="1"
                               name="number_bedrooms"
                               class="form-control @error('number_bedrooms') is-invalid @enderror"
                               placeholder="{{ _trans('Enter number bedrooms') }}"
                               value="{{ old('number_bedrooms',$edit ? $property->number_bedrooms : 0) }}"
                        >
                        @error('number_bedrooms')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="number_bathrooms">{{ _trans('Number bathrooms') }}</label>
                        <input id="number_bathrooms"
                               type="number"
                               min="0"
                               step="1"
                               name="number_bathrooms"
                               class="form-control @error('number_bathrooms') is-invalid @enderror"
                               placeholder="{{ _trans('Enter number bathrooms') }}"
                               value="{{ old('number_bathrooms',$edit ? $property->number_bathrooms : 0) }}"
                        >
                        @error('number_bathrooms')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="number_floors">{{ _trans('Number floors') }}</label>
                        <input id="number_floors"
                               type="number"
                               min="0"
                               step="1"
                               name="number_floors"
                               class="form-control @error('number_floors') is-invalid @enderror"
                               placeholder="{{ _trans('Enter number floors') }}"
                               value="{{ old('number_floors',$edit ? $property->number_floors : 0) }}"
                        >
                        @error('number_floors')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="square">{{ _trans('Square (m²)') }}</label>
                        <input id="square"
                               type="number"
                               min="0"
                               step="any"
                               name="square"
                               class="form-control @error('square') is-invalid @enderror"
                               placeholder="{{ _trans('Enter Square (m²)') }}"
                               value="{{ old('square',$edit ? $property->square : 0) }}"
                        >
                        @error('square')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="price">{{ _trans('Price') }}</label>
                        <input id="price"
                               type="number"
                               min="0"
                               step="any"
                               name="price"
                               class="form-control @error('price') is-invalid @enderror"
                               placeholder="{{ _trans('Enter price') }}"
                               value="{{ old('price',$edit ? $property->price : 0) }}"
                        >
                        @error('price')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <x-category :categories="$categories" :model="$edit ? $property->realEstate : null" scripts="scripts" col="4"/>

                    <div class="col-md-4">
                        <label class="form-label" for="currency_id">
                            {{ _trans('Currency name') }}
                            @can('Currency add')
                                <a href="{{ route('admin.currency.create') }}" target="_blank" class="btn-sm btn-primary">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                </a>
                            @endcan
                        </label>
                        <select id="currency_id"
                                name="currency_id"
                                class="js-example-basic-single @error('currency_id') is-invalid @enderror">
                            <option value="">{{ _trans('Select currency name') }}</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}" @selected(old('currency_id',$edit ? $property->realEstate?->currency_id : null) == $currency->id)>
                                    {{ $currency->translateOrDefault(locale())?->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('currency_id')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="youtube_video_url">{{ _trans('Youtube video url') }}</label>
                        <input id="youtube_video_url"
                               type="url"
                               name="youtube_video_url"
                               class="form-control @error('youtube_video_url') is-invalid @enderror"
                               value="{{ old('youtube_video_url',$edit ? $property->realEstate?->youtube_video_url : null) }}"
                        >
                        @error('youtube_video_url')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="start_date">{{ _trans('Start date') }}</label>
                        <input id="start_date"
                               type="date"
                               name="start_date"
                               class="form-control @error('start_date') is-invalid @enderror"
                               value="{{ old('start_date',$edit ? formatDate('Y-m-d',$property->realEstate?->start_date) : null) }}"
                        >
                        @error('start_date')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="end_date">{{ _trans('Start date') }}</label>
                        <input id="end_date"
                               type="date"
                               name="end_date"
                               class="form-control @error('end_date') is-invalid @enderror"
                               value="{{ old('end_date',$edit ? formatDate('Y-m-d',$property->realEstate?->end_date) : null) }}"
                        >
                        @error('end_date')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label" for="feature_id">
                            {{ _trans('Feature names') }}
                            @can('Feature add')
                                <a href="{{ route('admin.feature.create') }}" target="_blank" class="btn-sm btn-primary">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                </a>
                            @endcan

                        </label>
                        <select id="feature_id"
                                name="feature_id[]"
                                multiple
                                class="js-example-basic-single @error('feature_id') is-invalid @enderror">
                            @foreach($features as $feature)
                                <option value="{{ $feature->id }}"
                                    @selected(in_array($feature->id,old('feature_id',$edit ? ($property->realEstate?->features ? $property->realEstate?->features->pluck('id')->toArray() : []) : [])))
                                >{{ $feature->translateOrDefault(locale())?->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('feature_id')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                        @error('feature_id.*')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-4">
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

                    <div class="col-md-4">
                        <div class="mb-1">
                            <label class="form-label">{{ _trans('Youtube video thumbnail') }}</label>
                        </div>
                        <div class="p-2 border border-dashed" style="max-width:230px;">
                            <div class="row" id="youtube_video_thumbnail"></div>
                        </div>
                        @error('youtube_video_thumbnail')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <div class="mb-1">
                            <label class="form-label">{{ _trans('Gallery') }}</label>
                        </div>
                        <div class="p-2 border border-dashed" style="max-width:430px;">
                            <div class="row" id="images">
                                @if($edit)
                                    @foreach ($property->images as $key => $photo)
                                        <div class="col-6" id="image_{{ $photo->id }}">
                                            <div class="card">
                                                <div class="card-body">
                                                    <img style="width: 100%" height="auto"
                                                         src="{{ getAvatar($photo->full_file) }}"
                                                         alt="Product image">
                                                    <button type="button" class="btn btn-danger btn-block delete-img" id="{{ $photo->id }}">
                                                        <i class="fa fa-times"></i>
                                                    </button>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @error('images')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                        @error('images.*')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <x-country :countries="$countries" :model="$edit ? $property->realEstate : null" scripts="scripts" col="4"/>

                    @foreach($languages as $key =>  $lang)
                        <div class="col-md-6">
                            <label for="address_{{ $lang['code'] }}" class="form-label">{{ _trans('Address') }} ({{ ucfirst($lang['code']) }})</label>
                            <input id="address_{{ $lang['code'] }}"
                                   type="text"
                                   name="{{ $lang['code'] }}[address]"
                                   class="form-control @error($lang['code'].'.address') is-invalid @enderror"
                                   placeholder="{{ _trans('Enter') }} {{ _trans('Address') }} ({{ ucfirst($lang['code']) }})"
                                   value="{{ old($lang['code'].'.address',$edit ? $property->realEstate?->translate($lang['code'])?->address : null) }}"
                            >
                            @error($lang['code'].'.address')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>
                    @endforeach

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

                    <input type="hidden" name="lat" value="{{ old('lat',$edit ? $property->realEstate?->lat : null)  }}" class="search_latitude"/>
                    <input type="hidden" name="lng" value="{{ old('lng',$edit ? $property->realEstate?->lng : null)  }}" class="search_longitude"/>

                    <div class="col-md-12">
                        <h4>{{ _trans('Search Engine Optimize') }}</h4>
                        <p>
                            {{ _trans('Setup meta title & description to make your site easy to discovered on search engines such as Google') }}
                        </p>
                        <hr>
                    </div>

                    @foreach($languages as $lang)
                        <div class="col-md-6">
                            <label class="form-label" for="{{ $lang['code'] }}[seo_title]">{{_trans('SEO title') }} ({{ ucfirst($lang['code']) }})</label>
                            <input id="{{ $lang['code'] }}[seo_title]"
                                   type="text"
                                   name="{{ $lang['code'] }}[seo_title]"
                                   class="form-control @error($lang['code'].'.seo_title') is-invalid @enderror"
                                   placeholder="{{_trans('SEO title') }} ({{ ucfirst($lang['code']) }})"
                                   value="{{ old($lang['code'].'.seo_title',$edit ? $property->realEstate?->translateOrDefault($lang['code'])?->seo_title : null) }}"
                            >
                            @error($lang['code'].'.seo_title')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>
                    @endforeach

                    @foreach($languages as $lang)
                        <div class="col-md-6">
                            <label class="form-label" for="{{ $lang['code'] }}[seo_description]">{{ _trans('SEO description') }} ({{ ucfirst($lang['code']) }})</label>
                            <textarea id="{{ $lang['code'] }}[seo_description]"
                                      cols="3"
                                      rows="3"
                                      name="{{ $lang['code'] }}[seo_description]"
                                      class="form-control @error($lang['code'].'.seo_description') is-invalid @enderror "
                                      placeholder="{{ _trans('SEO description') }} ({{ ucfirst($lang['code']) }})"
                            >{{ old($lang['code'].'.seo_description',$edit ? $property->realEstate?->translateOrDefault($lang['code'])?->seo_description : null) }}</textarea>
                            @error($lang['code'].'.seo_description')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>
                    @endforeach

                    <div class="col-md-12">
                        <div class="m-t-50 d-flex justify-content-end">
                            <button type="submit" id="btn-property" class="btn btn-primary">{{ $edit ?_trans('Update') : _trans('Save') }}</button>
                        </div>
                    </div>
                </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection
@include('layouts.partials.ckeditor',['inputClass' => 'textarea'])
@push('scripts')
    <script src="{{ asset('assets/js/spartan-multi-image-picker.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#switch-feature-enable').on('change', function () {
                if ($(this).prop("checked") === true) {
                    $("#feature").val(1);
                } else {
                    $("#feature").val(0);
                }
            })
            @if(old('feature',$edit ? $property->realEstate?->feature : 0) == 1)
            $("#feature").val(1);
            @else
            $("#feature").val(0);
            @endif

            $('#switch-publish-enable').on('change', function () {
                if ($(this).prop("checked") === true) {
                    $("#publish").val(1);
                } else {
                    $("#publish").val(0);
                }
            })
            @if(old('publish',$edit ? $property->realEstate?->publish : 0) == 1)
            $("#publish").val(1);
            @else
            $("#publish").val(0);
            @endif
        })
    </script>
    @include('layouts.partials.spartan-multi-image',[
    'single' => true,
    'file_name' => 'image',
    'image' => $edit ? getAvatar($property->realEstate?->image) :  asset('assets/images/img/400x400/img2.jpg')
    ])

    @include('layouts.partials.spartan-multi-image',[
    'single' => true,
    'file_name' => 'youtube_video_thumbnail',
    'image' => $edit ? getAvatar($property->realEstate?->youtube_video_thumbnail) :  asset('assets/images/img/400x400/img2.jpg')
    ])

    @include('layouts.partials.spartan-multi-image',[
    'multi' => true,
    'file_name' => 'images',
    'count' => $edit ? 5 - $property->realEstate?->images_count : 5,
    ])

    @includeWhen($edit,'layouts.ajax.delete-images',[
         'class_name' => 'delete-img',
         'route' => route('admin.property.delete-image'),
         'relation_id' => $edit ? $property->id : null
         ])
@endpush
@include('layouts.partials.location-with-map',[
    'scripts' => 'scripts',
    'styles' => 'styles',
])

@include('layouts.ajax.disabled-button-form',[
        'id' => 'property',
        ])
