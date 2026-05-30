@extends('admin_new.layout.master')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Admin Dashboard</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-6 col-xl-4">
        <div class="card dashnum-card dashnum-card-small overflow-hidden">
            <span class="round bg-primary small"></span>
            <span class="round bg-primary big"></span>
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="avtar avtar-lg">
                        <i class="fas fa-calendar-check text-primary"></i>
                    </div>
                    <div class="ms-2">
                        <h4 class="mb-1">{{ $todayClasses ?? 0 }}</h4>
                        <p class="mb-0 opacity-50 text-sm">Today's Classes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card dashnum-card dashnum-card-small overflow-hidden">
            <span class="round bg-warning small"></span>
            <span class="round bg-warning big"></span>
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="avtar avtar-lg">
                        <i class="fas fa-bookmark text-warning"></i>
                    </div>
                    <div class="ms-2">
                        <h4 class="mb-1">{{ $todayBookings ?? 0 }}</h4>
                        <p class="mb-0 opacity-50 text-sm">Today's Bookings</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card dashnum-card dashnum-card-small overflow-hidden">
            <span class="round bg-danger small"></span>
            <span class="round bg-danger big"></span>
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="avtar avtar-lg">
                        <i class="fas fa-credit-card text-danger"></i>
                    </div>
                    <div class="ms-2">
                        <h4 class="mb-1">{{ $pendingPayments ?? 0 }}</h4>
                        <p class="mb-0 opacity-50 text-sm">Pending Payments</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5>Today's Schedule</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Class</th>
                                <th>Instructor</th>
                                <th>Room</th>
                                <th>Bookings</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" class="text-center text-muted">No classes scheduled for today</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection

