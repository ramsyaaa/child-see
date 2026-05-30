@extends('superadmin.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Bank Accounts</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Bank Accounts</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Bank Accounts</h5>
                <a href="{{ route('superadmin.bank-accounts.create') }}" class="btn btn-primary" style="background-color: #FF6F51; border-color: #FF6F51;">
                    <i class="fas fa-plus"></i> Add Bank Account
                </a>
            </div>
            <div class="card-body">
                <!-- Search and Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="search-wrapper">
                            <input type="text" id="globalSearch" class="form-control" placeholder="Search bank accounts...">
                            <i class="ti ti-search search-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select id="statusFilter" class="form-select">
                            <option value="">All Status</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="bankAccountsTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Bank Name</th>
                                <th>Account Number</th>
                                <th>Account Holder</th>
                                <th>Branch</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bankAccounts as $account)
                                <tr>
                                    <td><strong>{{ $account->bank_name }}</strong></td>
                                    <td>{{ $account->account_number }}</td>
                                    <td>{{ $account->account_holder }}</td>
                                    <td>{{ $account->branch ?? '-' }}</td>
                                    <td>
                                        @if($account->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="{{ route('superadmin.bank-accounts.show', $account->id) }}"
                                                class="btn btn-outline-secondary btn-sm"
                                                data-bs-toggle="tooltip"
                                                title="View Details">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="{{ route('superadmin.bank-accounts.edit', $account->id) }}"
                                                class="btn btn-outline-primary btn-sm"
                                                data-bs-toggle="tooltip"
                                                title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <form action="{{ route('superadmin.bank-accounts.destroy', $account->id) }}"
                                                method="POST"
                                                class="d-inline delete-form"
                                                onsubmit="return confirm('Are you sure you want to delete this bank account?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-outline-danger btn-sm"
                                                    data-bs-toggle="tooltip"
                                                    title="Delete">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="ti ti-building-bank fa-3x text-muted mb-3 d-block"></i>
                                        <p class="text-muted">No bank accounts found</p>
                                        <a href="{{ route('superadmin.bank-accounts.create') }}" class="btn btn-primary" style="background-color: #FF6F51; border-color: #FF6F51;">
                                            <i class="fas fa-plus"></i> Add Your First Bank Account
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#bankAccountsTable').DataTable({
        responsive: true,
        pageLength: 15,
        lengthMenu: [[10, 15, 25, 50, -1], [10, 15, 25, 50, "All"]],
        order: [[0, 'asc']], // Sort by bank name
        columnDefs: [
            { orderable: false, targets: [5] }, // Actions column
            { className: "text-center", targets: [4, 5] }
        ],
        language: {
            search: "",
            searchPlaceholder: "Search...",
            lengthMenu: "Show _MENU_ entries per page",
            info: "Showing _START_ to _END_ of _TOTAL_ accounts",
            infoEmpty: "Showing 0 to 0 of 0 accounts",
            infoFiltered: "(filtered from _MAX_ total accounts)",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        drawCallback: function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });

    // Global search
    $('#globalSearch').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Status filter
    $('#statusFilter').on('change', function() {
        table.column(4).search(this.value).draw();
    });

    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
