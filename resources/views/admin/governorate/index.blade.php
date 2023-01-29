@extends('layouts.master')
@section('title',_trans('Governorates'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{_trans('Governorates')}}</h3>
                </div>
                @can('Governorate add')
                    <div class="col-6 text-right">
                        <a href="{{ route('admin.governorate.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                            {{--{{ _trans('Add Governorate') }}--}}
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
                    <li class="breadcrumb-item active">{{ _trans('Governorate') }}</li>
                </ol>
            </div>
            <div class="col-xl-12 col-md-12 ">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="left-side-header">
                        <div class="row justify-content-between align-items-center">
                            <x-search :columns="$columns"/>
                            <div class="col-md-4">
                                <p class="mb-0 font-sm d-flex align-items-center justify-content-end">{{ _trans('Count Governorates') }} :
                                    <span class="d-block font-md text-danger">({{ $governorates->total() }})</span>
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
                            <th><span>{{ _trans('Governorate')}} </span></th>
                            <th><span>{{ _trans('Active')}} </span></th>
                            <th><span>{{ _trans('action')}}</span></th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($governorates as $index => $item)
                            <tr>
                                <td class="text-main">Gov#{{$item->id}}</td>
                                <td>{{$item->created_at->format('d-m-Y') }}</td>
                                <td>{{$item->country?->translateOrDefault(locale())?->name }}</td>
                                <td>{{$item->name }}</td>
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
                                    @can('Governorate edit')
                                        <a href="{{ route('admin.governorate.edit',$item->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $governorates->appends(request()->query())->links('layouts.partials.pagination') }}

                @if( $governorates->count() == 0)
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
@include('layouts.ajax.update-status',['class' => 'status','route' => route('admin.governorate.update-status')])

