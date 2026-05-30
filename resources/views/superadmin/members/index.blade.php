@extends('superadmin.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Members</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Members</h2>
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

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="inklu-stat-card">
            <div class="stat-value">{{ number_format($stats['total']) }}</div>
            <div class="stat-label">Total Members</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="inklu-stat-card">
            <div class="stat-value">{{ number_format($stats['active']) }}</div>
            <div class="stat-label">Active Members</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-semibold">Search</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                       placeholder="Name, email, or phone">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="active"   @selected(request('status')=='active')>Active</option>
                    <option value="inactive" @selected(request('status')=='inactive')>Inactive</option>
                    <option value="suspended" @selected(request('status')=='suspended')>Suspended</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i>Search
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('superadmin.members.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Member</th>
                        <th>Phone</th>
                        <th>Bookings</th>
                        <th>Subscriptions</th>
                        <th>Transactions</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $m)
                        <tr>
                            <td>{{ $m->id }}</td>
                            <td>
                                <strong>{{ $m->name }}</strong>
                                <br><small class="text-muted">{{ $m->email }}</small>
                            </td>
                            <td>{{ $m->phone ?? '—' }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $m->bookings_count }}</span></td>
                            <td><span class="badge bg-light text-dark border">{{ $m->subscriptions_count }}</span></td>
                            <td><span class="badge bg-light text-dark border">{{ $m->transactions_count }}</span></td>
                            <td>
                                @php $st = strtolower($m->status ?? 'active'); @endphp
                                @if($st === 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($st === 'inactive')
                                    <span class="badge bg-secondary">Inactive</span>
                                @elseif($st === 'suspended')
                                    <span class="badge bg-danger">Suspended</span>
                                @else
                                    <span class="badge bg-light text-dark">{{ ucfirst($st) }}</span>
                                @endif
                            </td>
                            <td>{{ $m->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('superadmin.members.show', $m) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="fas fa-users fa-2x mb-2 d-block opacity-25"></i>
                                No members found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $members->links() }}
        </div>
    </div>
</div>
@endsection
