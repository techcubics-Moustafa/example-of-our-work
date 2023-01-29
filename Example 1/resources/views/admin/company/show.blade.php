@extends('layouts.master')
@section('title',_trans('Clinic Detail'))
@section('content')

    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ _trans('Clinic Detail') }}</h3>
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
                    <li class="breadcrumb-item"><a href="{{ route('admin.clinic.index') }}">{{ _trans('Clinics') }}</a></li>
                    <li class="breadcrumb-item active">{{ _trans('Clinic Detail') }}</li>
                </ol>
            </div>

            <div class="col-xl-12 col-md-12 mb-4">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="left-side-header">
                        <div class="row justify-content-between align-items-center">
                            <x-search :columns="$columns"/>
                            <div class="col-md-4">
                                <p class="mb-0 font-sm d-flex align-items-center justify-content-end">{{ _trans('Count Services') }} :
                                    <span class="d-block font-md text-danger">({{ $services->total() }})</span>
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
                            <th><span>{{ _trans('Category')}} </span></th>
                            <th><span>{{ _trans('Service')}} </span></th>
                            <th><span>{{ _trans('Icon')}} </span></th>
                            <th><span>{{ _trans('Rating')}} </span></th>
                            <th><span>{{ _trans('action')}}</span></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($services as $index => $item)
                            <tr>
                                <td class="text-main"><a href="{{ route('admin.service.show',$item->id) }}">Service#{{$item->id}}</a></td>
                                <td>{{$item->created_at->format('d-m-Y') }}</td>
                                <td>{{$item->category?->translateOrDefault(locale())?->name}}</td>
                                <td>{{$item->translateOrDefault(locale())?->name}}</td>
                                <td>
                                    <img src="{{getAvatar($item->icon) }}" class="mb-2 image-preview">
                                </td>
                                <td>{{ round($item->rates_avg_degree,1) ?? 0 }}</td>

                                <td>
                                    @can('Product edit')
                                        <a href="{{ route('admin.service.edit',$item->id) }}" class="btn btn-primary">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                    <a href="{{ route('admin.service.show',$item->id) }}" class="btn btn-primary">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $services->appends(request()->query())->links('layouts.partials.pagination') }}

                @if( $services->count() == 0)
                    <div class="empty-data">
                        <img src="{{ asset('assets/')}}/images/nodata.svg">
                        <h4>{{ _trans('No_data_to_show')}}</h4>
                    </div>
                @endif
            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-details">
                            <h4 class="title-details">{{_trans('Clinic Details')}}</h4>
                            <div class="">
                                <p>{{_trans('Name')}} :<span>{{$clinic->translateOrDefault(locale())?->name}}</span></p>
                                <p>{{_trans('Owner')}} :<span>{{$clinic->owner?->user?->name}}</span></p>
                                <p>{{_trans('pone')}} :<span>{{$clinic->phone}}</span></p>
                                <p>{{_trans('Subscription')}} :<span>{{ $clinic->subscription_value }} {{$clinic->subscription_type=='participation' ?'':'%'}}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-details">
                            <h4 class="title-details">{{ _trans('Special Offers')}}</h4>
                            <div class="">
                                <p>{{ _trans('Count')}} : <span>{{$clinic->special_offers_count}}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-details">
                            <h4 class="title-details">{{ _trans('Medical Offers')}}</h4>
                            <div class="">
                                <p>{{ _trans('Count')}} : <span>{{$clinic->medical_offers_count}}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-details">
                            <h4 class="title-details">{{ _trans('Discounts')}}</h4>
                            <div class="">
                                <p>{{ _trans('Count')}} : <span>{{ $clinic->discounts_count }}</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-details">
                            <h4 class="title-details">{{ _trans('Coupons')}}</h4>
                            <div class="">
                                <p>{{ _trans('Count')}} : <span>{{$clinic->coupons_count}}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-details">
                            <h4 class="title-details">{{ _trans('Orders')}}</h4>
                            <div class="">
                                <p>{{ _trans('Orders')}} : <span>{{$clinic->orders_count}}</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-details">
                            <h4 class="title-details">{{ _trans('Orders Total Price')}}</h4>
                            <div class="">
                                <p><span>{{ $clinic->orders_sum_price }}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-details">
                            <h4 class="title-details">{{ _trans('Subscription')}}</h4>
                            <div class="">
                                <p><span>{{$subscription}}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-details">
                            <h4 class="title-details">{{ _trans('Total Profit')}}</h4>
                            <div class="">
                                <p><span>{{ $clinic->orders_sum_price ?? 0 - $subscription}}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <!-- add qr code -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card card-details">
                                    <h4 class="title-details">{{ _trans('QR Code') }}</h4>
                                    <img src="{{ getAvatar($clinic->qr_code) }}" class="qr-code">
                                    <a href="{{ route('download.qr-code',encrypt($clinic->id)) }}"
                                       class="download_code">{{ _trans('Download QR Code') }}</a>
                                </div>
                            </div>
                        </div>
                        <!-- end qr code -->
                    </div>

                </div>
            </div>

        </div>
    </div>
    <!-- Container-fluid Ends-->

@endsection
