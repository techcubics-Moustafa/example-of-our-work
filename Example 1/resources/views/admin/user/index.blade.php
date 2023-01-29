@extends('layouts.master')
@section('title',_trans('Users'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ _trans('Users') }}</h3>
                </div>
                @can('User add')
                    <div class="col-6 text-right">
                        <a href="{{ route('admin.user.create') }}" class="btn btn-primary">
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
                    <li class="breadcrumb-item active">{{ _trans('Users') }}</li>
                </ol>
            </div>

            <div class="col-xl-12 col-md-12 ">

                <form action="{{ url()->current() }}" method="GET">
                    <div class="left-side-header">
                        <div class="row justify-content-between align-items-center">
                            <x-search :columns="$columns"/>
                            <div class="col-md-4">
                                <p class="mb-0 font-sm d-flex align-items-center justify-content-end">{{ _trans('Count Roles') }} :
                                    <span class="d-block font-md text-danger">({{ $users->total() }})</span>
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
                            <th><span>{{ _trans('Date')}}</span></th>
                            <th><span>{{ _trans('User type')}}</span></th>
                            <th><span>{{ _trans('Full name')}}</span></th>
                            <th><span>{{ _trans('Phone')}}</span></th>
                            <th><span>{{ _trans('E-mail')}}</span></th>
                            <th><span>{{ _trans('Country')}}</span></th>
                            <th><span>{{ _trans('City')}}</span></th>
                            <th><span>{{ _trans('Region')}}</span></th>
                            <th><span>{{ _trans('Status')}}</span></th>
                            <th><span>{{ _trans('action')}}</span></th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $key => $row)
                            <tr>
                                <td class="text-main">User#{{ $row->id }}</td>
                                <td>{{ $row->created_at->format('d-m-Y') }}</td>
                                <td>{{ ucfirst($row->user_type) }}</td>
                                {{--<td>{{ $row->user_type == \App\Enums\UserType::Company->value ? $row->company?->translateOrDefault(locale())?->name  : ucfirst($row->user_type) }}</td>--}}
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->phone }}</td>
                                <td>{{ $row->email }}</td>
                                <td>{{ $row->country?->translateOrDefault(locale())?->name }}</td>
                                <td>{{ $row->governorate?->translateOrDefault(locale())?->name }}</td>
                                <td>{{ $row->region?->translateOrDefault(locale())?->name }}</td>
                                <td>
                                    <div class="media-body icon-state">
                                        <label class="switch">
                                            <input id="{{ $row->id }}" type="checkbox" class="status" @checked($row->status == 1) >
                                            <span class="switch-state "></span>
                                        </label>
                                    </div>
                                </td>

                                <td>
                                    @can('User list')
                                        <a href="{{ route('admin.user.show',$row->id) }}" class="btn btn-primary">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                    @can('User edit')
                                        <a href="{{ route('admin.user.edit',$row->id) }}" class="btn btn-primary">
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

                {{ $users->appends(request()->query())->links('layouts.partials.pagination') }}

                @if( $users->count() == 0)
                    <div class="empty-data">
                        <img src="{{ asset('assets')}}/images/nodata.svg">
                        <h4>{{ _trans('No_data_to_show')}}</h4>
                    </div>
                @endif

            </div>

        </div>
    </div>
    <!-- Container-fluid Ends-->

    @include('layouts.partials.change-password',['route' => route('admin.user.change-password'),'name' => 'change_password'])
@endsection

@include('layouts.ajax.update-status',['class' => 'status','route' => route('admin.user.update-status')])
