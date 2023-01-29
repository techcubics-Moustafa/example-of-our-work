@extends('layouts.master')
@section('title',_trans('Specials'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ _trans('Specials') }}</h3>
                </div>
                @can('Special add')
                    <div class="col-6 text-right">
                        <a href="{{ route('admin.special.create') }}" class="btn btn-primary">
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
                    <li class="breadcrumb-item active"><a href="#">{{ _trans('Specials') }}</a></li>
                </ol>
            </div>

            <div class="col-xl-12 col-md-12 ">

                <form action="{{ url()->current() }}" method="GET">
                    <div class="left-side-header">
                        <div class="row justify-content-between align-items-center">
                            <x-search :columns="$columns" sort="true" />
                            <div class="col-md-4">
                                <p class="mb-0 font-sm d-flex align-items-center justify-content-end">{{ _trans('Count Specials') }} :
                                    <span class="d-block font-md text-danger">({{ $specials->total() }})</span>
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
                            <th><span>{{ _trans('Special name')}}</span></th>
                            <th><span>{{ _trans('Special ranking')}}</span></th>
                            <th><span>{{ _trans('Status')}}</span></th>
                            <th><span>{{ _trans('action')}}</span></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($specials as $key => $row)
                            <tr>
                                <td class="text-main">
                                    <a href="{{ route('admin.special.edit',$row->id) }}">Special#{{ $row->id }}</a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.special.edit',$row->id) }}">{{ $row->translateOrDefault(locale())?->name  }}</a>
                                </td>
                                <td>{{ $row->ranking  }}</td>
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
                                    @can('Special edit')
                                        <a href="{{ route('admin.special.edit',$row->id) }}" class="btn btn-primary">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $specials->appends(request()->query())->links('layouts.partials.pagination') }}

                @if( $specials->count() == 0)
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

@include('layouts.ajax.update-status',['class' => 'status','route' => route('admin.special.update-status')])
