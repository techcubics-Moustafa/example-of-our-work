<div class="card_reset">
    <div class="login-text">
        <div class="logo">
            <img src="{{ getAvatar(Utility::getValByName('web_logo') ) }}">
        </div>
        <h1>{{ _trans('Forgot Password? 🔒') }}</h1>
        <h6>{{ _trans('Enter your email and weill send you instructions to reset your password') }}</h6>

        <form class="flex-c" action="{{ $routeForgetPassword }}" method="post" id="password-email_form">
            @csrf
            <div class="input-box">
                <div class="input">
                    <input type="email" class="@error('email') is-invalid @enderror"
                           placeholder="{{ _trans('E-mail') }} "
                           name="email"
                           required=""
                           value="{{ old('email') }}">
                </div>
                @error('email')
                <span class="text-danger">{!! $message !!} </span>
                @enderror
            </div>

            <div class="">
                <button class="btn-reset" id="btn-password-email" type="submit">{{ _trans('Resend link to email') }} </button>
            </div>
        </form>
    </div>
</div>

