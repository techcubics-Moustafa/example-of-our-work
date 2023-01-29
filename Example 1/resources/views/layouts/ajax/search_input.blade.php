@push('scripts')
    <script>
        $(document).ready(function () {
            @if(request('column_name') == 'status')
            $('.status_select').css('display', 'block');
            $('.search_input').css('display', 'none');
            @else
            $('.status_select').css('display', 'none');
            $('.search_input').css('display', 'block');
            @endif
        })
        $(document).on('change', '#column_name', function (e) {
            e.preventDefault();

            var column_name = $('#column_name option:selected').val();
            if (column_name == 'status') {
                $('.status_select').css('display', 'block');
                $('.search_input').css('display', 'none');
            } else {
                $('.status_select').css('display', 'none');
                $('.search_input').css('display', 'block');
            }
        });
    </script>
@endpush
