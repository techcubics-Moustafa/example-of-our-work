<script>
    $(document).on('change', '#clinic_id', function (e) {

        e.preventDefault();
        $(".service_id").empty();
        $(".service_id").append('<option value="">{{ _trans('Select service name') }}</option>');
        var clinicId = $('#clinic_id option:selected').val();
        if (clinicId == '' || clinicId == null) {
            return false;
        } else {
            getServices(clinicId, null)
        }
    });

    function getServices(clinicId, selectedId = null, lasted = false) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            url: "{{ route('ajax.service-by-clinic') }}",
            method: 'POST',
            cache: false,
            data: {
                clinic_id: clinicId
            },
            success: function (response) {
                if (response.status == true) {
                    if (response.data.length > 0) {
                        if (lasted == true){
                            $(".service_id").last().empty();
                            $(".service_id").last().append('<option value="">{{ _trans('Select service name') }}</option>');
                        }
                        $.each(response.data, function (index, value) {
                            var selected = '';
                            if (selectedId == value.id) {
                                selected = 'selected';
                            }
                            if (lasted == true) {
                                $(".service_id").last().append('<option ' + selected + ' value="' + value.id + '">' + value.name + '</option>');
                            } else {
                                $(".service_id").append('<option ' + selected + ' value="' + value.id + '">' + value.name + '</option>');
                            }
                        });
                    }
                } else {
                    toastr.error(response.data)
                }
            },

        });
    }

</script>
