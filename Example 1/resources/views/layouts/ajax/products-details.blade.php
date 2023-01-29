<script>
    let clinicId = "{{ $clinic_id }}";
    let selectedId = "{{ $selectedId }}";

    $(document).ready(function () {
        getProducts(clinicId, selectedId)
    })

    function getProducts(clinicId, selectedId = null) {
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
                        $.each(response.data, function (index, value) {
                            var selectedItem = '';
                            if (selectedId == value.id) {
                                selectedItem = 'selected';
                            }
                            if (selectedId == '' || selectedId == null) {
                                $(".service_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                            } else {
                                $(".service_key_{{$key}}").append('<option ' + selectedItem + ' value="' + value.id + '">' + value.name + '</option>');
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



