@extends('admin_new.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Payment Verification</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Payment Verification</h2>
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
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Transactions Awaiting Verification</h5>
        <div class="d-flex gap-2">
            <a href="?payment_status=pending" class="btn btn-sm {{ !request('payment_status') || request('payment_status')=='pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                Pending
            </a>
            <a href="?payment_status=paid" class="btn btn-sm {{ request('payment_status')=='paid' ? 'btn-success' : 'btn-outline-success' }}">
                Verified
            </a>
            <a href="?payment_status=failed" class="btn btn-sm {{ request('payment_status')=='failed' ? 'btn-danger' : 'btn-outline-danger' }}">
                Rejected
            </a>
            <a href="?payment_status=" class="btn btn-sm btn-outline-secondary">All</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Transaction #</th>
                        <th>Member</th>
                        <th>Amount</th>
                        <th>Bank</th>
                        <th>Submitted</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                        <tr>
                            <td><strong>{{ $trx->transaction_number }}</strong></td>
                            <td>
                                <strong>{{ $trx->user->name }}</strong>
                                <br><small class="text-muted">{{ $trx->user->email }}</small>
                            </td>
                            <td><strong>Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</strong></td>
                            <td>{{ $trx->bankAccount->bank_name ?? '—' }}</td>
                            <td>{{ $trx->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                @if($trx->payment_status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($trx->payment_status == 'paid')
                                    <span class="badge bg-success">Verified</span>
                                @elseif(in_array($trx->payment_status, ['failed','rejected']))
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($trx->payment_status) }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.payment-verification.show', $trx) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-check-circle fa-2x mb-2 d-block text-success opacity-50"></i>
                                No transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
