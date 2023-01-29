@extends('layouts.master')
@section('title',$edit ? _trans('Edit Language') : _trans('Add Language'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ $edit ? _trans('Edit Language') : _trans('Add Language') }}</h3>
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
                    @canany(['Language list','Language edit','Language delete'])
                        <li class="breadcrumb-item ">
                            <a href="{{ route('admin.language.index') }}">{{ _trans('Languages') }}</a>
                        </li>
                    @endcanany

                    <li class="breadcrumb-item active">
                        {{ $edit ? _trans('Edit Language') : _trans('Add Language') }}
                    </li>
                </ol>
            </div>

            <div class="col-md-12">
                @if ($edit)
                    {{ Form::open(['route' => ['admin.language.update',$language->id],'method' => 'PUT','files' => true,'class' => 'form mb-15 form-submit','id' =>'language_form']) }}
                @else
                    {{ Form::open(['route' => 'admin.language.store','method' => 'POST','files' => true,'class' => 'form mb-15','id' =>'language_form']) }}
                @endif

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label" for="code">{{ _trans('Code') }}</label>
                        <select id="code"
                                name="code"
                                class="js-example-basic-single @error('code') is-invalid @enderror">
                            <option value="">{{ _trans('Select code') }}</option>
                            @foreach($languages as $lang =>  $row)
                                <option value="{{ $lang }}" data-name="{{ $row['native'] }}"
                                    @selected(old('code',$edit ? $language->code : null) == $lang)>
                                    {{ $row['name'] }} ({{ $row['native'] }})
                                </option>
                            @endforeach
                        </select>
                        @error('code')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="language_name">{{ _trans('Language Name') }}</label>
                        <input id="language_name"
                               type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="{{ _trans('Language Name') }}"
                               value="{{ old('name',$edit ? $language->name : null) }}"
                        >
                        @error('name')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="direction">{{ _trans('direction') }}</label>
                        <select id="direction"
                                name="direction"
                                class="js-example-basic-single @error('direction') is-invalid @enderror">
                            <option value="">{{ _trans('Select direction') }}</option>
                            @foreach(\App\Enums\Direction::cases() as $row)
                                <option
                                    @selected(old('direction',$edit ? $language->direction : null) == $row->value)
                                    value="{{ $row->value }}">
                                    {{ $row->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('direction')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="flag">{{ _trans('Flag') }}</label>
                        <select id="flag" name="flag"
                                class="form-control @error('flag') is-invalid @enderror country-var-select">
                            <option value="">{{ _trans('Select Flag') }}</option>
                            @foreach($files as $path)
                                {{--@if(pathinfo($path)['filename'] !='en')--}}
                                <option {{ old('flag',$edit ? $language->flag : '') == pathinfo($path)['filename'] ? 'selected' : '' }}
                                        value="{{ pathinfo($path)['filename'] }}"
                                        title="{{ asset('flags/'.pathinfo($path)['filename'].'.png') }}">
                                    {{ strtoupper(pathinfo($path)['filename']) }}
                                </option>
                                {{--@endif--}}
                            @endforeach
                        </select>
                        @error('flag')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <div class="m-t-50 d-flex justify-content-end">
                            <button type="submit" id="btn-language" class="btn btn-primary">{{ $edit ?_trans('Update Language') : _trans('Save Language') }}</button>
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
    <script type="text/javascript">
        $(document).ready(function () {
            $('.country-var-select').select2({
                templateResult: codeSelect,
                templateSelection: codeSelect,
                escapeMarkup: function (m) {
                    return m;
                }
            });

            function codeSelect(state) {
                var code = state.title;
                if (!code) return state.text;
                return "<img class='image-preview' src='" + code + "' style='width: 20px; height: 20px'>" + state.text;
            }
        });

        $('#code').on('change', function (e) {
            e.preventDefault();
            var name = $('#code option:selected').data('name');
            var code = $('#code option:selected').val();
            if (code == '' || code == null) {
                $('#language_name').val('');
            } else {
                $('#language_name').val(name);
            }
        })
    </script>
@endpush
@include('layouts.ajax.disabled-button-form',[
        'id' => 'language',
        ])


