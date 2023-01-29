@extends('layouts.master')
@section('title',$edit ? _trans('Edit Role') : _trans('Add Role'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ $edit ? _trans('Edit Role') : _trans('Add Role') }}</h3>
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
                    @canany(['Role list','Role edit','Role delete'])
                        <li class="breadcrumb-item ">
                            <a href="{{ route('admin.role.index') }}">{{ _trans('Roles') }}</a>
                        </li>
                    @endcanany
                    <li class="breadcrumb-item active">
                        {{ $edit ? _trans('Edit Role') : _trans('Add Role') }}
                    </li>
                </ol>
            </div>
            <div class="col-md-12">
                @if ($edit)
                    {{ Form::open(['route' => ['admin.role.update',$role->id],'method' => 'PUT','files' => true,'class' => 'form mb-15 form-submit','id' =>'role_form']) }}
                @else
                    {{ Form::open(['route' => 'admin.role.store','method' => 'POST','files' => true,'class' => 'form mb-15','id' =>'role_form']) }}
                @endif
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">{{ _trans('Role Name') }}</label>
                        <input id="name"
                               type="text"
                               name="name"
                               class="form-control @error('code') is-invalid @enderror"
                               placeholder="{{ _trans('Role Name') }}"
                               value="{{ old('name',$edit ? $role->name : null) }}"
                        >
                        @error('name')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <h2 class="mb-0 font-md">{{ _trans('Module Permission')." : " }}</h2>
                    </div>
                    <div class="col-xl-12 col-md-12 ">
                        <h5 class="mb-0 font-md">{{ _trans('Select all') }}</h5>
                        <div class="media-body icon-state">
                            <label class="switch">
                                <input id="selectAll" type="checkbox" name="selectAll" @checked(old('selectAll')) >
                                <span class="switch-state "></span>
                            </label>
                        </div>
                    </div>

                    <div class="col-xl-12 col-md-12 ">
                        @foreach($modules as $row)
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <h3 class="mb-0 font-md">{{ _trans(Str::replace('#',' ',$row)) }}</h3>
                                </div>
                                <div class="col">
                                    <div class="m-t-15 m-checkbox-inline">
                                        @if(!in_array($row,["Report","Setting","Chat","Chart","Rate"]))
                                            <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                                                <input class="form-check-input"
                                                       name="{{ $row." list" }}"
                                                       value="1"
                                                       @if ($edit)
                                                           {{ $role->hasPermissionTo($row." list") ? 'checked' : '' }}
                                                       @endif
                                                       @checked(old($row.'_list') == 1)
                                                       id="{{ $row." list" }}"
                                                       type="checkbox">
                                                <label class="form-check-label" for="{{ $row." list" }}">{{ _trans('List') }} </label>
                                            </div>
                                        @endif
                                        @if(!in_array($row,["Report","Setting","Order","Chat","Chart","Day","Month","Setting#Account","Rate"]))
                                            <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                                                <input class="form-check-input"
                                                       name="{{ $row." add" }}"
                                                       value="1"
                                                       @if ($edit)
                                                           {{ $role->hasPermissionTo($row." add") ? 'checked' : '' }}
                                                       @endif
                                                       @checked(old($row.'_add') == 1)
                                                       id="{{ $row." add" }}"
                                                       type="checkbox">
                                                <label class="form-check-label" for="{{ $row." add" }}">{{ _trans('Add') }}</label>
                                            </div>
                                        @endif

                                        @if(!in_array($row,["Report","Setting","Chat","Chart","Rate","Payment"]))
                                            <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                                                <input class="form-check-input"
                                                       name="{{ $row." edit" }}"
                                                       value="1"
                                                       @if ($edit)
                                                           {{ $role->hasPermissionTo($row." edit") ? 'checked' : '' }}
                                                       @endif
                                                       @checked(old($row.'_edit') == 1)
                                                       id="{{ $row." edit" }}"
                                                       type="checkbox">
                                                <label class="form-check-label" for="{{ $row." edit" }}">{{ _trans('Edit') }}</label>
                                            </div>
                                        @endif

                                        @if(!in_array($row,["Report","Setting","Order","Chat","Chart","Day","Month","Setting#Account","Rate","Payment"]))
                                            <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                                                <input class="form-check-input "
                                                       name="{{ $row." delete" }}"
                                                       value="1"
                                                       @if ($edit)
                                                           {{ $role->hasPermissionTo($row." delete") ? 'checked' : '' }}
                                                       @endif
                                                       @checked(old($row.'_delete') == 1)
                                                       id="{{ $row." delete" }}"
                                                       type="checkbox">
                                                <label class="form-check-label" for="{{ $row." delete" }}">{{ _trans('Delete') }}</label>
                                            </div>
                                        @endif

                                        @if(in_array($row,["Report","Setting","Chat","Chart","Rate"]))
                                            <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                                                <input class="form-check-input "
                                                       name="{{ $row." list" }}"
                                                       value="1"
                                                       @if ($edit)
                                                           {{ $role->hasPermissionTo($row." list") ? 'checked' : '' }}
                                                       @endif
                                                       @checked(old($row.'_list') == 1)
                                                       id="{{ $row." list" }}"
                                                       type="checkbox">
                                                <label class="form-check-label" for="{{ $row." list" }}">{{ _trans('All') }}</label>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="row mt-5">
                            <div class="col-md-12 text-right">
                                <button type="submit" id="btn-role" class="btn btn-primary">{{ $edit ?_trans('Update') : _trans('Save') }}</button>
                            </div>
                        </div>


                    </div>

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            @if(old('selectAll') == 'on')
            $('input:checkbox').not(this).prop('checked', this.checked);
            @endif
        })
        $("#selectAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

    </script>
@endpush
@include('layouts.ajax.disabled-button-form',[
        'id' => 'role',
        ])
