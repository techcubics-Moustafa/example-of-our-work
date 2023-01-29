<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ _trans('Download Apps') }}</title>
</head>
<body>

<script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}/"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script>
    $(document).ready(function () {

        const device = navigator.userAgent
        let link;
        let brows;
        if (/android/i.test(device)) {
            link = "{{ Utility::getValByName('link_google_play') }}"
            brows = 'Android'
        } else if ((/iPad|iPhone|iPod/.test(device)) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1)) {
            link = "{{ Utility::getValByName('link_apple_store') }}"
            brows = 'iOS'
        } else {
            link = "{{ Utility::getValByName('link_website') }}"
            brows = 'web'
        }
        location.href = link
        console.log(brows)
    })
</script>
</body>
</html>
