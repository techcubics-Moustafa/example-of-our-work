<!DOCTYPE html>
<html lang="{{ session('local') }}" dir="{{ session('direction') }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ Utility::getValByName('meta_description_'.locale()) }}">
    <meta name="keywords" content="{{ Utility::getValByName('meta_keywords_'.locale()) }}">
    <meta name="author" content="{{ Utility::getValByName('author') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ getAvatar(Utility::getValByName('icon') ) }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ getAvatar(Utility::getValByName('favicon') ) }}" type="image/x-icon">
    <title>{{ Utility::getValByName('company_name_'.locale()) }} | @yield('title') </title>
    @include('layouts.partials.styles')
    @stack('styles')
</head>

<body class="{{ session('direction') }}">
<!-- Loader starts-->
<div class="loader-wrapper">
    <div class="loader show fullscreen" id="ftco-loader">
        <div class="loader-bar"></div>
        <div class="loader-bar"></div>
        <div class="loader-bar"></div>
        <div class="loader-bar"></div>
        <div class="loader-bar"></div>
        <div class="loader-ball"></div>
    </div>
</div>
<div id="overlay-loader">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
</div>
<!-- Loader ends-->
<!-- tap on top starts-->
<div class="tap-top"><i data-feather="chevrons-up"></i></div>
<!-- tap on tap ends-->
<!-- page-wrapper Start-->
<div class="page-wrapper compact-wrapper" id="pageWrapper">
    <!-- Page Header Start-->
    <div class="page-header">
        @includeWhen(auth('admin')->check(),'admin.panel.header')
    </div>
    <!-- Page Header Ends-->
    <!-- Page Body Start-->
    <div class="page-body-wrapper">
        <!-- Page Sidebar Start-->
        <div class="sidebar-wrapper">
            @includeWhen(Auth::guard('admin')->check(),'admin.panel.menu')
        </div>
        <!-- Page Sidebar Ends-->
        <div class="page-body">
            @yield('content')
        </div>
        <!-- footer start-->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 footer-copyright text-center">
                        <p class="mb-0">{{ Utility::getValByName('company_copyright_text') }} </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>

@include('layouts.partials.scripts')
{{--@if(request('guard') == 'web')
    <script>
        const path = "{{ asset('storage/') }}";
        const pathNo = "{{ asset('assets/images/no-image.png') }}";
        let avatar = pathNo;
        let channel = Echo.channel("owners_{{ auth()->user()->id }}");
        channel.listen('.GetNotificationRestaurant', function (data) {
            if (data.notification) {
                setNotification(data.notification)
                var count = $('#notification_owner').text();
                $('#notification_owner').text(parseInt(count) + 1);
            }
        })

        function setNotification(notification) {
            let html = '';
            let message = '';
            var link_href = '#';
            console.log(notification)
            if (notification.notification_type == 'order' || notification.notification_type == 'account_order') {
                link_href = notification.data.order_route;
                if (notification.notification_type == 'order') {
                    message = '{{ _trans('request order new') }}';
                } else {
                    message = '{{ _trans('request account order new') }}';
                }
            }
            if (notification.notification_type == 'message') {
                link_href = notification.data.message_route;
                message = '{{ _trans('New message for customer') }}';
            }
            if (notification.notification_type == 'invoice') {
                link_href = notification.data.invoice_route;
                message = '{{ _trans('Invoice reminder') }}' + notification.data.payment_id;

            }
            html += '<li id="notificationId_' + notification.id + '">';
            html += '<div class="media">';
            html += '<div class="notification-img bg-light-success">';
            html += '<img src="' + notification.customer.avatar + '" alt="" style="width:29px; height:29px">';
            html += '</div>';
            html += '<div class="media-body">';
            html += '<p>' + message + '</p><span>' + notification.created_at + '</span>';
            html += '</div>';
            html += '<div class="notification-right">';
            html += '<a href="#" id="deleteNotification"><i data-feather="x"></i></a>';
            html += '</div>';
            html += '</div>';
            html += '</li>';
            $('#notification_list_owner').append(html)
        }

        function deleteNotification(e, notificationId) {

        }
    </script>
@endif
@if(request('guard') == 'admin')
    <script>
        const path = "{{ asset('storage/') }}";
        const pathNo = "{{ asset('assets/images/no-image.png') }}";
        let avatar = pathNo;
        let channel = Echo.channel("admins_{{ auth()->user()->id }}");
        channel.listen('.GetNotificationAdmin', function (data) {
            if (data.notification) {
                setNotification(data.notification)
                var count = $('#notification_admin').text();
                $('#notification_admin').text(parseInt(count) + 1);
            }
        })

        function setNotification(notification) {
            let html = '';
            let message = '';
            var link_href = '#';
            let avatar = '';
            let customer_name = '';
            if (notification.notification_type == 'join_us') {
                link_href = notification.data.join_us_route;
                customer_name = notification.data.owner_name;
                message = '{{ _trans('Request join us ') }}' + ' ' + customer_name;
                avatar = notification.data.owner_avatar;
            }
            if (notification.notification_type == 'ticket') {
                link_href = notification.data.message_route;
                message = '{{ _trans('Message ticket from') }}';
                avatar = notification.customer.avatar;
                customer_name = notification.customer.name;
            }
            html += '<li id="notificationId_' + notification.id + '">';
            html += '<div class="media">';
            html += '<div class="notification-img bg-light-success">';
            html += '<img src="' + avatar + '" alt="" style="width:29px; height:29px">';
            html += '</div>';
            html += '<div class="media-body">';
            html += '<h5><a class="f-14 m-0" href="' + link_href + '">' + customer_name + '</a></h5>';
            html += '<p>' + message + '</p><span>' + notification.created_at + '</span>';
            html += '</div>';
            html += '</div>';
            html += '</li>';
            $('#notification_list_admin').append(html)
        }
    </script>
@endif--}}
@stack('scripts')
</body>

</html>
