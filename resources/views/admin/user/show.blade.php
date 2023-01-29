@extends('layouts.master')
@section('title',_trans('Owner Detail'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ _trans('Owner Details') }}</h3>
                </div>
                <div class="col-6 text-right"></div>
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
                        </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.owner.index') }}">{{ _trans('Owners') }}</a></li>
                    <li class="breadcrumb-item active">{{ _trans('Owner Detail') }}</li>
                </ol>
            </div>

            <div class="col-xl-12 col-md-12 mb-4">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="left-side-header">
                        <div class="row justify-content-between align-items-center">
                            <x-search :columns="$columns"/>
                            <div class="col-md-4">
                                <p class="mb-0 font-sm d-flex align-items-center justify-content-end">{{ _trans('Count Clinics') }} :
                                    <span class="d-block font-md text-danger">({{ $data->total() }})</span>
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
                            <th><span>{{ _trans('Clinic Name')}}</span></th>
                            <th><span>{{ _trans('Specialization')}}</span></th>
                            <th><span>{{ _trans('City Name')}}</span></th>
                            <th><span>{{ _trans('Address')}}</span></th>
                            <th><span>{{ _trans('Services')}}</span></th>
                            <th><span>{{ _trans('Logo')}}</span></th>
                            <th><span>{{ _trans('Rating')}}</span></th>
                            <th><span>{{ _trans('action')}}</span></th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $key => $row)
                            <tr>
                                <td class="text-main"><a href="{{ route('admin.clinic.show',$row->id) }}">Clinic#{{ $row->id }}</a></td>
                                <td>{{$row->created_at->format('d-m-Y') }}</td>
                                <td>{{ $row->translateOrDefault(locale())?->name }}</td>
                                <td>{{ implode(',',$row->specializations->pluck('name')->toArray()) }}</td>
                                <td>{{ $row->governorate?->translateOrDefault(locale())?->name }}</td>
                                <td>{{ Str::limit($row->address,5) }}</td>
                                <td>
                                    <a href="{{ route('admin.service.index').'/?clinic='.$row->translateOrDefault(locale())?->name }}">{{ $row->services_count }}</a>
                                </td>
                                <td>
                                    <img src="{{getAvatar($row->logo) }}" class="mb-2 image-preview">
                                </td>
                                <td>{{$row->getRate() }}</td>

                                <td>
                                    @can('Clinics edit')
                                        <a href="{{ route('admin.clinic.edit',$row->id) }}" class="btn btn-primary">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                    <a href="{{ route('admin.clinic.show',$row->id) }}" class="btn btn-primary">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $data->appends(request()->query())->links('layouts.partials.pagination') }}

                @if( $data->count() == 0)
                    <div class="empty-data">
                        <img src="{{ asset('assets')}}/images/nodata.svg">
                        <h4>{{ _trans('No_data_to_show')}}</h4>
                    </div>
                @endif

            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-details">
                            <h4 class="title-details">{{_trans('Owner Details')}}</h4>
                            <div class="">
                                <p>{{_trans('Name')}} :<span>{{ $owner->user->name }}</span></p>
                                <p>{{_trans('Phone')}} :<span>{{ $owner->phone}}</span></p>
                                <p>{{_trans('Brand name')}} :<span>{{ $owner->brand_name}}</span></p>
                                <p>{{_trans('Country name')}} :<span>{{ $owner?->user?->country->translateOrDefault(locale())?->name }}</span></p>
                                <p>{{_trans('Governorate name')}} :<span>{{ $owner?->user?->governorate->translateOrDefault(locale())?->name }}</span></p>
                                <p>{{_trans('Region name')}} :<span>{{ $owner?->user?->region?->translateOrDefault(locale())?->name }}</span></p>
                                <p>{{_trans('Address name')}} :<span>{{ $owner?->address }}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-details">
                            <h4 class="title-details">{{ _trans('Orders')}}</h4>
                            <div class="">
                                <p>{{ _trans('Count')}} : <span>{{ $orders->count() }}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-details">
                            <h4 class="title-details">{{ _trans('Orders Total Price')}}</h4>
                            <div class="">
                                <p><span>{{$orders->sum('price') - $orders->sum('coupon_price')}}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection
