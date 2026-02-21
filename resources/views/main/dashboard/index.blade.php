@extends('templates.backend.master')

@section('page-title', 'Dashboard Overview')
@section('page-link', '/')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card bg-primary-subtle shadow-none position-relative overflow-hidden mb-4">
                <div class="card-body px-4 py-3">
                    <div class="row align-items-center">
                        <div class="col-9">
                            <h4 class="fw-semibold mb-2">Welcome Back, {{ auth()->user()->profile->nama_lengkap ?? auth()->user()->email }}!</h4>
                            <p class="mb-0">You have full access to management features. Keep tracking the payment progress seamlessly.</p>
                        </div>
                        <div class="col-3">
                            <div class="text-center mb-n5">
                                <img src="{{ asset('assets/images/breadcrumb/ChatBc.png') }}" alt="" class="img-fluid mb-n4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 zoom-in bg-primary text-white shadow-primary h-100 mb-4" style="min-height: 120px;">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white bg-opacity-25 rounded-circle p-2 me-3">
                            <iconify-icon icon="solar:calendar-date-bold-duotone" class="fs-7 text-white"></iconify-icon>
                        </div>
                        <h6 class="mb-0 text-white opacity-75">Current Date</h6>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="mb-0 text-white fw-bold">{{ now()->format('l, d F Y') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 col-lg-3">
            <div class="card shadow-none border mb-4">
                <div class="card-body p-4 text-center">
                    <div class="bg-secondary-subtle rounded-circle p-3 d-inline-flex mb-3">
                        <iconify-icon icon="solar:users-group-two-rounded-line-duotone" class="fs-8 text-secondary"></iconify-icon>
                    </div>
                    <h5 class="fw-semibold mb-0">--</h5>
                    <p class="text-muted mb-0">Total Students</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-3">
            <div class="card shadow-none border mb-4">
                <div class="card-body p-4 text-center">
                    <div class="bg-success-subtle rounded-circle p-3 d-inline-flex mb-3">
                        <iconify-icon icon="solar:round-transfer-horizontal-line-duotone" class="fs-8 text-success"></iconify-icon>
                    </div>
                    <h5 class="fw-semibold mb-0">--</h5>
                    <p class="text-muted mb-0">Verified Payments</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-3">
            <div class="card shadow-none border mb-4">
                <div class="card-body p-4 text-center">
                    <div class="bg-warning-subtle rounded-circle p-3 d-inline-flex mb-3">
                        <iconify-icon icon="solar:wallet-2-line-duotone" class="fs-8 text-warning"></iconify-icon>
                    </div>
                    <h5 class="fw-semibold mb-0">--</h5>
                    <p class="text-muted mb-0">Pending Review</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-3">
            <div class="card shadow-none border mb-4">
                <div class="card-body p-4 text-center">
                    <div class="bg-danger-subtle rounded-circle p-3 d-inline-flex mb-3">
                        <iconify-icon icon="solar:hand-money-line-duotone" class="fs-8 text-danger"></iconify-icon>
                    </div>
                    <h5 class="fw-semibold mb-0">--</h5>
                    <p class="text-muted mb-0">Total Revenue</p>
                </div>
            </div>
        </div>
    </div>
@endsection
