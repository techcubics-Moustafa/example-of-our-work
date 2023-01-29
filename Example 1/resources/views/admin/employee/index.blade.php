@extends('layouts.master')
@section('title',_trans('Employees'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ _trans('Employees') }}</h3>
                </div>
                @can('Employee add')
                    <div class="col-6 text-right">
                        <a href="{{ route('admin.employee.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus" aria-hidden="true"></i>
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
                    <li class="breadcrumb-item active">{{ _trans('Employees') }}</li>
                </ol>
            </div>

            <div class="col-xl-12 col-md-12 ">

                <form action="{{ url()->current() }}" method="GET">
                    <div class="left-side-header">
                        <div class="row justify-content-between align-items-center">
                            <x-search :columns="$columns"/>
                            <div class="col-md-4">
                                <p class="mb-0 font-sm d-flex align-items-center justify-content-end">{{ _trans('Count Employees') }} :
                                    <span class="d-block font-md text-danger">({{ $employees->total() }})</span>
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
                            <th><span>{{ _trans('Employees name')}}</span></th>
                            <th><span>{{ _trans('Role name')}}</span></th>
                            <th><span>{{ _trans('E-mail')}}</span></th>
                            <th><span>{{ _trans('Phone')}}</span></th>
                            <th><span>{{ _trans('Avatar')}}</span></th>
                            <th><span>{{ _trans('Status')}}</span></th>
                            <th><span>{{ _trans('action')}}</span></th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($employees as $key => $row)
                            <tr>
                                <td class="text-main">Employee#{{ $row->id }}</td>
                                <td>
                                    <a href="{{ route('admin.employee.edit',$row->id) }}">{{ $row->name }}</a>
                                </td>
                                <td>{{  ucfirst($row->role->name) }} </td>
                                <td>{{  $row->email }} </td>
                                <td>{{  $row->phone }} </td>
                                <td>
                                    <img src="{{getAvatar($row->avatar) }}" class="mb-2 image-preview">
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
                                    @can('Employee edit')
                                        <a href="{{ route('admin.employee.edit',$row->id) }}" class="btn btn-primary">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>
                                        <a data-bs-toggle="modal" data-bs-target="#change_password" data-id="{{ $row->id }}"
                                           class="btn btn-primary">
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $employees->appends(request()->query())->links('layouts.partials.pagination') }}

                @if( $employees->count() == 0)
                    <div class="empty-data">
                        <img src="{{ asset('assets')}}/images/nodata.svg">
                        <h4>{{ _trans('No_data_to_show')}}</h4>
                    </div>
                @endif


            </div>

        </div>
    </div>
    <!-- Container-fluid Ends-->

    @include('layouts.partials.change-password',['route' => route('admin.employee.change-password'),'name' => 'change_password'])

@endsection

@include('layouts.ajax.update-status',['class' => 'status','route' => route('admin.employee.update-status')])
