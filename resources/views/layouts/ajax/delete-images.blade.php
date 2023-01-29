<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(".{{ $class_name }}").on('click', function (e) {
        e.preventDefault();
        var obj = $(this);
        var imageId = obj.attr('id');
        if (imageId == '' || imageId == null) {
            return false;
        } else {
            Swal.fire({
                title: '{{ _trans('Are you sure?') }}',
                text: "{{ _trans('You won\'\t be able to revert this!') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ _trans('Yes, delete it!') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteImage(imageId)
                }
            })

        }
    });

    function deleteImage(imageId) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            url: "{{ $route }}",
            method: 'POST',
            cache: false,
            data: {
                id: imageId,
                relation_id: "{{ $relation_id }}",
            },
            success: function (response) {
                if (response.status == true) {
                    $('#image_' + imageId).remove();
                } else {
                    toastr.error(response.data)
                }

            },

        });
    }
</script>
