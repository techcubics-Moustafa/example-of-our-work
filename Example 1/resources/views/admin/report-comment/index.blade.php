@extends('layouts.master')
@section('title',_trans('Report Comments'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{_trans('Report Comments')}}</h3>
                </div>
                @can('Report#Comment add')
                    <div class="col-6 text-right">
                        <a href="{{ route('admin.report-comment.create') }}" class="btn btn-primary">
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
                    <li class="breadcrumb-item"><a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </a></li>
                    <li class="breadcrumb-item active"><a href="#">{{ _trans('Report Comments') }}</a></li>
                </ol>
            </div>
            <div class="col-xl-12 col-md-12 ">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="left-side-header">
                        <div class="row justify-content-between align-items-center">
                            <x-search :columns="$columns"/>
                            <div class="col-md-4">
                                <p class="mb-0 font-sm d-flex align-items-center justify-content-end">{{ _trans('Count Report Comments') }} :
                                    <span class="d-block font-md text-danger">({{ $reportComments->total() }})</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive custom-scrollbar p-t-30">
                    <table class="table">
                        <thead>
                        <tr>
                            <th><span>{{ _trans('Code')}}  </span></th>
                            <th><span>{{ _trans('Date')}} </span></th>
                            <th><span>{{ _trans('Ranking')}} </span></th>
                            <th><span>{{ _trans('Title')}} </span></th>
                            <th><span>{{ _trans('Used')}} </span></th>
                            <th><span>{{ _trans('Status')}} </span></th>
                            <th><span>{{ _trans('action')}}</span></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reportComments as $index => $item)
                            <tr id="row_{{ $item->id }}">
                                <td class="text-main">RC#{{ $item->id}}</td>
                                <td>{{ formatDate('d-m-Y h:i A',$item->created_at)  }}</td>
                                <td>{{ $item->ranking  }}</td>
                                <td>{{ $item->translateOrDefault(locale())?->title }}</td>
                                <td>
                                    {{ $item->report_comment_users_count }}
                                </td>
                                <td>
                                    <div class="media-body icon-state">
                                        <label class="switch">
                                            <input id="{{ $item->id }}" type="checkbox" class="status" @checked($item->status == 1) >
                                            <span class="switch-state ">

                                            </span>
                                        </label>
                                    </div>
                                </td>

                                <td>
                                    @can('Report#Comment edit')
                                        <a href="{{ route('admin.report-comment.edit',$item->id) }}" class="btn btn-primary">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                    @can('Report#Comment delete')
                                        <a onclick="deleteCoupon(event,'{{ $item->id }}')" href="#" class="btn btn-danger">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </a>
                                        {{ Form::open(['url' => route('admin.report-comment.destroy',$item->id),'method' => 'DELETE','id'=>'delete_form_'.$item->id]) }}
                                        {{ Form::close() }}
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $reportComments->appends(request()->query())->links('layouts.partials.pagination') }}

                @if( $reportComments->count() == 0)
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
@include('layouts.ajax.update-status',['class' => 'status','route' => route('admin.report-comment.update-status')])
@push('scripts')
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
                        url: baseUrl + `/admin/report-comment/${id}`,
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
