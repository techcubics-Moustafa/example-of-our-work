@extends('auth.master')

@section('title',_trans('Reset Password'))

@section('content')
    <x-auth.passwords.reset :reset="$reset" :routeReset="$routeReset"/>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '.show_password', function () {
            if ($('#password').attr('type') == 'password') {
                $('#password').attr('type', 'text')
            } else {
                $('#password').attr('type', 'password')
            }

        })
    </script>
@endpush
@include('layouts.ajax.disabled-button-form',[
        'id' => 'password-reset',
        ])

