@extends('auth.master')

@section('title',_trans('Forgot Password'))

@section('content')
    <x-auth.passwords.email :routeForgetPassword="$routeForgetPassword" :routeLogin="$routeLogin" />
@endsection

@include('layouts.ajax.disabled-button-form',[
        'id' => 'password-email',
        ])

