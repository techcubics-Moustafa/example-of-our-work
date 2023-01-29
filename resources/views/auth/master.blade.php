<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="pixelstrap">
    <meta name="description"
          content="{{ Utility::getValByName('meta_description_'.locale()) }}">
    <meta name="keywords"
          content="{{ Utility::getValByName('meta_keywords_'.locale()) }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ getAvatar(Utility::getValByName('icon') ) }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ getAvatar(Utility::getValByName('favicon') ) }}" type="image/x-icon">
    <title> {{ Utility::getValByName('company_name_'.locale()) }} | @yield('title') </title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/loader.css') }}">

    <style>

        @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css");
        @import url("https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700&display=swap");



        * {
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
            font-family: "Almarai", sans-serif !important;
        }


        .card_reset {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: rgb(125, 50, 133,0.09);
        }

        .login-text {
            background: #fff;
            box-shadow: 0 10px 34px -15px rgb(0 0 0 / 24%);
            min-width: 500px;
            border-radius: 20px;
            padding: 20px 20px 20px;
            margin: 93px 0 0;
            direction: ltr;
        }

        .logo {
            box-shadow: 0 10px 34px -15px rgb(0 0 0 / 24%);
            display: flex;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background:#fff;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: -99px auto 20px;
        }
        .logo img{
            width: 100px;
        }
        .login-text h1 {
            font-size: 18px;
            text-align: center;
            margin-bottom: 30px;
        }

        form {
            align-items: flex-start !important;
            width: 100%;
            margin-top: 15px;
        }
        .input{
            position: relative;
        }
        .input-box {
            margin:0 0 15px;
            width: 100%;
        }
        .input input{
            background: #fff;
            border: 1px solid #ececec;
            height: 50px;
            -webkit-box-shadow: none;
            box-shadow: none;
            padding: 0 10px;
            font-size: 13px;
            width: 100%;
            border-radius: 10px;
            flex: 1;
        }

        .input i {
            color: rgba(0, 0, 0, 0.4);
            position: absolute;
            right: 15px;
            top: 16px;
            cursor: pointer;
        }

        .btn-reset {
            background: #00569d;
            border:1px solid #00569d;
            text-decoration: none;
            color: #ffffff;
            border-radius: 30px;
            padding: 10px 15px;
            display: block;
            position: relative;
            font-size: 16px;
            margin: 40px 0 0;
            text-align: center;
            transition: all 0.3s linear;
            width:100%;
        }
        .btn-reset:hover{
            background: transparent;
            color:#00569d
        }
        .btn-reset:hover {
            transform: translateY(-2px);
        }
        .input-check{
            margin: 20px 0;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
        .forget_password{
            text-decoration: none;
            color: #8f2e82;
            font-size: 14px;
        }

    </style>

</head>

<body>
<div id="overlay-loader">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
</div>
@yield('content')
<script src="{{ asset('assets') }}/js/jquery-3.5.1.min.js"></script>
@include('panel.toaster')
@stack('scripts')
</body>
</html>
