@extends('superadmin.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Abandoned Carts</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Abandoned Carts</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avtar avtar-lg bg-light-warning">
                            <i class="fas fa-shopping-cart text-warning f-22"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($totalAbandoned) }}</h3>
                        <p class="text-muted mb-0 text-sm">Total Abandoned</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avtar avtar-lg bg-light-danger">
                            <i class="fas fa-money-bill text-danger f-22"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">Rp {{ number_format($totalValue, 0, ',', '.') }}</h3>
                        <p class="text-muted mb-0 text-sm">Total Lost Value</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avtar avtar-lg bg-light-primary">
                            <i class="fas fa-calendar text-primary f-22"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ number_format($thisMonthCount) }}</h3>
                        <p class="text-muted mb-0 text-sm">This Month</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avtar avtar-lg bg-light-success">
                            <i class="fas fa-chart-line text-success f-22"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">Rp {{ number_format($thisMonthValue, 0, ',', '.') }}</h3>
                        <p class="text-muted mb-0 text-sm">This Month Value</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('superadmin.abandoned-carts.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">From Date</label>
                <input type="date" name="from" class="form-control" value="{{ request('from') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">To Date</label>
                <input type="date" name="to" class="form-control" value="{{ request('to') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Reason</label>
                <select name="reason" class="form-control">
                    <option value="">All Reasons</option>
                    @foreach($reasons as $reason)
                        <option value="{{ $reason }}" {{ request('reason') == $reason ? 'selected' : '' }}>
                            {{ ucfirst($reason ?? 'Unknown') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
                <a href="{{ route('superadmin.abandoned-carts.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Abandoned Carts</h5>
        <span class="badge bg-warning">{{ $abandonedCarts->total() }} records</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="abandonedCartsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Reason</th>
                        <th>Captured At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($abandonedCarts as $cart)
                        <tr>
                            <td>{{ $cart->id }}</td>
                            <td>
                                @if($cart->user)
                                    <div>
                                        <strong>{{ $cart->user->name }}</strong>
                                        <br><small class="text-muted">{{ $cart->user->email }}</small>
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($cart->product)
                                    <div>
                                        <strong>{{ $cart->product->name }}</strong>
                                        <br><small class="text-muted">{{ ucfirst($cart->product->type ?? '') }}</small>
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td><strong>Rp {{ number_format($cart->price, 0, ',', '.') }}</strong></td>
                            <td>
                                @if($cart->reason)
                                    <span class="badge bg-warning text-dark">{{ ucfirst($cart->reason) }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $cart->captured_at ? $cart->captured_at->format('d M Y H:i') : $cart->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-shopping-cart fa-2x mb-2 d-block opacity-25"></i>
                                No abandoned carts found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-end mt-3">
            {{ $abandonedCarts->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endsection
