@extends('superadmin.layout.master')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Master Classes</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Master Classes</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Master Classes</h5>
                <a href="{{ route('superadmin.master-classes.create') }}" class="btn btn-primary" style="background-color: #FF6F51; border-color: #FF6F51;">
                    <i class="fas fa-plus me-2"></i>Add New Class
                </a>
            </div>
            <div class="card-body">
                <!-- Enhanced Search Bar -->
                <div class="row mb-3" id="searchFilters">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                            <input type="text" class="form-control" id="globalSearch"
                                placeholder="Search by name or category...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="difficultyFilter">
                            <option value="">All Difficulty Levels</option>
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Table View -->
                <div id="tableView" class="view-container">
                    <div class="table-responsive">
                        <table class="table table-enhanced" id="masterClassesTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Difficulty</th>
                                    <th>Duration</th>
                                    <th>Color</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($masterClasses as $class)
                                    <tr>
                                        <td><strong>#{{ $class->id }}</strong></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="avtar avtar-s bg-light-primary">
                                                        <i class="ti ti-yoga f-20"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-0">{{ $class->name }}</h6>
                                                    <small class="text-muted">{{ $class->default_duration }} minutes</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light-secondary">{{ $class->category }}</span></td>
                                        <td>
                                            @if($class->difficulty_level == 'beginner')
                                                <span class="badge bg-success">Beginner</span>
                                            @elseif($class->difficulty_level == 'intermediate')
                                                <span class="badge bg-warning">Intermediate</span>
                                            @else
                                                <span class="badge bg-danger">Advanced</span>
                                            @endif
                                        </td>
                                        <td>{{ $class->default_duration }} min</td>
                                        <td>
                                            @if($class->color)
                                                <div class="d-flex align-items-center">
                                                    <span class="badge" style="background-color: {{ $class->color }}; width: 30px; height: 30px; border-radius: 50%; display: inline-block; margin-right: 8px;"></span>
                                                    <small class="text-muted">{{ $class->color }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($class->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="{{ route('superadmin.master-classes.show', $class) }}"
                                                    class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip"
                                                    title="View Details">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <a href="{{ route('superadmin.master-classes.edit', $class) }}"
                                                    class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip"
                                                    title="Edit Class">
                                                    <i class="ti ti-edit-circle"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    data-bs-toggle="tooltip" title="Delete Class"
                                                    onclick="confirmDelete({{ $class->id }})">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                                <form id="delete-form-{{ $class->id }}"
                                                    action="{{ route('superadmin.master-classes.destroy', $class) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
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
</div>
<!-- [ Main Content ] end -->
@endsection

@section('scripts')
<script>
let masterClassesTable;

$(document).ready(function() {
    // Initialize DataTable
    masterClassesTable = $('#masterClassesTable').DataTable({
        responsive: true,
        pageLength: 15,
        lengthMenu: [[10, 15, 25, 50, -1], [10, 15, 25, 50, "All"]],
        order: [[0, 'desc']],
        columnDefs: [
            { orderable: false, targets: [7] }, // Actions column
            { className: "text-center", targets: [0, 3, 4, 5, 6, 7] }
        ],
        language: {
            search: "",
            searchPlaceholder: "Search master classes...",
            lengthMenu: "Show _MENU_ classes per page",
            info: "Showing _START_ to _END_ of _TOTAL_ classes",
            infoEmpty: "Showing 0 to 0 of 0 classes",
            infoFiltered: "(filtered from _MAX_ total classes)",
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
        masterClassesTable.search(this.value).draw();
    });

    // Difficulty filter
    $('#difficultyFilter').on('change', function() {
        masterClassesTable.column(3).search(this.value).draw();
    });

    // Status filter
    $('#statusFilter').on('change', function() {
        masterClassesTable.column(5).search(this.value).draw();
    });

    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});

// Confirm delete function
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this master class?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection

