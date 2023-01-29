
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
{!! Toastr::message()  !!}

<script type="text/javascript">
    let option = toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-{{ session('direction') == 'ltr' ? 'right': 'left' }}",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    @if ($errors->any())
    @foreach($errors->all() as $key =>  $error)
    toastr.error('{{ $error}}', '', option);
    @endforeach
    @endif

    @if (session('success'))
    toastr.success("{{ session('success') }}", '', option);
    @elseif(session('error'))
    toastr.error("{{ session('error') }}", '', option);
    @elseif(session('warning'))
    toastr.warning("{{ session('warning') }}", '', option);
    @elseif(session('info'))
    toastr.info("{{ session('info') }}", '', option);
    @endif
</script>
