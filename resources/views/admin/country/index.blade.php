@extends('layouts.master')
@section('title',_trans('Countries'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{_trans('Countries')}}</h3>
                </div>
                @can('Country add')
                    <div class="col-6 text-right">
                        <a href="{{ route('admin.country.create') }}" class="btn btn-primary">
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
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">{{ _trans('Country') }}</li>
                </ol>
            </div>
            <div class="col-xl-12 col-md-12 ">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="left-side-header">
                        <div class="row justify-content-between align-items-center">
                            <x-search :columns="$columns"/>
                            <div class="col-md-4">
                                <p class="mb-0 font-sm d-flex align-items-center justify-content-end">{{ _trans('Count Countries') }} :
                                    <span class="d-block font-md text-danger">({{ $countries->total() }})</span>
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
                            <th><span>{{ _trans('Country')}} </span></th>
                            <th><span>{{ _trans('Code')}} </span></th>
                            <th><span>{{ _trans('Currency')}} </span></th>
                            <th><span>{{ _trans('Active')}} </span></th>
                            <th><span>{{ _trans('action')}}</span></th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($countries as $index => $item)
                            <tr>
                                <td class="text-main">Country#{{$item->id}}</td>
                                <td>{{ $item->created_at->format('d-m-Y')}}</td>
                                <td>{{ $item->translateOrDefault(locale())?->name }}</td>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->currency?->code }}</td>
                                <td>
                                    <div class="media-body icon-state">
                                        <label class="switch">
                                            <input id="{{ $item->id }}" type="checkbox" class="status" @checked($item->status == 1) >
                                            <span class="switch-state "></span>
                                        </label>
                                    </div>
                                </td>

                                <td>
                                    @can('Country edit')
                                        <a href="{{ route('admin.country.edit',$item->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $countries->appends(request()->query())->links('layouts.partials.pagination') }}

                @if( $countries->count() == 0)
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
@include('layouts.ajax.update-status',['class' => 'status','route' => route('admin.country.update-status')])
