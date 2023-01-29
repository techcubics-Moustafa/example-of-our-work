<script>
    $(document).on('change', '#country_id', function (e) {
        e.preventDefault();
        $("#governorate_id,#region_id").empty();
        $("#governorate_id").append('<option value="">{{ _trans('Select Governorate Name') }}</option>');
        $("#region_id").append('<option value="">{{ _trans('Select Region Name') }}</option>');
        var countryId = $('#country_id option:selected').val();
        getGovernorate(countryId, null)
    });

    $(document).on('change', '#governorate_id', function (e) {
        e.preventDefault();
        $("#region_id").empty();
        $("#region_id").append('<option value="">{{ _trans('Select Region Name') }}</option>');
        var governorateId = $('#governorate_id option:selected').val();
        getRegion(governorateId, null)
    });

    function getGovernorate(countryId, selectedId = null) {
        if (countryId == '' || countryId == null) {
            return false;
        } else {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: "{{ route('ajax.governorate-by-country') }}",
                method: 'POST',
                cache: false,
                data: {
                    country_id: countryId
                },
                beforeSend: function () {
                    $("#overlay-loader").fadeIn(400);
                },
                success: function (response) {
                    if (response.status == true) {
                        if (response.data.length > 0) {
                            $.each(response.data, function (index, value) {
                                var selected = '';
                                if (selectedId == value.id) {
                                    selected = 'selected';
                                }
                                $("#governorate_id").append('<option ' + selected + ' value="' + value.id + '">' + value.name + '</option>');
                            });
                        }

                    } else {
                        toastr.error(response.data)
                    }

                },
                complete: function () {
                    $("#overlay-loader").fadeOut(400);
                },
            });
        }
    }

    function getRegion(governorateId, selectedId = null) {
        if (governorateId == '' || governorateId == null) {
            return false;
        } else {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: "{{ route('ajax.region-by-governorate') }}",
                method: 'POST',
                cache: false,
                data: {
                    governorate_id: governorateId
                },
                beforeSend: function () {
                    $("#overlay-loader").fadeIn(400);
                },
                success: function (response) {
                    if (response.status == true) {
                        if (response.data.length > 0) {
                            $.each(response.data, function (index, value) {
                                var selected = '';
                                if (selectedId == value.id) {
                                    selected = 'selected';
                                }
                                $("#region_id").append('<option ' + selected + ' value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    } else {
                        toastr.error(response.data)
                    }

                },
                complete: function () {
                    $("#overlay-loader").fadeOut(400);
                },
            });
        }
    }

    {{--$(document).on('change', '#ranking_id', function (e) {--}}
    {{--    e.preventDefault();--}}
    {{--    $("#subcategory_id").empty();--}}
    {{--    $("#subcategory_id").append('<option value="">{{ _trans('Select Sub Category') }}</option>');--}}
    {{--    var rankingId = $('#ranking_id option:selected').val();--}}
    {{--    if (rankingId == '' || rankingId == null) {--}}
    {{--        return false;--}}
    {{--    } else {--}}
    {{--        getSubCategory(rankingId, null)--}}
    {{--    }--}}

    {{--});--}}

    {{--function getSubCategory(rankingId, selectedId = null) {--}}
    {{--    $.ajax({--}}
    {{--        headers: {--}}
    {{--            'X-CSRF-TOKEN': '{{ csrf_token() }}'--}}
    {{--        },--}}
    {{--        url: "{{ route('ajax.subcategory-by-category') }}",--}}
    {{--        method: 'POST',--}}
    {{--        cache: false,--}}
    {{--        data: {--}}
    {{--            ranking_id: rankingId--}}
    {{--        },--}}
    {{--        success: function (response) {--}}
    {{--            if (response.status == true) {--}}
    {{--                if (response.data.length > 0) {--}}
    {{--                    $.each(response.data, function (index, value) {--}}
    {{--                        var selected = '';--}}
    {{--                        if (selectedId == value.id) {--}}
    {{--                            selected = 'selected';--}}
    {{--                        }--}}
    {{--                        $("#subcategory_id").append('<option ' + selected + ' value="' + value.id + '">' + value.name + '</option>');--}}
    {{--                    });--}}
    {{--                }--}}

    {{--            } else {--}}
    {{--                toastr.error(response.data)--}}
    {{--            }--}}

    {{--        },--}}

    {{--    });--}}
    {{--}--}}

</script>
