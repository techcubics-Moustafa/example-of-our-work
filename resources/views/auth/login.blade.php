@extends('auth.master')

@section('title',_trans('Login'))

@section('content')
    <x-login :routeLogin="$routeLogin" :resetPassword="$resetPassword" />
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
        'id' => 'login',
        ])

