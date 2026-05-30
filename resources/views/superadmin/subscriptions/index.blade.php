@extends('superadmin.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Subscriptions</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Subscriptions</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Subscriptions</h5>
                <a href="{{ route('superadmin.subscriptions.create') }}" class="btn btn-primary" style="background-color: #FF6F51; border-color: #FF6F51;">
                    <i class="fas fa-plus me-2"></i>Add New Subscription
                </a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Search and Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="search-wrapper">
                            <input type="text" id="globalSearch" class="form-control" placeholder="Search subscriptions...">
                            <i class="ti ti-search search-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select id="statusFilter" class="form-select">
                            <option value="">All Status</option>
                            <option value="Active">Active</option>
                            <option value="Expired">Expired</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="subscriptionsTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Member</th>
                                <th>Product</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Quota</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscriptions as $subscription)
                                <tr>
                                    <td>{{ $subscription->id }}</td>
                                    <td>{{ $subscription->user->name }}</td>
                                    <td><strong>{{ $subscription->product->name }}</strong></td>
                                    <td data-order="{{ $subscription->start_date->format('Y-m-d') }}">{{ $subscription->start_date->format('d M Y') }}</td>
                                    <td data-order="{{ $subscription->end_date->format('Y-m-d') }}">{{ $subscription->end_date->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge bg-light-primary">
                                            {{ $subscription->quota_used }}/{{ $subscription->quota_allocated ?? '∞' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($subscription->status == 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($subscription->status == 'expired')
                                            <span class="badge bg-secondary">Expired</span>
                                        @else
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="{{ route('superadmin.subscriptions.show', $subscription) }}"
                                                class="btn btn-outline-secondary btn-sm"
                                                data-bs-toggle="tooltip"
                                                title="View Details">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="{{ route('superadmin.subscriptions.edit', $subscription) }}"
                                                class="btn btn-outline-primary btn-sm"
                                                data-bs-toggle="tooltip"
                                                title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <form action="{{ route('superadmin.subscriptions.destroy', $subscription) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this subscription?');">
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
                                    <td colspan="8" class="text-center py-4">
                                        <i class="ti ti-calendar-off fa-3x text-muted mb-3 d-block"></i>
                                        <p class="text-muted">No subscriptions found. <a href="{{ route('superadmin.subscriptions.create') }}">Create one now</a></p>
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
    var table = $('#subscriptionsTable').DataTable({
        responsive: true,
        pageLength: 15,
        lengthMenu: [[10, 15, 25, 50, -1], [10, 15, 25, 50, "All"]],
        order: [[0, 'desc']],
        columnDefs: [
            { orderable: false, targets: [7] }, // Actions column
            { className: "text-center", targets: [0, 5, 6, 7] }
        ],
        language: {
            search: "",
            searchPlaceholder: "Search...",
            lengthMenu: "Show _MENU_ entries per page",
            info: "Showing _START_ to _END_ of _TOTAL_ subscriptions",
            infoEmpty: "Showing 0 to 0 of 0 subscriptions",
            infoFiltered: "(filtered from _MAX_ total subscriptions)",
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
        table.column(6).search(this.value).draw();
    });

    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endsection

