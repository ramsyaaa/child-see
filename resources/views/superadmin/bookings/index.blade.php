@extends('superadmin.layout.master')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Bookings</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Bookings Management</h2>
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
                <h5 class="mb-0">All Bookings</h5>
            </div>
            <div class="card-body">
                <!-- Enhanced Search Bar -->
                <div class="row mb-3" id="searchFilters">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                            <input type="text" class="form-control" id="globalSearch"
                                placeholder="Search by member name or email...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="booked">Booked</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="typeFilter">
                            <option value="">All Types</option>
                            <option value="subscription">Subscription</option>
                            <option value="dropin">Drop-in</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" id="dateFilter" placeholder="Filter by date">
                    </div>
                </div>

                <!-- Table View -->
                <div id="tableView" class="view-container">
                    <div class="table-responsive">
                        <table class="table table-enhanced" id="bookingsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Member</th>
                                    <th>Class</th>
                                    <th>Instructor</th>
                                    <th>Date & Time</th>
                                    <th>Type</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Check-in</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bookings as $booking)
                                <tr>
                                    <td><strong>#{{ $booking->id }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avtar avtar-s bg-light-primary">
                                                    <i class="ti ti-user f-20"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ $booking->user->name }}</h6>
                                                <small class="text-muted">{{ $booking->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $booking->batchClass->masterClass->name }}</strong><br>
                                        <small class="text-muted">{{ $booking->batchClass->masterClass->category }}</small>
                                    </td>
                                    <td>{{ $booking->batchClass->instructor->name }}</td>
                                    <td>
                                        {{ $booking->batchClass->date->format('d M Y') }}<br>
                                        <small class="text-muted">{{ date('H:i', strtotime($booking->batchClass->start_time)) }}</small>
                                    </td>
                                    <td>
                                        @if($booking->booking_type == 'subscription')
                                            <span class="badge bg-primary">Subscription</span>
                                        @else
                                            <span class="badge bg-info">Drop-in</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($booking->price > 0)
                                            Rp {{ number_format($booking->price, 0, ',', '.') }}
                                        @else
                                            <span class="text-muted">Free</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($booking->status == 'booked')
                                            <span class="badge bg-success">Booked</span>
                                        @elseif($booking->status == 'completed')
                                            <span class="badge bg-info">Completed</span>
                                        @else
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($booking->check_in_time)
                                            <span class="badge bg-success">
                                                <i class="ti ti-check"></i> Checked In
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Not Checked In</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="{{ route('superadmin.bookings.show', $booking->id) }}"
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
</div>
<!-- [ Main Content ] end -->
@endsection

@section('scripts')
<script>
let bookingsTable;

$(document).ready(function() {
    // Initialize DataTable
    bookingsTable = $('#bookingsTable').DataTable({
        responsive: true,
        pageLength: 15,
        lengthMenu: [[10, 15, 25, 50, -1], [10, 15, 25, 50, "All"]],
        order: [[0, 'desc']],
        columnDefs: [
            { orderable: false, targets: [9] }, // Actions column
            { className: "text-center", targets: [0, 5, 7, 8, 9] }
        ],
        language: {
            search: "",
            searchPlaceholder: "Search bookings...",
            lengthMenu: "Show _MENU_ bookings per page",
            info: "Showing _START_ to _END_ of _TOTAL_ bookings",
            infoEmpty: "Showing 0 to 0 of 0 bookings",
            infoFiltered: "(filtered from _MAX_ total bookings)",
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
        bookingsTable.search(this.value).draw();
    });

    // Status filter
    $('#statusFilter').on('change', function() {
        bookingsTable.column(7).search(this.value).draw();
    });

    // Type filter
    $('#typeFilter').on('change', function() {
        bookingsTable.column(5).search(this.value).draw();
    });

    // Date filter
    $('#dateFilter').on('change', function() {
        bookingsTable.column(4).search(this.value).draw();
    });

    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endsection

