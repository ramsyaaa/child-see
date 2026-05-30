@extends('admin_new.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Bookings</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Bookings</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">All Bookings</h5>
    </div>
    <div class="card-body">
        {{-- Filters --}}
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}" placeholder="Filter by date">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="booked"     {{ request('status')=='booked'     ? 'selected':'' }}>Booked</option>
                    <option value="completed"  {{ request('status')=='completed'  ? 'selected':'' }}>Completed</option>
                    <option value="cancelled"  {{ request('status')=='cancelled'  ? 'selected':'' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="booking_type" class="form-select">
                    <option value="">All Types</option>
                    <option value="subscription" {{ request('booking_type')=='subscription' ? 'selected':'' }}>Subscription</option>
                    <option value="dropin"       {{ request('booking_type')=='dropin'       ? 'selected':'' }}>Drop-in</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search member name/email...">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Member</th>
                        <th>Class</th>
                        <th>Date & Time</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Check-In</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>
                                <strong>{{ $booking->user->name }}</strong>
                                <br><small class="text-muted">{{ $booking->user->email }}</small>
                            </td>
                            <td>{{ $booking->batchClass->masterClass->name ?? '—' }}</td>
                            <td>
                                {{ $booking->batchClass->date->format('d M Y') }}
                                <br><small>{{ \Carbon\Carbon::parse($booking->batchClass->start_time)->format('H:i') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $booking->booking_type == 'subscription' ? 'info' : 'secondary' }}">
                                    {{ ucfirst($booking->booking_type) }}
                                </span>
                            </td>
                            <td>
                                @if($booking->price > 0)
                                    Rp {{ number_format($booking->price, 0, ',', '.') }}
                                @else
                                    <span class="text-success">Free</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->status == 'booked')
                                    <span class="badge bg-success">Booked</span>
                                @elseif($booking->status == 'completed')
                                    <span class="badge bg-primary">Completed</span>
                                @elseif($booking->status == 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->checked_in_at)
                                    <small class="text-success"><i class="fas fa-check me-1"></i>{{ $booking->checked_in_at->format('H:i') }}</small>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">No bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection
