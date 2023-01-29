<script>
    $(document).on('change', '#category_id', function (e) {
        e.preventDefault();
        var categoryId = $('#category_id option:selected').val();
        if (categoryId == '' || categoryId == null) {
            return false;
        } else {
            getSubCategories(categoryId, null)
        }

    });

    function getSubCategories(categoryId, selectedId = null) {
        $("#sub_category_id").empty();
        $("#sub_category_id").append('<option value="">{{ _trans('Select Sub Category Name') }}</option>');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            url: "{{ route('ajax.sub-category-by-category') }}",
            method: 'POST',
            cache: false,
            data: {
                category_id: categoryId
            },
            success: function (response) {
                if (response.status == true) {
                    console.log(response.data,categoryId);
                    if (response.data.length > 0) {
                        $.each(response.data, function (index, value) {
                            var selected = '';
                            if (selectedId == value.id) {
                                selected = 'selected';
                            }
                            $("#sub_category_id").append('<option ' + selected + ' value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                } else {
                    toastr.error(response.data)
                }
            },

        });
    }

</script>
