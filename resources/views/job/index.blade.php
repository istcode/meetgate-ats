@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Job') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Manage Job') }}</li>
@endsection
@php
    $icons = \App\Models\Utility::get_file('uploads/job/icons/');
@endphp
@push('script-page')
    <script>
        $('.copy_link').click(function(e) {
            e.preventDefault();
            var copyText = $(this).attr('href');

            document.addEventListener('copy', function(e) {
                e.clipboardData.setData('text/plain', copyText);
                e.preventDefault();
            }, true);

            document.execCommand('copy');
            show_toastr('Success', 'Url copied to clipboard', 'success');
        });
    </script>
@endpush
@section('action-button')

    @can('Create Job')
        <a href="{{ route('job.create') }}"  data-ajax-popup="true" data-size="md"
            data-title="{{ __('Create New Job') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection
@section('content')
<div class="row">

    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mb-3 mb-sm-0">
                        <div class="d-flex align-items-center">
                            <div class="badge theme-avtar bg-primary">
                                <i class="ti ti-briefcase"></i>
                            </div>
                            <div class="ms-3">
                                <small class="text-muted">{{__('Total')}}</small>
                                <h6 class="m-0">{{__('Jobs')}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto text-end">
                        <h4 class="m-0">{{$data['total']}}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mb-3 mb-sm-0">
                        <div class="d-flex align-items-center">
                            <div class="badge theme-avtar bg-info">
                                <svg xmlns="{{ asset($icons . 'active.svg') }}" width="40" height="40" viewBox="0 0 40 40">
                                    <rect width="20" height="20" fill="none"></rect>
                                    <image href="{{ asset($icons . 'active.svg') }}" x="0" y="0" width="40" height="40" />
                                </svg>
                            </div>
                            <div class="ms-3">
                                <small class="text-muted">{{__('Active')}}</small>
                                <h6 class="m-0">{{__('Jobs')}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto text-end">
                        <h4 class="m-0">{{$data['active']}}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mb-3 mb-sm-0">
                        <div class="d-flex align-items-center">
                            <div class="badge theme-avtar bg-warning">
                                <svg xmlns="{{ asset($icons . 'inactive.svg') }}" width="20" height="20" viewBox="0 0 40 40">
                                    <rect width="20" height="20" fill="none"></rect>
                                    <image href="{{ asset($icons . 'inactive.svg') }}" x="0" y="0" width="40" height="40" />
                                </svg>
                            </div>
                            <div class="ms-3">
                                <small class="text-muted">{{__('Inactive')}}</small>
                                <h6 class="m-0">{{__('Jobs')}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto text-end">
                        <h4 class="m-0">{{$data['in_active']}}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                {{-- <h5> </h5> --}}
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>{{ __('Branch') }}</th>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Start Date') }}</th>
                                <th>{{ __('End Date') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Created At') }}</th>
                                @if (Gate::check('Edit Job') || Gate::check('Delete Job') || Gate::check('Show Job'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($jobs as $job)
                            <tr>
                                <td>{{ !empty($job->branches) ? $job->branches->name : __('All') }}</td>
                                <td>{{ $job->title }}</td>
                                <td>{{ \Auth::user()->dateFormat($job->start_date) }}</td>
                                <td>{{ \Auth::user()->dateFormat($job->end_date) }}</td>
                                <td>
                                    @if ($job->status == 'active')
                                        <span
                                            class="badge bg-success p-2 px-3  status-badge">{{ App\Models\Job::$status[$job->status] }}</span>
                                    @else
                                        <span
                                            class="badge bg-danger p-2 px-3  status-badge">{{ App\Models\Job::$status[$job->status] }}</span>
                                    @endif
                                </td>
                                <td>{{ \Auth::user()->dateFormat($job->created_at) }}</td>
                                <td class="Action">
                                    @if (Gate::check('Edit Job') || Gate::check('Delete Job') || Gate::check('Show Job'))
                                    <div class="dt-buttons">
                                    <span>
                                        @can('Show Job')
                                        <div class="action-btn bg-warning me-2">
                                            <a href="{{ route('job.show', $job->id) }}"
                                                class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="{{ __('Job Detail') }}">
                                                <span class="text-white"><i class="ti ti-eye"></i></span>
                                            </a>
                                        </div>
                                        @endcan


                                        @can('Edit Job')
                                            <div class="action-btn bg-info me-2">
                                                <a href="{{ route('job.edit', $job->id) }}" class="mx-3 btn btn-sm  align-items-center"
                                                    data-url=""
                                                    data-ajax-popup="true" data-title="{{ __('Edit Job') }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Edit') }}">
                                                    <span class="text-white"><i class="ti ti-pencil"></i></span>
                                                </a>
                                            </div>
                                        @endcan

                                        @can('Delete Job')
                                            <div class="action-btn bg-danger">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['job.destroy', $job->id], 'id' => 'delete-form-' . $job->id]) !!}
                                                <a href="#!" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete') }}">
                                                    <span class="text-white"><i class="ti ti-trash"></i></span></a>
                                                {!! Form::close() !!}
                                            </div>
                                        @endcan
                                    </span>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection