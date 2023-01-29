<div class="card_reset">
    <div class="login-text">
        <div class="logo">
            <img src="{{ getAvatar(Utility::getValByName('web_logo') ) }}">
        </div>
        <h1>{{ _trans('Sign in') }}</h1>

        <form class="flex-c" action="{{ $routeLogin }}" method="post" id="login_form">
            @csrf
            <div class="input-box">
                <div class="input">
                    <input type="email" class="@error('email') is-invalid @enderror"
                           placeholder="{{ _trans('E-mail') }}"
                           name="email"
                           required=""
                           value="{{ old('email') }}"
                    >
                </div>
                @error('email')
                <span class="text-danger">{!! $message !!} </span>
                @enderror
            </div>

            <div class="input-box">
                <div class="input">
                    <input type="password" placeholder="{{ _trans('Password') }}"
                           class="@error('password') is-invalid @enderror"
                           name="password"
                           id="password">
                    <i class="bi bi-eye show_password"></i>
                </div>
                @error('password')
                <span class="text-danger">{!! $message !!} </span>
                @enderror
            </div>

            <div class="input-check">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                           name="remember"
                           {{ old('remember') }}
                           @checked(old('remember') == 'on')
                           id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        {{ _trans('Remember me') }}
                    </label>
                </div>
                <a href="{{ $resetPassword }}" class="forget_password">{{ _trans('Forget password ?') }}</a>
            </div>

            <div class="">
                {{--                    <a href="#" class="btn-reset" type="submit">{{ _trans('Sign in') }}</a>--}}
                <button class="btn-reset" id="btn-login" type="submit">{{ _trans('Sign in') }} </button>
            </div>
        </form>
    </div>
</div>
