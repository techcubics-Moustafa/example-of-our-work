@extends('layouts.master')
@section('title',$edit ? _trans('Edit User') : _trans('Add User'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ $edit ? _trans('Edit User') : _trans('Add User') }}</h3>
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid default-dash">
        <div class="row">
            <div class="col-12 col-sm-6">
                <ol class="breadcrumb">
                    <x-link-home />
                    @canany(['User list','User edit','User delete'])
                        <li class="breadcrumb-item ">
                            <a href="{{ route('admin.user.index') }}">{{ _trans('Users') }}</a>
                        </li>
                    @endcanany
                    <li class="breadcrumb-item active">
                        {{ $edit ? _trans('Edit User') : _trans('Add User') }}
                    </li>
                </ol>
            </div>

            <div class="col-md-12">
                @if ($edit)
                    {{ Form::open(['route' => ['admin.user.update',$user->id],'method' => 'PUT','files' => true,'class' => 'form mb-15 form-submit','id' =>'user_form']) }}
                @else
                    {{ Form::open(['route' => 'admin.user.store','method' => 'POST','files' => true,'class' => 'form mb-15','id' =>'user_form']) }}
                @endif

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label" for="code">{{ _trans('User Code') }}</label>
                        <input id="code"
                               disabled
                               type="text"
                               class="form-control"
                               placeholder="{{ _trans('User Code') }}"
                               value="{{ getCodeTable('User','users',$edit,$edit ? $user->id : null) }}"
                        >
                    </div>

                    @if ($edit)
                        <div class="col-md-4">
                            <label class="form-label" for="date">{{ _trans('Date') }}</label>
                            <input id="date"
                                   disabled
                                   type="text"
                                   class="form-control"
                                   value="{{ $user->created_at->format('d-m-Y  h:i A') }}"
                            >
                        </div>
                    @endif

                    <div class="col-md-4">
                        <label class="form-label" for="user_type">{{ _trans('User type') }}</label>
                        <select id="user_type"
                                name="user_type"
                                class="js-example-basic-single @error('user_type') is-invalid @enderror">
                            <option value="">{{ _trans('Select user type') }}</option>
                            @foreach(\App\Enums\UserType::cases() as $type)
                                <option value="{{ $type->value }}"
                                    @selected(old('user_type',$edit ? $user->user_type : null) == $type->value)
                                >{{ _trans($type->name) }}</option>
                            @endforeach
                        </select>
                        @error('user_type')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="first_name">{{ _trans('First name') }}</label>
                        <input id="first_name"
                               type="text"
                               name="first_name"
                               class="form-control @error('first_name') is-invalid @enderror"
                               placeholder="{{ _trans('Enter first name') }}"
                               value="{{ old('first_name',$edit ? $user->first_name : null) }}"
                        >
                        @error('first_name')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="last_name">{{ _trans('Last name') }}</label>
                        <input id="last_name"
                               type="text"
                               name="last_name"
                               class="form-control @error('last_name') is-invalid @enderror"
                               placeholder="{{ _trans('Enter last name') }}"
                               value="{{ old('last_name',$edit ? $user->last_name : null) }}"
                        >
                        @error('last_name')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="email">{{ _trans('E-mail address') }}</label>
                        <input id="email"
                               type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="{{ _trans('Enter E-mail address') }}"
                               value="{{ old('email',$edit ? $user->email : null) }}"
                        >
                        @error('email')
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
                                   placeholder="{{ _trans('Enter Password') }}"
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
                                   placeholder="{{ _trans('Enter Confirm Password') }}"
                                   value="{{ old('password_confirmation') }}"
                            >
                            @error('password_confirmation')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>
                    @endif

                    <div class="col-md-4">
                        <label class="form-label" for="phone">{{ _trans('Phone number') }}</label>
                        <input id="phone"
                               type="text"
                               name="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               placeholder="{{ _trans('Enter Phone number') }}"
                               value="{{ old('phone',$edit ? $user->phone : null) }}"
                        >
                        @error('phone')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>

                    <x-country :countries="$countries" :model="$edit ? $user : null" scripts="scripts" col="4"/>

                    <div class="col-md-4">
                        <label class="form-label" for="address">{{ _trans('Address') }}</label>
                        <input id="address"
                               type="text"
                               name="address"
                               class="form-control @error('address') is-invalid @enderror"
                               placeholder="{{ _trans('Address') }}"
                               value="{{ old('address',$edit ? $user->address : null) }}"
                        >
                        @error('address')
                        <span class="text-danger">{!! $message !!} </span>
                        @enderror
                    </div>


                    <div class="col-md-4">
                        <div class="col-md-6">
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

                    </div>

                    <div class="col-md-12">
                        <div class="m-t-50 d-flex justify-content-end">
                            <button type="submit" id="btn-user" class="btn btn-primary">{{ $edit ?_trans('Update') : _trans('Save') }}</button>
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
    'image' => $edit ? getAvatar($user->avatar) :  asset('assets/images/img/400x400/img2.jpg')
    ])
@endpush
@include('layouts.ajax.disabled-button-form',[
        'id' => 'user',
        ])


