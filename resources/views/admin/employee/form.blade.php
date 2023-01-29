@extends('layouts.master')
@section('title',$edit ? _trans('Edit Employee') : _trans('Add Employee'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ $edit ? _trans('Edit Employee') : _trans('Add Employee') }}</h3>
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
                    @canany(['Employee list','Employee edit','Employee delete'])
                        <li class="breadcrumb-item ">
                            <a href="{{ route('admin.employee.index') }}">{{ _trans('Employees') }}</a>
                        </li>
                    @endcanany
                    <li class="breadcrumb-item active">
                        {{ $edit ? _trans('Edit Employee') : _trans('Add Employee') }}
                    </li>
                </ol>
            </div>
            <div class="col-md-12">
                @if ($edit)
                    {{ Form::open(['route' => ['admin.employee.update',$admin->id],'method' => 'PUT','files' => true,'class' => 'form mb-15 form-submit','id' =>'employee_form']) }}
                @else
                    {{ Form::open(['route' => 'admin.employee.store','method' => 'POST','files' => true,'class' => 'form mb-15','id' =>'employee_form']) }}
                @endif

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="name" class="form-label">{{ _trans('Employee name') }}</label>
                        <input id="name"
                               type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="{{ _trans('Employee name') }}"
                               value="{{ old('name',$edit ? $admin->name : null) }}"
                        >
                        @error('name')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="email">{{ _trans('E-mail') }}</label>
                        <input id="email"
                               type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="{{ _trans('E-mail') }}"
                               value="{{ old('email',$edit ? $admin->email : null) }}"
                        >
                        @error('email')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="phone">{{ _trans('Phone') }}</label>
                        <input id="phone"
                               type="text"
                               name="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               placeholder="{{ _trans('Phone') }}"
                               value="{{ old('phone',$edit ? $admin->phone : null) }}"
                        >
                        @error('phone')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    @if (!$edit)
                        <div class="col-md-4">
                            <label class="form-label" for="password">{{ _trans('Password') }}</label>
                            <input id="password"
                                   type="password"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="{{ _trans('Password') }}"
                                   value="{{ old('password') }}"
                            >
                            @error('password')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="password_confirmation">{{ _trans('Confirm Password') }}</label>
                            <input id="password_confirmation"
                                   type="password"
                                   name="password_confirmation"
                                   class="form-control @error('password_confirmation') is-invalid @enderror"
                                   placeholder="{{ _trans('Confirm Password') }}"
                                   value="{{ old('password_confirmation') }}"
                            >
                            @error('password_confirmation')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>
                    @endif

                    <div class="col-md-4">
                        <label class="form-label" for="role_id">
                            {{ _trans('Role Name') }}
                            @can('Role add')
                                <a href="{{ route('admin.role.create') }}" target="_blank" class="btn-sm btn-primary">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                </a>
                            @endcan
                        </label>
                        <select id="role_id" name="role_id"
                                class="js-example-basic-single @error('role_id') is-invalid @enderror">
                            <option>{{ _trans('Select Role Name') }}</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" @selected(old('role_id',$edit ? $admin->role_id : null) == $role->id)>{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                        @error('role_id')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <div class="mb-1">
                            <label class="form-label">{{ _trans('Avatar') }}</label>
                        </div>
                        <div class="p-2 border border-dashed" style="max-width:230px;">
                            <div class="row" id="avatar"></div>
                        </div>
                        @error('avatar')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <div class="m-t-50 d-flex justify-content-end">
                            <button type="submit" id="btn-employee" class="btn btn-primary">{{ $edit ?_trans('Update') : _trans('Save') }}</button>
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
    'file_name' => 'avatar',
    'image' => $edit ? getAvatar($admin->avatar?->full_file) :  asset('assets/images/img/400x400/img2.jpg')
    ])

@endpush
@include('layouts.ajax.disabled-button-form',[
        'id' => 'employee',
        ])
