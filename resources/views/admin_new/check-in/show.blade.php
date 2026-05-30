@extends('admin_new.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.check-in.index') }}">Check-In</a></li>
                    <li class="breadcrumb-item" aria-current="page">{{ $batchClass->masterClass->name }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">{{ $batchClass->masterClass->name }}</h5>
                    <small class="text-muted">
                        {{ $batchClass->date->format('d M Y') }} ·
                        {{ \Carbon\Carbon::parse($batchClass->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($batchClass->end_time)->format('H:i') }} ·
                        {{ $batchClass->instructor->name ?? '' }} ·
                        {{ $batchClass->room->room_name ?? 'Studio' }}
                    </small>
                </div>
                <span class="badge bg-light text-dark border">
                    {{ $bookings->where('status','completed')->count() }}/{{ $batchClass->capacity }} checked in
                </span>
            </div>
            <div class="card-body">
                @if($bookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Member</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $i => $booking)
                                    <tr class="{{ $booking->status == 'completed' ? 'table-success' : '' }}">
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            <strong>{{ $booking->user->name }}</strong>
                                            <br><small class="text-muted">{{ $booking->user->email }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $booking->booking_type == 'subscription' ? 'info' : 'secondary' }}">
                                                {{ ucfirst($booking->booking_type) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($booking->status == 'completed')
                                                <span class="text-success">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    Checked in {{ $booking->checked_in_at ? $booking->checked_in_at->format('H:i') : '' }}
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark">Not Yet</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($booking->status == 'booked')
                                                <form action="{{ route('admin.check-in.process', $booking) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check me-1"></i>Check In
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-4">No bookings for this class.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        {{-- Manual check-in --}}
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Manual Check-In</h5></div>
            <div class="card-body">
                <p class="text-muted small mb-3">Check in a member who is present but has no booking record.</p>
                <form action="{{ route('admin.check-in.manual', $batchClass) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Member ID</label>
                        <input type="number" name="user_id" class="form-control @error('user_id') is-invalid @enderror"
                               placeholder="Enter member user ID" required>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-user-check me-2"></i>Manual Check-In
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('admin.check-in.index') }}" class="btn btn-outline-secondary w-100">
                <i class="fas fa-arrow-left me-2"></i>Back to Classes
            </a>
        </div>
    </div>
</div>
@endsection
