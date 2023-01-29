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
                        </a></li>
                    <li class="breadcrumb-item active">{{ _trans('Languages') }}</li>
                </ol>
            </div>

            <div class="col-xl-12 col-md-12 ">

                <form action="{{ url()->current() }}" method="GET">
                    <div class="left-side-header">
                        <div class="row justify-content-between align-items-center">
                            <x-search :columns="$columns"/>
                            <div class="col-md-4">
                                <p class="mb-0 font-sm d-flex align-items-center justify-content-end">{{ _trans('Count Roles') }} :
                                    <span class="d-block font-md text-danger">({{ $languages->total() }})</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive custom-scrollbar p-t-30">
                    <table class="table">
                        <thead>
                        <tr>
                            <th><span>{{ _trans('SL')}}</span></th>
                            <th><span>{{ _trans('Language Name')}}</span></th>
                            <th><span>{{ _trans('Code')}}</span></th>
                            <th><span>{{ _trans('Direction')}}</span></th>
                            <th><span>{{ _trans('Status')}}</span></th>
                            <th><span>{{ _trans('Default')}}</span></th>
                            <th><span>{{ _trans('action')}}</span></th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($languages as $key => $row)
                            <tr id="row_{{ $row->id }}">
                                <td class="text-main">#{{ $key + 1 }}</td>
                                <td>
                                    <a href="{{ route('admin.language.edit',$row->id) }}">{{ $row->name }}</a>
                                </td>
                                <td>{{ strtoupper($row->code) }}</td>
                                <td>{{ strtoupper($row->direction) }}</td>
                                <td>
                                    <div class="media-body icon-state">
                                        <label class="switch">
                                            <input id="{{ $row->id }}" type="checkbox" class="status" @checked($row->status == 1) >
                                            <span class="switch-state "></span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="media-body icon-state">
                                        <label class="switch">
                                            <input id="{{ $row->id }}" type="checkbox" class="default" @checked($row->default == 1) >
                                            <span class="switch-state "></span>
                                        </label>
                                    </div>
                                </td>

                                <td>
                                    @can('Language edit')
                                        <a href="{{ route('admin.language.edit',$row->id) }}" class="btn btn-primary">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{ route('admin.language.translate',$row->code) }}" class="btn btn-primary">
                                            <i class="fa fa-transgender" aria-hidden="true"></i>
                                        </a>
                                    @endcan

                                    @can('Language delete')
                                        @if(!$row->default)
                                            <a onclick="deleteCoupon(event,'{{ $row->id }}')" href="#" class="btn btn-danger">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </a>
                                            {{ Form::open(['url' => route('admin.language.destroy',$row->id),'method' => 'DELETE','id'=>'delete_form_'.$row->id]) }}
                                            {{ Form::close() }}
                                        @endif
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $languages->appends(request()->query())->links('layouts.partials.pagination') }}

                @if($languages->count() == 0)
                    <div class="empty-data">
                        <img src="{{ asset('assets')}}/images/nodata.svg">
                        <h4>{{ _trans('No_data_to_show')}}</h4>
                    </div>
                @endif

            </div>

        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection
@include('layouts.ajax.update-status',['class' => 'status','route' => route('admin.language.update-status')])

@push('scripts')
    <script>
        $(document).on('change', '.default', function () {
            var id = $(this).attr("id");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('admin.language.update-default') }}",
                method: 'POST',
                data: {
                    id: id,
                },
                dataType: 'json',
                cache: false,
                success: function (response) {
                    if (response.status == true) {
                        toastr.success(response.data, '', option);
                        setTimeout(function () {
                            location.reload();
                        }, 100)
                    } else {
                        toastr.error(response.data, '', option);
                        setTimeout(function () {
                            location.reload();
                        }, 100)
                    }
                }
            });
        });
    </script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const baseUrl = "{{ url('/') }}";

        function deleteCoupon(event, id) {
            event.preventDefault();
            let formData = new FormData(document.getElementById('delete_form_' + id));
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
                    $.ajax({
                        url: baseUrl + `/admin/language/${id}`,
                        data: formData,
                        method: 'POST',
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            if (response.status == true) {
                                toastr.success(response.message, '', option);
                                $(`#row_${id}`).remove();
                                Swal.fire(
                                    '{{ _trans('Deleted!') }}',
                                    '{{ _trans('Has been deleted.') }}',
                                    'success'
                                );
                            } else {
                                toastr.error(response.message, '', option);
                            }
                        }
                    });
                }
            })
        }
    </script>
@endpush

