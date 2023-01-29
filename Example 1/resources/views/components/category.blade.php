@props([
    'categories' => $categories,
    'model' => $model,
    'scripts' => $scripts,
    'col' => $col,
])
<div class="col-md-{{ $col }}">
    <label class="form-label" for="category_id">
        {{ _trans('Category name') }}
        @can('Category add')
            <a href="{{ route('admin.category.create') }}" target="_blank" class="btn-sm btn-primary">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
            </a>
        @endcan
    </label>
    <select id="category_id"
            name="category_id"
            class="js-example-basic-single @error('category_id') is-invalid @enderror">
        <option value="">{{ _trans('Select category name') }}</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $model?->category_id) == $category->id)>
                {{ $category->translateOrDefault(locale())?->name }}
            </option>
        @endforeach
    </select>
    @error('category_id')
    <span class="text-danger">{!! $message !!} </span>
    @enderror
</div>

<div class="col-md-{{ $col }}">
    <label class="form-label" for="sub_category_id">
        {{ _trans('Sub Category name') }}
        @can('Category add')
            <a href="{{ route('admin.category.create') }}" target="_blank" class="btn-sm btn-primary">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
            </a>
        @endcan
    </label>
    <select id="sub_category_id"
            name="sub_category_id"
            class="js-example-basic-single @error('sub_category_id') is-invalid @enderror">
        <option value="">{{ _trans('Select sub category name') }}</option>
    </select>
    @error('sub_category_id')
    <span class="text-danger">{!! $message !!} </span>
    @enderror
</div>

@push($scripts)
    <script>
        $(document).ready(function () {
            getSubCategories('{{ old('category_id',$model?->category_id) }}', '{{ old('sub_category_id',$model?->sub_category_id)  }}')
        })

        $(document).on('change', '#category_id', function (e) {
            e.preventDefault();
            $("#sub_category_id").empty().append('<option value="">{{ _trans('Select sub category name') }}</option>');
            let categoryId = $('#category_id option:selected').val();
            getSubCategories(categoryId, null)
        });

        function getSubCategories(categoryId, selectedId = null) {
            if (categoryId == '' || categoryId == null) {
                return false;
            } else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    url: "{{ route('ajax.sub-categories-by-category') }}",
                    method: 'POST',
                    cache: false,
                    data: {
                        category_id: categoryId
                    },
                    success: function (response) {
                        if (response.status == true) {
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
        }
    </script>

@endpush
