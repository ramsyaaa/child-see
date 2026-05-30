@extends('admin_new.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.payment-verification.index') }}">Payment Verification</a></li>
                    <li class="breadcrumb-item" aria-current="page">{{ $transaction->transaction_number }}</li>
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
    {{-- Transaction details --}}
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $transaction->transaction_number }}</h5>
                @if($transaction->payment_status == 'pending')
                    <span class="badge bg-warning text-dark">Pending Verification</span>
                @elseif($transaction->payment_status == 'paid')
                    <span class="badge bg-success">Verified</span>
                @elseif(in_array($transaction->payment_status, ['failed','rejected']))
                    <span class="badge bg-danger">Rejected</span>
                @endif
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Member</p>
                        <strong>{{ $transaction->user->name }}</strong>
                        <br><small class="text-muted">{{ $transaction->user->email }}</small>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Submitted At</p>
                        {{ $transaction->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Total Amount</p>
                        <strong class="h5 text-success">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Transfer To</p>
                        @if($transaction->bankAccount)
                            <strong>{{ $transaction->bankAccount->bank_name }}</strong><br>
                            <span class="text-muted">{{ $transaction->bankAccount->account_number }}</span><br>
                            <small>a/n {{ $transaction->bankAccount->account_holder }}</small>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </div>
                </div>

                {{-- Items --}}
                <h6 class="mt-4 mb-3">Items Purchased</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->items as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->product_name_snapshot ?? $item->product->name ?? '—' }}</strong>
                                        @if($item->product)
                                            @if($item->product->quota_type === 'unlimited')
                                                <br><small class="text-muted">Unlimited sessions</small>
                                            @elseif($item->product->quota)
                                                <br><small class="text-muted">{{ $item->product->quota }} sessions</small>
                                            @endif
                                        @endif
                                    </td>
                                    <td><span class="badge bg-secondary">{{ ucfirst($item->product->type ?? '—') }}</span></td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->unit_price ?? 0, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            @if($transaction->discount_amount > 0)
                                <tr>
                                    <td colspan="4" class="text-end text-muted">Subtotal</td>
                                    <td>Rp {{ number_format($transaction->items->sum('unit_price'), 0, ',', '.') }}</td>
                                </tr>
                                <tr class="text-success">
                                    <td colspan="4" class="text-end">
                                        <i class="fas fa-tag me-1"></i>Discount
                                        @if($transaction->coupon_code)
                                            <span class="badge bg-success ms-1">{{ $transaction->coupon_code }}</span>
                                        @endif
                                    </td>
                                    <td>- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th colspan="4" class="text-end">Total</th>
                                <th>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($transaction->rejection_reason)
                    <div class="alert alert-danger mt-3">
                        <strong>Rejection Reason:</strong> {{ $transaction->rejection_reason }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Payment proof --}}
        @if($transaction->payment_proof)
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Payment Proof</h5></div>
                <div class="card-body text-center">
                    <img src="{{ asset('storage/' . $transaction->payment_proof) }}"
                         alt="Payment Proof"
                         class="img-fluid rounded"
                         style="max-height: 500px; cursor: zoom-in"
                         onclick="window.open(this.src,'_blank')">
                    <div class="mt-2">
                        <a href="{{ asset('storage/' . $transaction->payment_proof) }}" target="_blank"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-external-link-alt me-1"></i>Open Full Size
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>No payment proof uploaded.
            </div>
        @endif
    </div>

    {{-- Action panel --}}
    <div class="col-md-4">
        @if($transaction->payment_status == 'pending')
            <div class="card mb-3">
                <div class="card-header"><h5 class="mb-0">Verify Payment</h5></div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        Confirm only if the transfer proof matches the amount and destination bank account exactly.
                        This will activate the member's subscription immediately.
                    </p>
                    <form action="{{ route('admin.payment-verification.verify', $transaction) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 mb-2"
                                onclick="return confirm('Verify this payment and activate subscription?')">
                            <i class="fas fa-check me-2"></i>Verify & Activate
                        </button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="mb-0">Reject Payment</h5></div>
                <div class="card-body">
                    <form action="{{ route('admin.payment-verification.reject', $transaction) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea name="rejection_reason" class="form-control @error('rejection_reason') is-invalid @enderror"
                                      rows="3" placeholder="Explain why this payment is rejected..."
                                      required>{{ old('rejection_reason') }}</textarea>
                            @error('rejection_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-danger w-100"
                                onclick="return confirm('Reject this payment? Member will be notified.')">
                            <i class="fas fa-times me-2"></i>Reject Payment
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Status</h5></div>
                <div class="card-body">
                    @if($transaction->payment_status == 'paid')
                        <div class="alert alert-success mb-0">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Verified</strong><br>
                            <small>{{ $transaction->verified_at ? \Carbon\Carbon::parse($transaction->verified_at)->format('d M Y, H:i') : '' }}</small>
                        </div>
                    @else
                        <div class="alert alert-danger mb-0">
                            <i class="fas fa-times-circle me-2"></i>
                            <strong>Rejected</strong><br>
                            @if($transaction->rejection_reason)
                                <small>{{ $transaction->rejection_reason }}</small>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <div class="mt-3">
            <a href="{{ route('admin.payment-verification.index') }}" class="btn btn-outline-secondary w-100">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </div>
</div>
@endsection
