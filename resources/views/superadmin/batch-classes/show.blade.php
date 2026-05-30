@extends('superadmin.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.batch-classes.index') }}">Batch Classes</a></li>
                    <li class="breadcrumb-item" aria-current="page">Details</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">{{ $batchClass->masterClass->name }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Class Details</h5>
                <div>
                    <a href="{{ route('superadmin.batch-classes.edit', $batchClass) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('superadmin.batch-classes.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Master Class:</strong></div>
                    <div class="col-md-8">{{ $batchClass->masterClass->name }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Instructor:</strong></div>
                    <div class="col-md-8">{{ $batchClass->instructor->name }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Room:</strong></div>
                    <div class="col-md-8">{{ $batchClass->room->room_name }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Date:</strong></div>
                    <div class="col-md-8">{{ $batchClass->date->format('l, d F Y') }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Time:</strong></div>
                    <div class="col-md-8">{{ date('H:i', strtotime($batchClass->start_time)) }} - {{ date('H:i', strtotime($batchClass->end_time)) }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Price:</strong></div>
                    <div class="col-md-8">Rp {{ number_format($batchClass->price, 0, ',', '.') }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Capacity:</strong></div>
                    <div class="col-md-8">{{ $batchClass->capacity }} people</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Available Slots:</strong></div>
                    <div class="col-md-8">
                        <span class="badge {{ $batchClass->remaining_slots > 0 ? 'bg-success' : 'bg-danger' }}">
                            {{ $batchClass->remaining_slots }} slots remaining
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Status:</strong></div>
                    <div class="col-md-8">
                        @if($batchClass->status == 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Cancelled</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Visibility:</strong></div>
                    <div class="col-md-8">
                        <span class="badge {{ $batchClass->visibility == 'public' ? 'bg-info' : 'bg-warning' }}">
                            {{ ucfirst($batchClass->visibility) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Bookings ({{ $batchClass->bookings->count() }})</h5>
            </div>
            <div class="card-body">
                @if($batchClass->bookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Member</th>
                                    <th>Booking Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($batchClass->bookings as $booking)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $booking->user->name }}</td>
                                        <td>{{ $booking->created_at->format('d M Y, H:i') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $booking->status == 'booked' ? 'success' : ($booking->status == 'completed' ? 'primary' : 'secondary') }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">No bookings yet</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Stats</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Total Bookings:</span>
                        <strong class="text-primary">{{ $batchClass->bookings->count() }}</strong>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Confirmed:</span>
                        <strong class="text-success">{{ $batchClass->bookings->whereIn('status', ['booked','completed'])->count() }}</strong>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Occupancy:</span>
                        <strong>{{ $batchClass->capacity > 0 ? round(($batchClass->capacity - $batchClass->remaining_slots) / $batchClass->capacity * 100) : 0 }}%</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

