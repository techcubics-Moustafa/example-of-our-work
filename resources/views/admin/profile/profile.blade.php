@extends('layouts.master')

@section('title',_trans('Account'))


@section('content')

    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ _trans('Account') }}</h3>
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
                    <li class="breadcrumb-item active">{{ _trans('Account') }} </li>
                </ol>
            </div>
            <div class="col-md-12">
                <ul class="nav nav-tabs nav-settings" id="myTab" role="tablist">
                    <li class="nav-item"><a class="nav-link active" id="personal-information-tab" data-bs-toggle="tab" href="#personal-information"
                                            role="tab" aria-controls="home" aria-selected="true">{{ _trans('Personal Information') }}</a>
                    </li>

                    <li class="nav-item"><a class="nav-link" id="email-setting-tabs" data-bs-toggle="tab" href="#change-password" role="tab"
                                            aria-controls="change-password" aria-selected="false">{{ _trans('Change password') }}</a></li>
                </ul>
                <div class="tab-content" id="myTabContent">

                    <div class="tab-pane fade show active" id="personal-information" role="tabpanel" aria-labelledby="personal-information-tab">
                        <br>
                        <div class="col-md-12">
                            {{ Form::open(['url' => route('admin.profile.account'),'method' => 'POST','files' => true,'id' => 'profile_form']) }}

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label" for="name">{{ _trans('Full name') }}</label>
                                    <input id="name"
                                           type="text"
                                           name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           placeholder="{{ _trans('Full name') }}"
                                           value="{{ old('name',$admin->name) }}"
                                    >
                                    @error('name')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="email">{{ _trans('Email address') }}</label>
                                    <input id="email"
                                           type="email"
                                           name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           placeholder="{{ _trans('Email address') }}"
                                           value="{{ old('email',$admin->email) }}"
                                    >
                                    @error('email')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="phone">{{ _trans('Phone') }}</label>
                                    <input id="phone"
                                           type="text"
                                           name="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           placeholder="{{ _trans('Phone') }}"
                                           value="{{ old('phone',$admin->phone) }}"
                                    >
                                    @error('phone')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

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

                                <div class="col-md-12">
                                    <div class="m-t-50 d-flex justify-content-end">
                                        <button type="submit" id="btn-profile" class="btn btn-primary">{{ _trans('Update profile') }}</button>
                                    </div>
                                </div>

                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="change-password" role="tabpanel" aria-labelledby="change-password-tab">
                        <br>
                        <div class="col-md-12">
                            {{ Form::open(['url' => route('admin.profile.security'),'method' => 'POST','files' => true,'id' => 'security_form']) }}

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="old_password">{{ _trans('Old password') }}</label>
                                    <div class="input-group">
                                        <input id="old_password"
                                               type="password"
                                               name="old_password"
                                               class="form-control @error('old_password') is-invalid @enderror"
                                               placeholder="{{ _trans('Old password') }}"
                                               value="{{ old('old_password')}}"
                                        >
                                        <div class="show-hide">
                                        <span class="show show_password">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                        </div>
                                    </div>
                                    @error('old_password')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="password">{{ _trans('New password') }}</label>
                                    <div class="input-group">
                                        <input id="password"
                                               type="password"
                                               name="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               placeholder="{{ _trans('New password') }}"
                                        >
                                        <div class="show-hide">
                                        <span class="show show_password">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                        </div>
                                    </div>

                                    @error('password')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="password_confirmation">{{ _trans('Confirm New Password') }}</label>
                                    <div class="input-group">
                                        <input id="password_confirmation"
                                               type="password"
                                               name="password_confirmation"
                                               class="form-control @error('password_confirmation') is-invalid @enderror"
                                               placeholder="{{ _trans('Confirm New Password') }}"
                                        >
                                        <div class="show-hide">
                                        <span class="show show_password">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                        </div>
                                    </div>
                                    @error('password_confirmation')
                                    <span class="text-danger">{!! $message !!} </span>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <div class="m-t-50 d-flex justify-content-end">
                                        <button type="submit" id="btn-security" class="btn btn-primary">{{ _trans('Change password') }}</button>
                                    </div>
                                </div>


                            </div>

                            {{ Form::close() }}


                        </div>
                    </div>

                </div>
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
  'image' => getAvatar($admin->avatar)
  ])
    <script>
        $(document).on('click', '.show_password', function () {
            if ($('#password').attr('type') == 'password') {
                $('#password,#password_confirmation,#old_password').attr('type', 'text')
            } else {
                $('#password,#password_confirmation,#old_password').attr('type', 'password');
            }

        })
    </script>
@endpush
@include('layouts.ajax.disabled-button-form',[
        'id' => 'profile',
        ])
@include('layouts.ajax.disabled-button-form',[
        'id' => 'security',
        ])
