@extends('layouts.master')
@section('title',_trans('Projects'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ _trans('Projects') }}</h3>
                </div>
                @can('Project add')
                    <div class="col-6 text-right">
                        <a href="{{ route('admin.project.create') }}" class="btn btn-primary">
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
                    <li class="breadcrumb-item active"><a href="#">{{ _trans('Projects') }}</a></li>
                </ol>
            </div>

            <div class="col-xl-12 col-md-12 ">

                <form action="{{ url()->current() }}" method="GET">
                    <div class="left-side-header">
                        <div class="row justify-content-between align-items-center">
                            <x-search :columns="$columns" sort="true"/>
                            <div class="col-md-4">
                                <p class="mb-0 font-sm d-flex align-items-center justify-content-end">{{ _trans('Count Projects') }} :
                                    <span class="d-block font-md text-danger">({{ $projects->total() }})</span>
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
                            <th><span>{{ _trans('Image')}}</span></th>
                            <th><span>{{ _trans('Project title')}}</span></th>
                            <th><span>{{ _trans('Company name')}}</span></th>
                            <th><span>{{ _trans('Project status')}}</span></th>
                            <th><span>{{ _trans('Publish')}}</span></th>
                            <th><span>{{ _trans('User name')}}</span></th>
                            <th><span>{{ _trans('Special name')}}</span></th>
                            <th><span>{{ _trans('action')}}</span></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($projects as $key => $row)
                            <tr>
                                <td class="text-main">
                                    <a href="{{ route('admin.project.edit',$row->id) }}">Project#{{ $row->id }}</a>
                                </td>
                                <td>
                                    <img src="{{ getAvatar($row->realEstate?->image) }}" class="mb-2 image-preview">
                                </td>
                                <td>
                                    <a href="{{ route('admin.project.edit',$row->id) }}">{{ $row->realEstate?->translateOrDefault(locale())?->title }}</a>
                                </td>

                                <td>
                                    {{ $row->company?->translateOrDefault(locale())?->name }}
                                </td>
                                <td>{{ $row->status  }}</td>
                                <td>
                                    <div class="media-body icon-state">
                                        <label class="switch">
                                            <input id="{{ $row->id }}" type="checkbox" class="publish" @checked($row->realEstate?->publish == 1) >
                                            <span class="switch-state "></span>
                                        </label>
                                    </div>
                                </td>
                                <td>{{ $row->realEstate?->user?->name  }}</td>
                                <td>
                                    {{ $row->realEstate?->special?->translateOrDefault(locale())?->name }}
                                </td>
                                <td>
                                    @can('Project edit')
                                        <a href="{{ route('admin.project.edit',$row->id) }}" class="btn btn-primary">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $projects->appends(request()->query())->links('layouts.partials.pagination') }}

                @if( $projects->count() == 0)
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

@include('layouts.ajax.update-status',['class' => 'publish','route' => route('admin.project.update-publish')])
