@extends('superadmin.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.transactions.index') }}">Transactions</a></li>
                    <li class="breadcrumb-item" aria-current="page">Transaction Details</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Transaction Details</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Transaction Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Transaction Number:</th>
                        <td><strong>{{ $transaction->transaction_number }}</strong></td>
                    </tr>
                    <tr>
                        <th>Customer:</th>
                        <td>{{ $transaction->user->name }} ({{ $transaction->user->email }})</td>
                    </tr>
                    <tr>
                        <th>Total Amount:</th>
                        <td><strong class="text-primary">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <th>Payment Method:</th>
                        <td>
                            <span class="badge bg-light-{{ $transaction->payment_method == 'offline' ? 'info' : 'success' }}">
                                {{ ucfirst($transaction->payment_method) }}
                            </span>
                        </td>
                    </tr>
                    @if($transaction->payment_method == 'offline' && $transaction->bankAccount)
                    <tr>
                        <th>Bank Account:</th>
                        <td>
                            {{ $transaction->bankAccount->bank_name }}<br>
                            <small class="text-muted">{{ $transaction->bankAccount->account_number }} - {{ $transaction->bankAccount->account_holder }}</small>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th>Payment Status:</th>
                        <td>
                            @if($transaction->payment_status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($transaction->payment_status == 'paid' || $transaction->payment_status == 'verified')
                                <span class="badge bg-success">{{ ucfirst($transaction->payment_status) }}</span>
                            @elseif($transaction->payment_status == 'failed')
                                <span class="badge bg-danger">Failed</span>
                            @elseif($transaction->payment_status == 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Transaction Date:</th>
                        <td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @if($transaction->verified_at)
                    <tr>
                        <th>Verified At:</th>
                        <td>{{ $transaction->verified_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Verified By:</th>
                        <td>{{ $transaction->verifier->name ?? 'N/A' }}</td>
                    </tr>
                    @endif
                    @if($transaction->verification_notes)
                    <tr>
                        <th>Verification Notes:</th>
                        <td>{{ $transaction->verification_notes }}</td>
                    </tr>
                    @endif
                    @if($transaction->rejection_reason)
                    <tr>
                        <th>Rejection Reason:</th>
                        <td class="text-danger">{{ $transaction->rejection_reason }}</td>
                    </tr>
                    @endif
                    @if($transaction->coupon_code)
                    <tr>
                        <th>Coupon Applied:</th>
                        <td>
                            <span class="badge bg-success me-1">{{ $transaction->coupon_code }}</span>
                            <small class="text-muted">discount: Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</small>
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Transaction Items -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Items</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>
                                        <span class="badge bg-light-primary">
                                            {{ ucfirst($item->product->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->unit_price ?? $item->subtotal, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            @if($transaction->discount_amount > 0)
                                <tr>
                                    <td colspan="4" class="text-end text-muted">Subtotal:</td>
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
                                <th colspan="4" class="text-end">Total:</th>
                                <th>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        @if($transaction->payment_method == 'offline' && $transaction->payment_proof)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Payment Proof</h5>
            </div>
            <div class="card-body text-center">
                <img src="{{ Storage::url($transaction->payment_proof) }}" alt="Payment Proof" class="img-fluid rounded" style="max-height: 400px;">
                <div class="mt-3">
                    <a href="{{ Storage::url($transaction->payment_proof) }}" target="_blank" class="btn btn-sm btn-primary">
                        <i class="fas fa-external-link-alt"></i> View Full Size
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

