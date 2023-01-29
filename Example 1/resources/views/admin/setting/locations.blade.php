@extends('layouts.master')
@section('title',_trans('Activity Logs'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{_trans('Activity Logs')}}</h3>
                </div>
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
                    <li class="breadcrumb-item active">
                        <a href="#">{{ _trans('Activity Logs') }}</a>
                    </li>
                </ol>
            </div>
            <div class="col-xl-12 col-md-12 ">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="left-side-header">
                        <div class="row justify-content-between align-items-center">
                            <x-search :columns="$columns" sort="true" />
                            <div class="col-md-4">
                                <p class="mb-0 font-sm d-flex align-items-center justify-content-end">{{ _trans('Count Activity Logs') }} :
                                    <span class="d-block font-md text-danger">({{ $activityLogs->total() }})</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive custom-scrollbar p-t-30">
                    <table class="table">
                        <thead>
                        <tr>
                            <th><span>#</span></th>
                            <th><span>{{ _trans('Customer name')}}  </span></th>
                            <th><span>{{ _trans('IP')}}</span></th>
                            <th><span>{{ _trans('Country name')}}</span></th>
                            <th><span>{{ _trans('Region name')}}</span></th>
                            <th><span>{{ _trans('City name')}}</span></th>
                            <th><span>{{ _trans('Date')}}</span></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($activityLogs as $key => $row)
                            <tr>
                                <td>#{{ $row->id }}</td>
                                <td>{{ $row->customer?->user?->name }}</td>
                                <td>{{ $row->ip ?? 'Unknown' }}</td>
                                <td>{{ $row->country_name }}</td>
                                <td>{{ $row->region_name }}</td>
                                <td>{{ $row->city_name }}</td>
                                <td>{{ formatDate('d-m-Y',$row->date) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $activityLogs->appends(request()->query())->links('layouts.partials.pagination') }}

                @if( $activityLogs->count() == 0)
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


