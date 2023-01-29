@extends('layouts.master')
@section('title',_trans('Report Comment'))
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6 col-sm-6">
                    <h3>{{ $edit ? _trans('Edit Report Comment') : _trans('Add Report Comment') }}</h3>
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid default-dash">
        <div class="row">

            <div class="row m-t-20">
                <div class="col-12 col-sm-6">
                    <ol class="breadcrumb">
                        <x-link-home />
                        <li class="breadcrumb-item">
                            @canany(['Report#Comment list','Report#Comment edit','Report#Comment delete'])
                                <a href="{{route('admin.report-comment.index')}}">{{ _trans('Report Comments') }}</a>
                            @endcanany
                        </li>
                        <li class="breadcrumb-item active">{{ $edit ? _trans('Edit Report Comment') : _trans('Add Report Comment') }}</li>
                    </ol>
                </div>
                <div class="col-md-12">
                    @if ($edit)
                        {{ Form::open(['route' => ['admin.report-comment.update',$reportComment->id],'method' => 'PUT','files' => true,'class' => 'form mb-15 form-submit','id' =>'kt_contact_form']) }}
                    @else
                        {{ Form::open(['route' => 'admin.report-comment.store','method' => 'POST','files' => true,'class' => 'form mb-15','id' =>'kt_contact_form']) }}
                    @endif

                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="code" class="form-label">{{ _trans('Code') }}</label>
                            <input id="code"
                                   disabled
                                   type="text"
                                   class="form-control"
                                   placeholder="{{ _trans('Code') }}"
                                   value="{{ getCodeTable('Report#Comment','report_comments',$edit,$edit ? $reportComment->id : null) }}"
                            >
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="ranking">{{ _trans('Ranking') }}</label>
                            <input id="ranking"
                                   type="number"
                                   min="0"
                                   step="1"
                                   name="ranking"
                                   class="form-control @error('ranking') is-invalid @enderror"
                                   placeholder="{{ _trans('Ranking') }}"
                                   value="{{ old('ranking',$edit ? $reportComment->ranking : 0) }}"
                            >
                            @error('name')
                            <span class="text-danger">{!! $message !!} </span>
                            @enderror
                        </div>

                        @foreach($languages as $lang)
                            <div class="col-md-12">
                                <label class="form-label" for="{{ $lang['code'] }}[title]">{{ _trans('Report Comment title') }} ({{ ucfirst($lang['code']) }})</label>
                                <input id="{{ $lang['code'] }}[title]"
                                       type="text"
                                       name="{{ $lang['code'] }}[title]"
                                       class="form-control @error($lang['code'].'.title') is-invalid @enderror"
                                       placeholder="{{ _trans('Report Comment title') }}"
                                       value="{{ old($lang['code'].'.title',$edit ? $reportComment->translateOrDefault($lang['code'])?->title : null) }}"
                                       required="">
                                @error($lang['code'].'.title')
                                <span class="text-danger">{!! $message !!} </span>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="m-t-50 d-flex justify-content-end">
                        <button type="submit"
                                class="btn btn-primary">{{ $edit ?_trans('Update') : _trans('Save') }}</button>
                    </div>
                </div>
                {{ Form::close() }}

            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection


