@extends('layouts.master')
@section('title',_trans('Dashboard'))
@section('content')

    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ _trans('Dashboard') }}</h3>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    {{--<div class="container-fluid default-dash">
        <div class="row">
            <div class="col-xl-4 col-sm-6 box-col-4">
                <div class="card ecommerce-widget">
                    <div class="card-body support-ticket-font">
                        <div class="row">
                            <div class="col-7"><a href="{{ route('admin.owner.index') }}">{{_trans('Owners')}}</a>
                                <a href="{{ route('admin.owner.index') }}">
                                    <h3 class="total-num counter">{{$owners}}</h3>
                                </a>
                            </div>
                            <div class="col-5">
                                <div class="text-end">
                                    <div class="bg-warning product-icon">
                                        <i class="fa fa-users" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="progress-showcase mt-4">
                            <div class="progress sm-progress-bar">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 70%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <small class="text-muted">الفترة السابقة</small>
                                <small class="text-muted">70%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 box-col-4">
                <div class="card ecommerce-widget">
                    <div class="card-body support-ticket-font">
                        <div class="row">
                            <div class="col-7"><a href="{{ route('admin.clinic.index') }}">{{_trans('Clinics')}}</a>
                                <a href="{{ route('admin.clinic.index')}}">
                                    <h3 class="total-num counter">{{$clinic}}</h3>
                                </a>
                            </div>
                            <div class="col-5">
                                <div class="text-end">
                                    <div class="bg-secondary product-icon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="progress-showcase">
                            <div class="progress sm-progress-bar">
                                <div class="progress-bar bg-secondary" role="progressbar" style="width: 70%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <small class="text-muted">الفترة السابقة</small>
                                <small class="text-muted">70%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 box-col-4">
                <div class="card ecommerce-widget">
                    <div class="card-body support-ticket-font">
                        <div class="row">
                            <div class="col-7">
                                <a href="{{ route('admin.currency.index') }}">{{_trans('Customers')}}</a>
                                <a href="{{ route('admin.currency.index') }}">
                                    <h3 class="total-num counter">{{$customers}}</h3>
                                </a>
                            </div>
                            <div class="col-5">
                                <div class="text-end">
                                    <div class="bg-info product-icon">
                                        <i class="fa fa-users" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="progress-showcase mt-4">
                            <div class="progress sm-progress-bar">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 70%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <small class="text-muted">الفترة السابقة</small>
                                <small class="text-muted">70%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-sm-6 box-col-4">
                <div class="card ecommerce-widget">
                    <div class="card-body support-ticket-font">
                        <div class="row">
                            <div class="col-7">
                                <a href="{{ route('admin.service.index') }}">{{_trans('Services')}}</a>
                                <a href="{{ route('admin.service.index') }}">
                                    <h3 class="total-num counter">{{$services}}</h3>
                                </a>
                            </div>
                            <div class="col-5">
                                <div class="text-end">
                                    <div class="bg-warning product-icon">
                                        <i class="fa fa-users" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="progress-showcase mt-4">
                            <div class="progress sm-progress-bar">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 70%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <small class="text-muted">الفترة السابقة</small>
                                <small class="text-muted">70%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-sm-6 box-col-4">
                <div class="card ecommerce-widget">
                    <div class="card-body support-ticket-font">
                        <div class="row">
                            <div class="col-7">
                                <a href="{{ route('admin.special-offer.index') }}">{{_trans('Special Offer')}}</a>
                                <a href="{{ route('admin.special-offer.index') }}">
                                    <h3 class="total-num counter">{{$specialOffer}}</h3>
                                </a>
                            </div>
                            <div class="col-5">
                                <div class="text-end">
                                    <div class="bg-danger product-icon">
                                        <i class="fa fa-bookmark-o" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="progress-showcase">
                            <div class="progress sm-progress-bar">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 70%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <small class="text-muted">الفترة السابقة</small>
                                <small class="text-muted">70%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 box-col-4">
                <div class="card ecommerce-widget">
                    <div class="card-body support-ticket-font">
                        <div class="row">
                            <div class="col-7">
                                <a href="{{ route('admin.medical-offer.index') }}">{{_trans('Medical Offer')}}</a>
                                <a href="{{ route('admin.medical-offer.index') }}">
                                    <h3 class="total-num counter">{{$medicalOffer}}</h3>
                                </a>
                            </div>
                            <div class="col-5">
                                <div class="text-end">
                                    <div class="bg-danger product-icon">
                                        <i class="fa fa-bookmark-o" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="progress-showcase">
                            <div class="progress sm-progress-bar">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 70%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <small class="text-muted">الفترة السابقة</small>
                                <small class="text-muted">70%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 box-col-4">
                <div class="card ecommerce-widget">
                    <div class="card-body support-ticket-font">
                        <div class="row">
                            <div class="col-7">
                                <a href="{{ route('admin.discount.index') }}">{{_trans('Discounts')}}</a>
                                <a href="{{ route('admin.discount.index') }}">
                                    <h3 class="total-num counter">{{$discounts}}</h3>
                                </a>
                            </div>
                            <div class="col-5">
                                <div class="text-end">
                                    <div class="bg-success product-icon">
                                        <i class="fa fa-bookmark-o" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="progress-showcase mt-4">
                            <div class="progress sm-progress-bar">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 70%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <small class="text-muted">الفترة السابقة</small>
                                <small class="text-muted">70%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-sm-6 box-col-4">
                <div class="card ecommerce-widget">
                    <div class="card-body support-ticket-font">
                        <div class="row">
                            <div class="col-7">
                                <a href="{{ route('order.index') }}">{{_trans('All Orders')}}</a>
                                <a href="{{ route('order.index') }}">
                                    <h3 class="total-num counter">{{$all_orders}}</h3>
                                </a>
                            </div>
                            <div class="col-5">
                                <div class="text-end">
                                    <div class="bg-primary product-icon">
                                        <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="progress-showcase">
                            <div class="progress sm-progress-bar">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 70%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <small class="text-muted">الفترة السابقة</small>
                                <small class="text-muted">70%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 box-col-4">
                <div class="card ecommerce-widget">
                    <div class="card-body support-ticket-font">
                        <div class="row">
                            <div class="col-7">
                                <a href="{{ route('order.index').'/?payment_method=cash' }}">{{_trans('Cash Orders')}}</a>
                                <a href="{{ route('order.index').'/?payment_method=cash' }}">
                                    <h3 class="total-num counter">{{$cash_orders}}</h3>
                                </a>
                            </div>
                            <div class="col-5">
                                <div class="text-end">
                                    <div class="bg-success product-icon">
                                        <i class="fa fa-money" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="progress-showcase">
                            <div class="progress sm-progress-bar">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 70%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <small class="text-muted">الفترة السابقة</small>
                                <small class="text-muted">70%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 box-col-4">
                <div class="card ecommerce-widget">
                    <div class="card-body support-ticket-font">
                        <div class="row">
                            <div class="col-7">
                                <a href="{{ route('order.index').'/?payment_method=visa' }}">{{_trans('Credit Orders')}}</a>
                                <a href="{{ route('order.index').'/?payment_method=visa' }}">
                                    <h3 class="total-num counter">{{$credit_orders}}</h3>
                                </a>
                            </div>
                            <div class="col-5">
                                <div class="text-end">
                                    <div class="bg-info product-icon">
                                        <i class="fa fa-credit-card" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="progress-showcase">
                            <div class="progress sm-progress-bar">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 70%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <small class="text-muted">الفترة السابقة</small>
                                <small class="text-muted">70%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-sm-6 box-col-4">
                <div class="card ecommerce-widget">
                    <div class="card-body support-ticket-font">
                        <div class="row">
                            <div class="col-7">
                                <a href="{{ route('admin.clinic.index').'/?subscription_type=percent' }}">{{_trans('Subscriptions By Percent')}}</a>
                                <a href="{{ route('admin.clinic.index').'/?subscription_type=percent' }}">
                                    <h3 class="total-num counter">{{$clinic_by_percent}}</h3>
                                </a>
                            </div>
                            <div class="col-5">
                                <div class="text-end">
                                    <div class="bg-primary product-icon">
                                        <i class="fa fa-percent" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="progress-showcase">
                            <div class="progress sm-progress-bar">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 70%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <small class="text-muted">الفترة السابقة</small>
                                <small class="text-muted">70%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 box-col-4">
                <div class="card ecommerce-widget">
                    <div class="card-body support-ticket-font">
                        <div class="row">
                            <div class="col-7">
                                <a href="{{ route('admin.clinic.index').'/?subscription_type=participation' }}">{{_trans('Subscriptions By Participations')}}</a>
                                <a href="{{ route('admin.clinic.index').'/?subscription_type=participation' }}">
                                    <h3 class="total-num counter">{{$subscription_participation}}</h3>
                                </a>
                            </div>
                            <div class="col-5">
                                <div class="text-end">
                                    <div class="bg-secondary product-icon">
                                        <i class="fa fa-line-chart" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="progress-showcase">
                            <div class="progress sm-progress-bar">
                                <div class="progress-bar bg-secondary" role="progressbar" style="width: 70%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <small class="text-muted">الفترة السابقة</small>
                                <small class="text-muted">70%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 box-col-4">
                <div class="card ecommerce-widget">
                    <div class="card-body support-ticket-font">
                        <div class="row">
                            <div class="col-7">
                                <a href="{{ route('admin.chat.index') }}"><span> {{ _trans('complaints') }}</span></a>
                                <a href="{{ route('admin.chat.index') }}">
                                    <h3 class="total-num counter">{{ $complaints }}</h3>
                                </a>

                            </div>
                            <div class="col-5">
                                <div class="text-end">
                                    <div class="bg-danger product-icon">
                                        <i class="fa fa-motorcycle" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="progress-showcase">
                            <div class="progress sm-progress-bar">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 70%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <small class="text-muted">الفترة السابقة</small>
                                <small class="text-muted">70%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>--}}
    <!-- Container-fluid Ends-->
@endsection

