@extends('superadmin.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.members.index') }}">Members</a></li>
                    <li class="breadcrumb-item" aria-current="page">{{ $member->name }}</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">{{ $member->name }}</h2>
                </div>
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

<div class="row g-4">

    {{-- Profile --}}
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body text-center py-4">
                <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center text-white"
                     style="width:72px;height:72px;font-size:28px;font-weight:600;background:linear-gradient(135deg,#3d2410,#C4923A);font-family:'Playfair Display',serif">
                    {{ strtoupper(substr($member->name,0,1)) }}
                </div>
                <h5 class="mb-0">{{ $member->name }}</h5>
                <p class="text-muted small mb-2">{{ $member->email }}</p>
                @if($member->phone)
                    <p class="text-muted small mb-2"><i class="fas fa-phone me-1"></i>{{ $member->phone }}</p>
                @endif
                @php $st = strtolower($member->status ?? 'active'); @endphp
                <span class="badge {{ $st==='active' ? 'bg-success' : ($st==='suspended' ? 'bg-danger' : 'bg-secondary') }} fs-6 px-3">
                    {{ ucfirst($st) }}
                </span>
                <p class="text-muted small mt-2 mb-0">Member since {{ $member->created_at->format('d M Y') }}</p>
            </div>
            <div class="card-footer bg-transparent d-grid gap-2">
                <a href="{{ route('superadmin.members.edit', $member) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Edit Profile
                </a>
                <form action="{{ route('superadmin.members.toggle-status', $member) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-outline-{{ $st==='active' ? 'warning' : 'success' }} w-100"
                            onclick="return confirm('{{ $st==='active' ? 'Deactivate' : 'Activate' }} this member?')">
                        <i class="fas fa-{{ $st==='active' ? 'ban' : 'check' }} me-2"></i>
                        {{ $st === 'active' ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
                <a href="{{ route('superadmin.members.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Members
                </a>
            </div>
        </div>

        {{-- Active Subscription --}}
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Active Subscription</h5></div>
            <div class="card-body">
                @if($activeSubscription)
                    <p class="fw-semibold mb-1">{{ $activeSubscription->product->name ?? 'Package' }}</p>
                    <p class="text-muted small mb-1">Expires: {{ $activeSubscription->end_date ? \Carbon\Carbon::parse($activeSubscription->end_date)->format('d M Y') : 'N/A' }}</p>
                    @if($activeSubscription->quota_allocated === null)
                        <span class="badge bg-info text-dark">Unlimited Sessions</span>
                    @else
                        <span class="badge bg-secondary">
                            {{ $activeSubscription->getRemainingQuota() ?? 0 }} / {{ $activeSubscription->quota_allocated }} remaining
                        </span>
                    @endif
                @else
                    <p class="text-muted mb-0">No active subscription.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Activity --}}
    <div class="col-md-8">

        {{-- Recent bookings --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Bookings</h5>
                <a href="{{ route('superadmin.bookings.index', ['user_id' => $member->id]) }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                @forelse($member->bookings as $booking)
                    <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
                        <div>
                            <p class="mb-0 fw-semibold">{{ $booking->batchClass->masterClass->name ?? '—' }}</p>
                            <small class="text-muted">
                                {{ $booking->batchClass->date?->format('d M Y') }}
                                · {{ \Carbon\Carbon::parse($booking->batchClass->start_time)->format('H:i') }}
                            </small>
                        </div>
                        @if($booking->status === 'completed')
                            <span class="badge bg-primary">Completed</span>
                        @elseif($booking->status === 'booked')
                            <span class="badge bg-success">Booked</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                        @endif
                    </div>
                @empty
                    <p class="text-muted text-center py-3 mb-0">No bookings yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Recent transactions --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Transactions</h5>
                <a href="{{ route('superadmin.transactions.index', ['user_id' => $member->id]) }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                @forelse($member->transactions as $trx)
                    <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
                        <div>
                            <p class="mb-0 fw-semibold">{{ $trx->transaction_number }}</p>
                            <small class="text-muted">{{ $trx->created_at->format('d M Y') }}</small>
                        </div>
                        <div class="text-end">
                            <p class="mb-0 fw-semibold">Rp {{ number_format($trx->total_amount,0,',','.') }}</p>
                            @if($trx->payment_status === 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($trx->payment_status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @else
                                <span class="badge bg-danger">{{ ucfirst($trx->payment_status) }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-3 mb-0">No transactions yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
