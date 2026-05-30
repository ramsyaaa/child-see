@extends('superadmin.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Instructors</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Instructors</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Instructors</h5>
                <a href="{{ route('superadmin.instructors.create') }}" class="btn btn-primary" style="background-color: #FF6F51; border-color: #FF6F51;">
                    <i class="fas fa-plus me-2"></i>Add New Instructor
                </a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Search and Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="search-wrapper">
                            <input type="text" id="globalSearch" class="form-control" placeholder="Search instructors...">
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
                    <table id="instructorsTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Specialization</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($instructors as $instructor)
                                <tr>
                                    <td>{{ $instructor->id }}</td>
                                    <td>
                                        @if($instructor->photo_url)
                                            <img src="{{ asset('storage/' . $instructor->photo_url) }}" alt="{{ $instructor->name }}" class="rounded-circle" width="40" height="40">
                                        @else
                                            <div class="avatar-icon">
                                                <i class="ti ti-user"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td><strong>{{ $instructor->name }}</strong></td>
                                    <td>{{ $instructor->specialization ?? 'N/A' }}</td>
                                    <td>
                                        @if($instructor->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="{{ route('superadmin.instructors.show', $instructor) }}"
                                                class="btn btn-outline-secondary btn-sm"
                                                data-bs-toggle="tooltip"
                                                title="View Details">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="{{ route('superadmin.instructors.edit', $instructor) }}"
                                                class="btn btn-outline-primary btn-sm"
                                                data-bs-toggle="tooltip"
                                                title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <form action="{{ route('superadmin.instructors.destroy', $instructor) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this instructor?');">
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
                                        <i class="ti ti-user-off fa-3x text-muted mb-3 d-block"></i>
                                        <p class="text-muted">No instructors found. <a href="{{ route('superadmin.instructors.create') }}">Create one now</a></p>
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
    var table = $('#instructorsTable').DataTable({
        responsive: true,
        pageLength: 15,
        lengthMenu: [[10, 15, 25, 50, -1], [10, 15, 25, 50, "All"]],
        order: [[0, 'desc']],
        columnDefs: [
            { orderable: false, targets: [1, 5] }, // Photo and Actions columns
            { className: "text-center", targets: [0, 1, 4, 5] }
        ],
        language: {
            search: "",
            searchPlaceholder: "Search...",
            lengthMenu: "Show _MENU_ entries per page",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        drawCallback: function() {
            // Re-initialize tooltips after table redraw
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
@endsection

