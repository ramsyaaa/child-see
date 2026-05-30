@extends('admin_new.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Check-In</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Class Check-In</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Select Date</label>
                <input type="date" name="date" class="form-control" value="{{ $date }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i>View Classes
                </button>
            </div>
        </form>
    </div>
</div>

@if($batchClasses->count() > 0)
    <div class="row g-4">
        @foreach($batchClasses as $class)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="mb-1">{{ $class->masterClass->name }}</h5>
                        <p class="text-muted small mb-3">
                            <i class="fas fa-clock me-1"></i>
                            {{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }}
                            – {{ \Carbon\Carbon::parse($class->end_time)->format('H:i') }}
                            &nbsp;·&nbsp;
                            <i class="fas fa-map-marker-alt me-1"></i>{{ $class->room->room_name ?? 'Studio' }}
                        </p>
                        <p class="text-muted small mb-3">
                            <i class="fas fa-user me-1"></i>{{ $class->instructor->name ?? '—' }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-light text-dark border">
                                {{ $class->capacity - $class->remaining_slots }}/{{ $class->capacity }} booked
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('admin.check-in.show', $class) }}" class="btn btn-primary w-100">
                            <i class="fas fa-qrcode me-2"></i>Open Check-In
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-calendar-times fa-3x text-muted mb-3 d-block opacity-25"></i>
            <p class="text-muted">No active classes scheduled for {{ \Carbon\Carbon::parse($date)->format('d M Y') }}.</p>
        </div>
    </div>
@endif
@endsection
