@props([
    'users' => $users,
    'model' => $model,
    'companyId' => $companyId,
    'scripts' => $scripts,
    'col' => $col,
])
<div class="col-md-{{ $col }}">
    <label class="form-label" for="user_id">
        {{ _trans('User name') }}
        @can('User add')
            <a href="{{ route('admin.user.create') }}" target="_blank" class="btn-sm btn-primary">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
            </a>
        @endcan
    </label>
    <select id="user_id"
            name="user_id"
            class="js-example-basic-single @error('user_id') is-invalid @enderror">
        <option value="">{{ _trans('Select user name') }}</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}" @selected(old('user_id', $model?->user_id) == $user->id)>
                {{ $user->name }}
            </option>
        @endforeach
    </select>
    @error('user_id')
    <span class="text-danger">{!! $message !!} </span>
    @enderror
</div>

<div class="col-md-{{ $col }}">
    <label class="form-label" for="company_id">
        {{ _trans('Company name') }}
        @can('Company add')
            <a href="{{ route('admin.company.create') }}" target="_blank" class="btn-sm btn-primary">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
            </a>
        @endcan
    </label>
    <select id="company_id"
            name="company_id"
            class="js-example-basic-single @error('company_id') is-invalid @enderror">
        <option value="">{{ _trans('Select company name') }}</option>
    </select>
    @error('company_id')
    <span class="text-danger">{!! $message !!} </span>
    @enderror
</div>

@push($scripts)
    <script>
        $(document).ready(function () {
            getCompanies('{{ old('user_id',$model?->user_id) }}', '{{ old('company_id',$companyId)  }}')
        })

        $(document).on('change', '#user_id', function (e) {
            e.preventDefault();
            $("#company_id").empty().append('<option value="">{{ _trans('Select company name') }}</option>');
            let userId = $('#user_id option:selected').val();
            getCompanies(userId, null)
        });

        function getCompanies(userId, selectedId = null) {
            if (userId == '' || userId == null) {
                return false;
            } else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    url: "{{ route('ajax.companies-by-user') }}",
                    method: 'POST',
                    cache: false,
                    data: {
                        user_id: userId
                    },
                    success: function (response) {
                        if (response.status == true) {
                            if (response.data.length > 0) {
                                $.each(response.data, function (index, value) {
                                    var selected = '';
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

@endpush
