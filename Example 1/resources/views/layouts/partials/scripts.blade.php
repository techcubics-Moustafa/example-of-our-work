{{--<script src="{{ asset('js/app.js') }}"></script>--}}
<script src="{{ asset('assets') }}/js/jquery-3.5.1.min.js"></script>
<script src="{{ asset('assets') }}/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets') }}/js/feather.min.js"></script>
<script src="{{ asset('assets') }}/js/feather-icon.js"></script>
<script src="{{ asset('assets') }}/js/simplebar.js"></script>
<script src="{{ asset('assets') }}/js/custom.js"></script>
<script src="{{ asset('assets') }}/js/config.js"></script>
<script src="{{ asset('assets') }}/js/sidebar-menu.js"></script>
<script src="{{ asset('assets') }}/js/knob.min.js"></script>
<script src="{{ asset('assets') }}/js/stock-prices.js"></script>
<script src="{{ asset('assets') }}/js/datepicker.js"></script>
<script src="{{ asset('assets') }}/js/datepicker.en.js"></script>
<script src="{{ asset('assets') }}/js/height-equal.js"></script>
<script src="{{ asset('assets') }}/js/moment.min.js"></script>
<script src="{{ asset('assets') }}/js/select2.full.min.js"></script>
<script src="{{ asset('assets') }}/js/select2-custom.js"></script>
<script src="{{ asset('assets') }}/js/script.js"></script>
<script src="{{ asset('assets') }}/js/customizer.js"></script>
<script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
@include('panel.toaster')
<script>
    $(document).bind('ajaxStart', function(){
        $("#overlay-loader").fadeIn(400);
    }).bind('ajaxStop', function(){
        $("#overlay-loader").fadeOut(400);
    });
</script>
