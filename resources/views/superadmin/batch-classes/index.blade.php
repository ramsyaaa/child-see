@extends('superadmin.layout.master')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Batch Classes</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Batch Classes</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Scheduled Classes</h5>
                <a href="{{ route('superadmin.batch-classes.create') }}" class="btn btn-primary" style="background-color: #FF6F51; border-color: #FF6F51;">
                    <i class="fas fa-plus me-2"></i>Schedule New Class
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
                    <div class="col-md-4">
                        <div class="search-wrapper">
                            <input type="text" id="globalSearch" class="form-control" placeholder="Search classes...">
                            <i class="ti ti-search search-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select id="statusFilter" class="form-select">
                            <option value="">All Status</option>
                            <option value="Active">Active</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" id="dateFilter" class="form-control" placeholder="Filter by date">
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="batchClassesTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Class</th>
                                <th>Instructor</th>
                                <th>Room</th>
                                <th>Slots</th>
                                <th>Restrictions</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($batchClasses as $class)
                                <tr>
                                    <td data-order="{{ $class->date->format('Y-m-d') }}">{{ $class->date->format('d M Y') }}</td>
                                    <td>{{ date('H:i', strtotime($class->start_time)) }} - {{ date('H:i', strtotime($class->end_time)) }}</td>
                                    <td>
                                        <strong>{{ $class->masterClass->name }}</strong>
                                        <br>
                                        <small class="text-muted">Rp {{ number_format($class->price, 0, ',', '.') }}</small>
                                    </td>
                                    <td>{{ $class->instructor->name }}</td>
                                    <td>{{ $class->room->room_name }}</td>
                                    <td>
                                        <span class="badge {{ $class->remaining_slots > 0 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $class->remaining_slots }}/{{ $class->capacity }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($class->is_have_gender_restriction && $class->gender_restriction)
                                            <span class="badge bg-info mb-1">
                                                <i class="ti ti-gender-{{ strtolower($class->gender_restriction) == 'women' ? 'female' : (strtolower($class->gender_restriction) == 'men' ? 'male' : 'bigender') }}"></i>
                                                {{ $class->gender_restriction }}
                                            </span>
                                        @endif
                                        @if($class->is_have_age_restriction && $class->age_restriction)
                                            <span class="badge bg-warning text-dark">
                                                <i class="ti ti-calendar-event"></i>
                                                {{ $class->age_restriction }}
                                            </span>
                                        @endif
                                        @if(!$class->is_have_gender_restriction && !$class->is_have_age_restriction)
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($class->status == 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="{{ route('superadmin.batch-classes.show', $class) }}"
                                                class="btn btn-outline-secondary btn-sm"
                                                data-bs-toggle="tooltip"
                                                title="View Details">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="{{ route('superadmin.batch-classes.edit', $class) }}"
                                                class="btn btn-outline-primary btn-sm"
                                                data-bs-toggle="tooltip"
                                                title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <form action="{{ route('superadmin.batch-classes.destroy', $class) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this class?');">
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
                                    <td colspan="9" class="text-center py-4">
                                        <i class="ti ti-calendar-off fa-3x text-muted mb-3 d-block"></i>
                                        <p class="text-muted">No batch classes scheduled. <a href="{{ route('superadmin.batch-classes.create') }}">Schedule one now</a></p>
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
    var table = $('#batchClassesTable').DataTable({
        responsive: true,
        pageLength: 15,
        lengthMenu: [[10, 15, 25, 50, -1], [10, 15, 25, 50, "All"]],
        order: [[0, 'desc']], // Sort by date descending
        columnDefs: [
            { orderable: false, targets: [6, 8] }, // Restrictions and Actions columns
            { className: "text-center", targets: [5, 6, 7, 8] }
        ],
        language: {
            search: "",
            searchPlaceholder: "Search...",
            lengthMenu: "Show _MENU_ entries per page",
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
        table.column(7).search(this.value).draw();
    });

    // Date filter
    $('#dateFilter').on('change', function() {
        var selectedDate = $(this).val();
        if (selectedDate) {
            // Convert to display format for filtering
            var date = new Date(selectedDate);
            var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            var day = ('0' + date.getDate()).slice(-2);
            var month = months[date.getMonth()];
            var year = date.getFullYear();
            var formattedDate = day + ' ' + month + ' ' + year;
            table.column(0).search(formattedDate).draw();
        } else {
            table.column(0).search('').draw();
        }
    });

    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endsection

