@extends('layouts.master')
@section('title',_trans('Roles'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ _trans('Roles') }}</h3>
                </div>
                @can('Role add')
                    <div class="col-6 text-right">
                        <a href="{{ route('admin.role.create') }}" class="btn btn-primary">
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
                    <li class="breadcrumb-item active">{{ _trans('Roles') }}</li>
                </ol>
            </div>

            <div class="col-xl-12 col-md-12 ">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="left-side-header">
                        <div class="row justify-content-between align-items-center">
                            <x-search :columns="$columns"/>
                            <div class="col-md-4">
                                <p class="mb-0 font-sm d-flex align-items-center justify-content-end">{{ _trans('Count Roles') }} :
                                    <span class="d-block font-md text-danger">({{ $roles->total() }})</span>
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
                            <th><span>{{ _trans('Role Name')}}</span></th>
                            <th><span>{{ _trans('Count Staff')}}</span></th>
                            <th><span>{{ _trans('Count Permission')}}</span></th>
                            <th><span>{{ _trans('action')}}</span></th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($roles as $key => $row)
                            <tr>
                                <td class="text-main"> {{ $key + 1 }}</td>
                                <td>
                                    <a href="{{ route('admin.role.edit',$row->id) }}">{{ ucfirst($row->name) }}</a>
                                </td>
                                <td>{{ $row->users_count }}</td>
                                <td>{{ $row->permissions_count }}</td>
                                <td>
                                    @can('Role edit')
                                        <a href="{{ route('admin.role.edit',$row->id) }}" class="btn btn-primary">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                    @can('Role delete')
                                        <a onclick="return confirm('{{ _trans('Are you sure you want to delete ?') }}');" href="{{ route('admin.role.destroy',$row->id) }}" class="btn btn-primary">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $roles->appends(request()->query())->links('layouts.partials.pagination') }}

                @if( $roles->count() == 0)
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
