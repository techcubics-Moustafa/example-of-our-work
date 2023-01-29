<script>
    @if(request('guard') == 'admin')
    $(document).on('change', '#owner_id', function (e) {
        e.preventDefault();
        let ownerId = $('#owner_id option:selected').val();
        getCompanies(ownerId, null)
    });
    @endif

    function getCompanies(ownerId, selectedId = null) {
        $("#company_id").empty().append('<option value="">{{ _trans('Select company name') }}</option>');
        if (ownerId == '' || ownerId == null) {
            return false;
        } else {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: "{{ route('ajax.company-by-owner') }}",
                method: 'POST',
                cache: false,
                data: {
                    owner_id: ownerId
                },
                success: function (response) {
                    if (response.status == true) {
                        if (response.data.length > 0) {
                            $.each(response.data, function (index, value) {
                                let selected = '';
                                if (selectedId == value.id) {
                                    selected = 'selected';
                                }
                                $("#company_id").append('<option ' + selected + ' value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    } else {
                        toastr.error(response.data)
                    }
                },
            });
        }
    }

</script>
