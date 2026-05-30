@extends('superadmin.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Payment Gateway Configuration</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Payment Gateway Configuration</h2>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- Gateway Toggles -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-sliders-h me-2"></i>Gateway Settings</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('superadmin.payment-gateway.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Offline / Bank Transfer -->
                    <div class="d-flex align-items-center justify-content-between p-4 rounded border mb-3" style="background:#f8f9fa">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avtar avtar-lg bg-light-info">
                                <i class="fas fa-university text-info f-22"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Offline Bank Transfer</h6>
                                <p class="text-muted mb-0 small">Members pay via manual bank transfer and upload proof of payment.</p>
                            </div>
                        </div>
                        <div class="form-check form-switch ms-3">
                            <input class="form-check-input" type="checkbox" name="offline_enabled"
                                id="offlineToggle" role="switch" style="width:48px;height:26px"
                                {{ $config->offline_enabled ? 'checked' : '' }}>
                            <label class="form-check-label ms-2 fw-semibold" for="offlineToggle">
                                {{ $config->offline_enabled ? 'Enabled' : 'Disabled' }}
                            </label>
                        </div>
                    </div>

                    <!-- Mayar Gateway -->
                    <div class="d-flex align-items-center justify-content-between p-4 rounded border mb-4" style="background:#f8f9fa">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avtar avtar-lg bg-light-success">
                                <i class="fas fa-credit-card text-success f-22"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Mayar Payment Gateway</h6>
                                <p class="text-muted mb-0 small">Online payment via Mayar — credit/debit cards, e-wallets, and more.</p>
                                <span class="badge bg-secondary mt-1">Coming Soon</span>
                            </div>
                        </div>
                        <div class="form-check form-switch ms-3">
                            <input class="form-check-input" type="checkbox" name="mayar_enabled"
                                id="mayarToggle" role="switch" style="width:48px;height:26px"
                                {{ $config->mayar_enabled ? 'checked' : '' }} disabled>
                            <label class="form-check-label ms-2 fw-semibold text-muted" for="mayarToggle">
                                Not Available
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Configuration
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bank Accounts (for offline) -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-university me-2"></i>Bank Accounts</h5>
                <a href="{{ route('superadmin.bank-accounts.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i> Add Account
                </a>
            </div>
            <div class="card-body">
                @forelse($bankAccounts as $bank)
                    <div class="d-flex align-items-center justify-content-between p-3 rounded border mb-2 {{ $bank->is_active ? '' : 'opacity-50' }}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avtar avtar-s bg-light-primary">
                                <i class="fas fa-university text-primary"></i>
                            </div>
                            <div>
                                <strong class="d-block">{{ $bank->bank_name }}</strong>
                                <small class="text-muted">{{ $bank->account_number }} · {{ $bank->account_holder }}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            @if($bank->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                            <a href="{{ route('superadmin.bank-accounts.edit', $bank->id) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-university fa-2x mb-2 d-block opacity-25"></i>
                        No bank accounts configured yet.
                        <br>
                        <a href="{{ route('superadmin.bank-accounts.create') }}" class="btn btn-sm btn-primary mt-2">Add First Account</a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Status Summary -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Current Status</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span><i class="fas fa-university me-2 text-info"></i>Bank Transfer</span>
                    @if($config->offline_enabled)
                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Active</span>
                    @else
                        <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Disabled</span>
                    @endif
                </div>
                <div class="d-flex justify-content-between align-items-center py-2">
                    <span><i class="fas fa-credit-card me-2 text-success"></i>Mayar Gateway</span>
                    @if($config->mayar_enabled)
                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Active</span>
                    @else
                        <span class="badge bg-secondary">Not Available</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
