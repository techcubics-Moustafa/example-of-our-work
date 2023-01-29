<div class="card_reset">
    <div class="login-text">
        <div class="logo">
            <img src="{{ getAvatar(Utility::getValByName('web_logo') ) }}">
        </div>
        <h1>{{ _trans('Reset Password 🔒') }}</h1>
        <h6>{{ _trans('Your new password must be different from previously used passwords') }}</h6>

        <form class="flex-c" action="{{ $routeReset }}" method="post" id="password-reset_form">
            <input type="hidden" name="token" value="{{ $reset->token }}">
            @csrf
            <div class="input-box">
                <div class="input">
                    <input type="email" placeholder="{{ _trans('E-mail') }}"
                           class="@error('email') is-invalid @enderror"
                           name="email"
                           required=""
                           value="{{ old('email',$reset->email) }}">
                </div>
                @error('email')
                <span class="text-danger">{!! $message !!} </span>
                @enderror
            </div>

            <div class="input-box">
                <div class="input">
                    <input type="password" placeholder="{{ _trans('Password') }}"
                           name="password"
                           id="password"
                           class="@error('password') is-invalid @enderror"
                    >
                    <i class="bi bi-eye show_password"></i>
                </div>
                @error('password')
                <span class="text-danger">{!! $message !!} </span>
                @enderror
            </div>
            <div class="input-box">
                <div class="input">
                    <input type="password" placeholder="{{ _trans('Confirm Password') }}"
                           class="@error('password_confirmation') is-invalid @enderror"
                           name="password_confirmation"
                           id="password_confirmation"
                    >
                    <i class="bi bi-eye"></i>
                </div>
                @error('password_confirmation')
                <span class="text-danger">{!! $message !!} </span>
                @enderror
            </div>

            <div class="">
                <button class="btn-reset" id="btn-password-reset" type="submit">{{ _trans('Reset password') }} </button>
            </div>
        </form>

    </div>
</div>
