@extends('layouts.master')
@section('title',_trans('Categories'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ _trans('Categories') }}</h3>
                </div>
                @can('Category add')
                    <div class="col-6 text-right">
                        <a href="{{ route('admin.category.create') }}" class="btn btn-primary">
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
                    <x-link-home />
                    <li class="breadcrumb-item active"><a href="#">{{ _trans('Categories') }}</a></li>
                </ol>
            </div>

            <div class="col-xl-12 col-md-12 ">

                <form action="{{ url()->current() }}" method="GET">
                    <div class="left-side-header">
                        <div class="row justify-content-between align-items-center">
                            <x-search :columns="$columns" sort="true" />
                            <div class="col-md-4">
                                <p class="mb-0 font-sm d-flex align-items-center justify-content-end">{{ _trans('Count Categories') }} :
                                    <span class="d-block font-md text-danger">({{ $categories->total() }})</span>
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
                            <th><span>{{ _trans('Category name')}}</span></th>
                            <th><span>{{ _trans('Category ranking')}}</span></th>
                            <th><span>{{ _trans('Main Category')}}</span></th>
                            <th><span>{{ _trans('Image')}}</span></th>
                            <th><span>{{ _trans('Status')}}</span></th>
                            <th><span>{{ _trans('action')}}</span></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $key => $row)
                            <tr>
                                <td class="text-main">
                                    <a href="{{ route('admin.category.edit',$row->id) }}">Category#{{ $row->id }}</a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.category.edit',$row->id) }}">{{ $row->translateOrDefault(locale())?->name  }}</a>
                                </td>
                                <td>{{ $row->ranking  }}</td>
                                <td>{{ $row->parent_id ? $row->parent->translateOrDefault(locale())?->name : _trans('Main Category') }}</td>
                                <td>
                                    <img src="{{getAvatar($row->image) }}" class="mb-2 image-preview">
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
                                    @can('Category edit')
                                        <a href="{{ route('admin.category.edit',$row->id) }}" class="btn btn-primary">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $categories->appends(request()->query())->links('layouts.partials.pagination') }}

                @if( $categories->count() == 0)
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

@include('layouts.ajax.update-status',['class' => 'status','route' => route('admin.category.update-status')])
