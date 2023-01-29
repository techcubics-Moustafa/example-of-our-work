@extends('layouts.master')
@section('title',$edit ? _trans('Edit Region') : _trans('Add Region'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ $edit ? _trans('Edit Region') : _trans('Add Region') }}</h3>
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
                    @canany(['Region list','Region edit','Region delete'])
                        <li class="breadcrumb-item ">
                            <a href="{{ route('admin.region.index') }}">{{ _trans('Regions') }}</a>
                        </li>
                    @endcanany

                    <li class="breadcrumb-item active">
                        {{ $edit ? _trans('Edit Region') : _trans('Add Region') }}
                    </li>
                </ol>
            </div>

            <div class="col-md-12">
                @if ($edit)
                    {{ Form::open(['route' => ['admin.region.update',$region->id],'method' => 'PUT','class' => 'form mb-15 form-submit','id' =>'region_form']) }}
                @else
                    {{ Form::open(['route' => 'admin.region.store','method' => 'POST','class' => 'form mb-15','id' =>'region_form']) }}
                @endif

                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="code" class="form-label">{{ _trans('Region Code') }}</label>
                        <input id="code"
                               disabled
                               type="text"
                               class="form-control"
                               placeholder="{{ _trans('Region Code') }}"
                               value="{{ getCodeTable('Reg','regions',$edit,$edit ? $region->id : null) }}"
                        >
                    </div>

                    @if ($edit)
                        <div class="col-md-6">
                            <label class="form-label" for="date">{{ _trans('Date') }}</label>
                            <input id="date"
                                   disabled
                                   type="text"
                                   class="form-control"
                                   value="{{ $region->created_at->diffForHumans() }}"
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
                                class="js-example-basic-single @error('country_id') is-invalid @enderror">
                            <option>{{ _trans('Select Country name') }}</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}"
                                    @selected(old('country_id',$edit ? $region->country_id : null) == $country->id)
                                >{{ $country->translateOrDefault(locale())?->name }}</option>
                            @endforeach
                        </select>
                        @error('country_id')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="governorate_id">
                            {{ _trans('Governorate name') }}
                            @can('Governorate add')
                                <a href="{{ route('admin.governorate.create') }}" target="_blank" class="btn-sm btn-primary">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                </a>
                            @endcan
                        </label>
                        <select id="governorate_id" name="governorate_id"
                                class="js-example-basic-single @error('governorate_id') is-invalid @enderror">
                            <option>{{ _trans('Select Governorate name') }}</option>

                        </select>
                        @error('governorate_id')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    @foreach($languages as $lang)
                        <div class="col-md-6">
                            <label for="{{ $lang['code'] }}[name]" class="form-label">{{ _trans('Region name') }} ({{ ucfirst($lang['code']) }})</label>
                            <input id="{{ $lang['code'] }}[name]"
                                   type="text"
                                   name="{{ $lang['code'] }}[name]"
                                   class="form-control @error($lang['code'].'.name') is-invalid @enderror"
                                   placeholder="{{ _trans('Region').' '._trans('Name') }}"
                                   value="{{ old($lang['code'].'.name',$edit ? $region->translateOrDefault($lang['code'])?->name : null) }}"
                            >
                            @error($lang['code'].'.name')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>
                    @endforeach

                    <div class="col-md-12">
                        <div class="m-t-50 d-flex justify-content-end">
                            <button type="submit" id="btn-region" class="btn btn-primary">{{ $edit ?_trans('Update') : _trans('Save') }}</button>
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
    <script>
        $(document).ready(function () {
            getGovernorate("{{ old('country_id',$edit ? $region->country_id : null) }}", "{{ old('governorate_id',$edit ? $region->governorate_id : null)  }}")
        })
        $(document).on('change', '#country_id', function (e) {
            e.preventDefault();
            $("#governorate_id").empty();
            $("#governorate_id").append('<option value="">{{ _trans('Select Governorate name') }}</option>');
            var countryId = $('#country_id option:selected').val();
            getGovernorate(countryId)

        });

        function getGovernorate(countryId, selectedId = null) {
            if (countryId == '' || countryId == null) {
                return false;
            } else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    url: "{{ route('ajax.governorate-by-country') }}",
                    method: 'POST',
                    cache: false,
                    data: {
                        country_id: countryId
                    },
                    success: function (response) {
                        if (response.status == true) {
                            if (response.data.length > 0) {
                                $.each(response.data, function (index, value) {
                                    var selected = '';
                                    if (selectedId == value.id) {
                                        selected = 'selected';
                                    }
                                    $("#governorate_id").append('<option ' + selected + ' value="' + value.id + '">' + value.name + '</option>');
                                });
                            }

                        } else {
                            toastr.error(response.error)
                        }

                    },

                });
            }
        }
    </script>
@endpush
@include('layouts.ajax.disabled-button-form',[
        'id' => 'region',
        ])
