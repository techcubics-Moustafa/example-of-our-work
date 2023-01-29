@extends('layouts.master')

@section('title',_trans('Languages'))


@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ _trans('Languages') }}</h3>
                </div>
                @can('Language add')
                    <div class="col-6 text-right">
                        <a href="{{ route('admin.language.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                        </a>
                    </div>
                @endcan

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid default-dash">
        <div class="row">
            <div class="col-12 col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </a>
                    </li>
                    @can('Language list')
                        <li class="breadcrumb-item ">
                            <a href="{{ route('admin.language.index') }}">{{ _trans('Languages') }}</a>
                        </li>
                    @endcan
                    <li class="breadcrumb-item active">
                        {{ _trans('Translate')  }} ({{ $lang }})
                    </li>
                </ol>
            </div>

            <div class="col-xl-12 col-md-12 ">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="left-side-header">
                        <div class="row justify-content-between align-items-center">
                            <x-search :columns="$columns"/>
                            <div class="col-md-4">
                                <p class="mb-0 font-sm d-flex align-items-center justify-content-end">{{ _trans('Translations') }} :
                                    <span class="d-block font-md text-danger">({{ $data->total() }})</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive custom-scrollbar p-t-30">
                    <table class="table">
                        <thead>
                        <tr>
                            <th><span>{{ _trans('SL') }}</span></th>
                            <th><span>{{ _trans('Key')}}</span></th>
                            <th><span>{{ _trans('Value')}}</span></th>
                            <th><span>{{ _trans('action')}}</span></th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $count => $row)
                            <tr id="lang-{{$row['key']}}">
                                <td class="text-main">{{ $count + 1 }}</td>
                                <td class="text-main">
                                    <input type="text" name="key[]" value="{{$row['key']}}" hidden>
                                    <label>{{$row['key']}}</label>
                                </td>
                                <td class="text-main">
                                    <input type="text" class="form-control" name="value[]"
                                           id="value-{{$count+1}}"
                                           value="{{$row['value']}}">
                                </td>

                                <td>
                                    <button type="button"
                                            onclick="update_lang('{{$row['key']}}',$('#value-{{$count+1}}').val())"
                                            class="btn btn-primary">{{ _trans('Update') }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $data->appends(request()->query())->links('layouts.partials.pagination') }}

            </div>

        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection

@push('scripts')
    <script type="text/javascript">
        function update_lang(key, value) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: "{{ route('admin.language.translate-submit',[$lang]) }}",
                method: 'POST',
                data: {
                    key: key,
                    value: value
                },
                success: function (response) {
                    if (response.status == true) {
                        toastr.success(response.data, '', option);
                    }
                    toastr.warning(response.data, '', option);
                },

            });
        }
    </script>
@endpush
