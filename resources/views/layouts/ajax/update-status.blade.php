@push('scripts')
    <script>
        $(document).on('change', '.{{ $class }}', function () {
            var id = $(this).attr("id");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ $route }}",
                method: 'POST',
                data: {
                    id: id,
                },
                dataType: 'json',
                cache: false,
                success: function (response) {
                    if (response.status == true) {
                        toastr.success(response.data, '', option);
                    } else {
                        toastr.error(response.data, '', option);
                        setTimeout(function () {
                            location.reload();
                        }, 100)
                    }
                }
            });
        });
    </script>
@endpush
