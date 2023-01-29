<script>
    $(document).on('change', '#resource_type', function (e) {
        e.preventDefault();
        $("#resource_id").empty();
        var resource = $('#resource_type option:selected').val();
        console.log(resource);
        $("#resource_id_lable").text(resource+' name');
        $("#resource_id").append('<option value="">{{_trans('Select')}}</option>');
        if (resource == '' || resource == null || resource=='Home') {
            return false;
        } else {
            getResourceData(resource, null)
        }

    });

    function getResourceData(resource, selectedId = null) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            url: "{{ route('ajax.getResourceData') }}",
            method: 'POST',
            cache: false,
            data: {
                resource: resource
            },
            success: function (response) {
                if (response.status == true) {
                    $("#resource_id_lable").text(resource+' name');
                    if (response.data.length > 0) {
                        $.each(response.data, function (index, value) {
                            var selected = '';
                            if (selectedId == value.id) {
                                selected = 'selected';
                            }
                            $("#resource_id").append('<option ' + selected + ' value="' + value.id + '">' + value.name + '</option>');
                        });
                    }

                } else {
                    toastr.error(response.error)
                }

            },
        });
    }
</script>
