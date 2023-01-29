@extends('layouts.master')

@section('title',_trans('Social Media'))


@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ _trans('Social Media') }}</h3>
                </div>
                @can('Social#Media add')
                    <div class="col-6 text-right">
                        <a href="{{ route('admin.social-media.create') }}" class="btn btn-primary">
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
                    <x-link-home/>
                    <li class="breadcrumb-item active"><a href="#">{{ _trans('Social Media') }}</a></li>
                </ol>
            </div>

            <div class="col-xl-12 col-md-12 ">

                <form action="{{ url()->current() }}" method="GET">
                    <div class="left-side-header">
                        <div class="row justify-content-between align-items-center">
                            <x-search :columns="$columns"/>
                            <div class="col-md-4">
                                <p class="mb-0 font-sm d-flex align-items-center justify-content-end">{{ _trans('Count Social Medias') }} :
                                    <span class="d-block font-md text-danger">({{ $socialMedia->total() }})</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive custom-scrollbar p-t-30">
                    <table class="table">
                        <thead>
                        <tr>
                            <th><span>{{ _trans('SL')}} </span></th>
                            <th><span>{{ _trans('Social Media Name')}}</span></th>
                            <th><span>{{ _trans('Icon')}}</span></th>
                            <th><span>{{ _trans('Status')}}</span></th>
                            <th><span>{{ _trans('action')}}</span></th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($socialMedia as $key => $row)
                            <tr id="row_{{ $row->id }}">
                                <td class="text-main">SM#{{ $row->id }}</td>
                                <td>{{ $row->slug  }}</td>
                                <td>
                                    <img src="{{getAvatar($row->icon) }}" class="mb-2 image-preview">
                                </td>
                                <td>
                                    <div class="media-body icon-state">
                                        <label class="switch">
                                            <input id="{{ $row->id }}" type="checkbox" class="status" @checked($row->status == 1) >
                                            <span class="switch-state ">

                                            </span>
                                        </label>
                                    </div>
                                </td>

                                <td>
                                    @can('Social#Media edit')
                                        <a href="{{ route('admin.social-media.edit',$row->id) }}" class="btn btn-primary">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                    @can('Social#Media delete')
                                        <a onclick="deleteCoupon(event,'{{ $row->id }}')" href="#" class="btn btn-danger">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </a>
                                        {{ Form::open(['url' => route('admin.social-media.destroy',$row->id),'method' => 'DELETE','id'=>'delete_form_'.$row->id]) }}
                                        {{ Form::close() }}
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $socialMedia->appends(request()->query())->links('layouts.partials.pagination') }}

                @if( $socialMedia->count() == 0)
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

@include('layouts.ajax.update-status',['class' => 'status','route' => route('admin.social-media.update-status')])

@can('Social#Media delete')
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
                            url: baseUrl + `/admin/social-media/${id}`,
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
@endcan
