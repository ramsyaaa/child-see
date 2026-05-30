@extends('superadmin.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.bank-accounts.index') }}">Bank Accounts</a></li>
                    <li class="breadcrumb-item" aria-current="page">Bank Account Details</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Bank Account Details</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Account Information</h5>
                <div>
                    <a href="{{ route('superadmin.bank-accounts.edit', $bankAccount->id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('superadmin.bank-accounts.destroy', $bankAccount->id) }}" method="POST" class="d-inline delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Bank Name:</th>
                        <td><strong>{{ $bankAccount->bank_name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Account Number:</th>
                        <td><strong class="text-primary">{{ $bankAccount->account_number }}</strong></td>
                    </tr>
                    <tr>
                        <th>Account Holder:</th>
                        <td>{{ $bankAccount->account_holder }}</td>
                    </tr>
                    <tr>
                        <th>Branch:</th>
                        <td>{{ $bankAccount->branch ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            @if($bankAccount->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    @if($bankAccount->note)
                    <tr>
                        <th>Note:</th>
                        <td>{{ $bankAccount->note }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Created At:</th>
                        <td>{{ $bankAccount->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Last Updated:</th>
                        <td>{{ $bankAccount->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>

                <div class="mt-3">
                    <a href="{{ route('superadmin.bank-accounts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Recent Transactions</h5>
            </div>
            <div class="card-body">
                @if($bankAccount->transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Transaction #</th>
                                    <th>User</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bankAccount->transactions->take(10) as $transaction)
                                    <tr>
                                        <td>{{ $transaction->transaction_number }}</td>
                                        <td>{{ $transaction->user->name }}</td>
                                        <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                        <td>
                                            @if($transaction->payment_status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($transaction->payment_status == 'paid' || $transaction->payment_status == 'verified')
                                                <span class="badge bg-success">{{ ucfirst($transaction->payment_status) }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ ucfirst($transaction->payment_status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $transaction->created_at->format('d M Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3">No transactions yet</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light-info">
            <div class="card-body">
                <h5 class="mb-3"><i class="fas fa-chart-line"></i> Statistics</h5>
                <div class="mb-3">
                    <small class="text-muted">Total Transactions</small>
                    <h3 class="mb-0">{{ $bankAccount->transactions->count() }}</h3>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Pending Transactions</small>
                    <h3 class="mb-0">{{ $bankAccount->transactions->where('payment_status', 'pending')->count() }}</h3>
                </div>
                <div>
                    <small class="text-muted">Total Amount</small>
                    <h3 class="mb-0">Rp {{ number_format($bankAccount->transactions->where('payment_status', 'verified')->sum('total_amount'), 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Delete confirmation
    document.querySelector('.delete-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Are you sure?',
            text: "This bank account will be deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#FF6F51',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
</script>
@endpush
@endsection

