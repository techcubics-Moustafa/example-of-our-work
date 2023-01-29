@push('scripts')
    <script>
        $(document).ready(function () {
            $("#overlay-loader").fadeOut(400);
            $('#btn-{{ $id }}').prop('disabled', false);
        })
        $('#{{ $id }}_form').submit(function (e) {
            /* console.log(e)
             e.preventDefault();*/
            $("#overlay-loader").fadeIn(400);
            $('#btn-{{ $id }}').prop('disabled', true);
        });
    </script>
@endpush
