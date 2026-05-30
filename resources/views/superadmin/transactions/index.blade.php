@extends('superadmin.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Transactions</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Transactions</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Transactions</h5>
            </div>
            <div class="card-body">
                <!-- Enhanced Search Bar -->
                <div class="row mb-3" id="searchFilters">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                            <input type="text" class="form-control" id="globalSearch"
                                placeholder="Search by transaction number or user...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="statusFilter">
                            <option value="">All Payment Status</option>
                            <option value="Pending">Pending</option>
                            <option value="Paid">Paid</option>
                            <option value="Verified">Verified</option>
                            <option value="Failed">Failed</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="methodFilter">
                            <option value="">All Payment Methods</option>
                            <option value="Offline">Offline</option>
                            <option value="Mayar">Mayar</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" id="dateFilter" placeholder="Filter by date">
                    </div>
                </div>

                <!-- Transactions Table -->
                <div class="table-responsive">
                    <table class="table table-enhanced" id="transactionsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Transaction #</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td><strong>#{{ $transaction->id }}</strong></td>
                                    <td><strong>{{ $transaction->transaction_number }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avtar avtar-s bg-light-primary">
                                                    <i class="ti ti-user f-20"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ $transaction->user->name }}</h6>
                                                <small class="text-muted">{{ $transaction->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><strong>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong></td>
                                    <td>
                                        @if($transaction->payment_method == 'offline')
                                            <span class="badge bg-info">Offline</span>
                                        @else
                                            <span class="badge bg-success">Mayar</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($transaction->payment_status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($transaction->payment_status == 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($transaction->payment_status == 'verified')
                                            <span class="badge bg-success">Verified</span>
                                        @elseif($transaction->payment_status == 'failed')
                                            <span class="badge bg-danger">Failed</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="{{ route('superadmin.transactions.show', $transaction->id) }}"
                                                class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip"
                                                title="View Details">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
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
let transactionsTable;

$(document).ready(function() {
    // Initialize DataTable
    transactionsTable = $('#transactionsTable').DataTable({
        responsive: true,
        pageLength: 15,
        lengthMenu: [[10, 15, 25, 50, -1], [10, 15, 25, 50, "All"]],
        order: [[0, 'desc']],
        columnDefs: [
            { orderable: false, targets: [7] }, // Actions column
            { className: "text-center", targets: [0, 4, 5, 7] }
        ],
        language: {
            search: "",
            searchPlaceholder: "Search transactions...",
            lengthMenu: "Show _MENU_ transactions per page",
            info: "Showing _START_ to _END_ of _TOTAL_ transactions",
            infoEmpty: "Showing 0 to 0 of 0 transactions",
            infoFiltered: "(filtered from _MAX_ total transactions)",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6">>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        drawCallback: function() {
            // Re-initialize tooltips after table redraw
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });

    // Custom search functionality
    $('#globalSearch').on('keyup', function() {
        transactionsTable.search(this.value).draw();
    });

    // Status filter
    $('#statusFilter').on('change', function() {
        transactionsTable.column(5).search(this.value).draw();
    });

    // Method filter
    $('#methodFilter').on('change', function() {
        transactionsTable.column(4).search(this.value).draw();
    });

    // Date filter
    $('#dateFilter').on('change', function() {
        transactionsTable.column(6).search(this.value).draw();
    });

    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endsection

