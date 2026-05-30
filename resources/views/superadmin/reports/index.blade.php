@extends('superadmin.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Reports</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Reports & Analytics</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Revenue Report Card -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 hover-card" style="cursor: pointer;" onclick="window.location.href='{{ route('superadmin.reports.revenue') }}'">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-money-bill-wave fa-4x" style="color: #FF6F51;"></i>
                </div>
                <h4 class="mb-2">Revenue Report</h4>
                <p class="text-muted mb-3">View revenue statistics, payment methods, and daily trends</p>
                <a href="{{ route('superadmin.reports.revenue') }}" class="btn btn-primary" style="background-color: #FF6F51; border-color: #FF6F51;">
                    <i class="fas fa-chart-line"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <!-- Attendance Report Card -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 hover-card" style="cursor: pointer;" onclick="window.location.href='{{ route('superadmin.reports.attendance') }}'">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-users fa-4x" style="color: #97B5A9;"></i>
                </div>
                <h4 class="mb-2">Attendance Report</h4>
                <p class="text-muted mb-3">View attendance by class, instructor, and completion rates</p>
                <a href="{{ route('superadmin.reports.attendance') }}" class="btn btn-primary" style="background-color: #97B5A9; border-color: #97B5A9;">
                    <i class="fas fa-chart-bar"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <!-- Subscription Report Card -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 hover-card" style="cursor: pointer;" onclick="window.location.href='{{ route('superadmin.reports.subscriptions') }}'">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-calendar-check fa-4x" style="color: #E3B7B4;"></i>
                </div>
                <h4 class="mb-2">Subscription Report</h4>
                <p class="text-muted mb-3">View active subscriptions, expiring soon, and usage statistics</p>
                <a href="{{ route('superadmin.reports.subscriptions') }}" class="btn btn-primary" style="background-color: #E3B7B4; border-color: #E3B7B4;">
                    <i class="fas fa-chart-pie"></i> View Report
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-light-primary rounded">
                            <h3 class="mb-1" style="color: #FF6F51;">{{ $stats['total_revenue'] ?? 0 }}</h3>
                            <small class="text-muted">Total Revenue (This Month)</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-light-success rounded">
                            <h3 class="mb-1" style="color: #97B5A9;">{{ $stats['total_bookings'] ?? 0 }}</h3>
                            <small class="text-muted">Total Bookings (This Month)</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-light-warning rounded">
                            <h3 class="mb-1" style="color: #E3B7B4;">{{ $stats['active_subscriptions'] ?? 0 }}</h3>
                            <small class="text-muted">Active Subscriptions</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-light-info rounded">
                            <h3 class="mb-1" style="color: #F5DD89;">{{ $stats['total_members'] ?? 0 }}</h3>
                            <small class="text-muted">Total Members</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Transactions</h5>
            </div>
            <div class="card-body">
                @if(isset($recentTransactions) && $recentTransactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Transaction #</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->transaction_number }}</td>
                                        <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $transaction->payment_status == 'verified' ? 'success' : 'warning' }}">
                                                {{ ucfirst($transaction->payment_status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3">No recent transactions</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Upcoming Classes</h5>
            </div>
            <div class="card-body">
                @if(isset($upcomingClasses) && $upcomingClasses->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($upcomingClasses as $class)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $class->masterClass->name }}</strong><br>
                                        <small class="text-muted">{{ $class->date->format('d M Y') }} - {{ date('H:i', strtotime($class->start_time)) }}</small>
                                    </div>
                                    <span class="badge bg-primary">{{ $class->bookings_count ?? 0 }} bookings</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-3">No upcoming classes</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@endsection

@push('styles')
<style>
    .hover-card {
        transition: all 0.3s ease;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>
@endpush

