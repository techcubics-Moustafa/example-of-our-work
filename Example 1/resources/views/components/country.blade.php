@props([
    'countries' => $countries,
    'model' => $model,
    'scripts' => $scripts,
    'col' => $col,
])
<div class="col-md-{{ $col }}">
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
        <option value="">{{ _trans('Select Country Name') }}</option>
        @foreach($countries as $country)
            <option value="{{ $country->id }}" @selected(old('country_id',$model?->country_id) == $country->id)>{{ $country->name }}</option>
        @endforeach
    </select>
    @error('country_id')
    <span class="text-danger">{!! $message !!} </span>
    @enderror
</div>

<div class="col-md-{{ $col }}">
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
        <option value="">{{ _trans('Select Governorate Name') }}</option>

    </select>
    @error('governorate_id')
    <span class="text-danger">{!! $message !!} </span>
    @enderror
</div>

<div class="col-md-{{ $col }}">
    <label class="form-label" for="region_id">
        {{ _trans('Region name') }}
        @can('Region add')
            <a href="{{ route('admin.region.create') }}" target="_blank" class="btn-sm btn-primary">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
            </a>
        @endcan
    </label>
    <select id="region_id" name="region_id"
            class="js-example-basic-single @error('region_id') is-invalid @enderror">
        <option value="">{{ _trans('Select Region Name') }}</option>
    </select>
    @error('region_id')
    <span class="text-danger">{!! $message !!} </span>
    @enderror
</div>

@push($scripts)
    <script>
        $(document).ready(function () {
            getGovernorate('{{ old('country_id',$model?->country_id) }}', '{{ old('governorate_id',$model?->governorate_id)  }}')
            getRegion('{{ old('governorate_id',$model?->governorate_id) }}', '{{ old('region_id',$model?->region_id) }}')
        })
    </script>
    @include('layouts.ajax.countries')
@endpush
