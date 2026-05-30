@extends('admin_new.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.bookings.index') }}">Bookings</a></li>
                    <li class="breadcrumb-item" aria-current="page">Booking #{{ $booking->id }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Booking #{{ $booking->id }}</h5>
                @if($booking->status == 'booked')
                    <span class="badge bg-success">Booked</span>
                @elseif($booking->status == 'completed')
                    <span class="badge bg-primary">Completed</span>
                @elseif($booking->status == 'cancelled')
                    <span class="badge bg-danger">Cancelled</span>
                @endif
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1 small">Member</p>
                        <strong>{{ $booking->user->name }}</strong>
                        <br><small class="text-muted">{{ $booking->user->email }}</small>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1 small">Booking Type</p>
                        <span class="badge bg-{{ $booking->booking_type == 'subscription' ? 'info' : 'secondary' }}">
                            {{ ucfirst($booking->booking_type) }}
                        </span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1 small">Class</p>
                        <strong>{{ $booking->batchClass->masterClass->name ?? '—' }}</strong>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1 small">Instructor</p>
                        {{ $booking->batchClass->instructor->name ?? '—' }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1 small">Date</p>
                        {{ $booking->batchClass->date->format('d M Y') }}
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1 small">Time</p>
                        {{ \Carbon\Carbon::parse($booking->batchClass->start_time)->format('H:i') }}
                        – {{ \Carbon\Carbon::parse($booking->batchClass->end_time)->format('H:i') }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1 small">Room</p>
                        {{ $booking->batchClass->room->room_name ?? '—' }}
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1 small">Price Paid</p>
                        @if($booking->price > 0)
                            <strong>Rp {{ number_format($booking->price, 0, ',', '.') }}</strong>
                        @else
                            <span class="text-success">Free (via subscription)</span>
                        @endif
                    </div>
                </div>
                @if($booking->subscription)
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="text-muted mb-1 small">Subscription Used</p>
                            {{ $booking->subscription->product->name ?? 'Subscription #'.$booking->subscription_id }}
                        </div>
                    </div>
                @endif
                @if($booking->checked_in_at)
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="text-muted mb-1 small">Checked In At</p>
                            <span class="text-success"><i class="fas fa-check me-1"></i>{{ $booking->checked_in_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Actions</h5></div>
            <div class="card-body d-grid gap-2">
                @if($booking->status == 'booked')
                    <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="btn btn-danger w-100"
                                onclick="return confirm('Cancel this booking? Slots and quota will be restored.')">
                            <i class="fas fa-times me-2"></i>Cancel Booking
                        </button>
                    </form>
                @endif
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Bookings
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
