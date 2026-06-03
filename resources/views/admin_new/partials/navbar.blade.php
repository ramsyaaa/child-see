<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header" style="background: #3d2410 !important; border-bottom: 1px solid rgba(255,255,255,.08);">
            <a href="{{ url('/admin/dashboard') }}" class="b-brand text-white">
                <img src="{{ asset('logo.png') }}" style="max-width: 100%; height: auto;" alt="Child See" />
            </a>
        </div>
        <div class="navbar-content">
            <div class="card pc-user-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('vendor/dashboard') }}/assets/images/user/avatar-1.jpg" alt="user-image"
                                class="user-avtar wid-45 rounded-circle" />
                        </div>
                        <div class="flex-grow-1 ms-3 me-2">
                            @auth
                            <h6 class="mb-0">{{ Auth::user()->full_name ?? Auth::user()->name }}</h6>
                            <small>Admin</small>
                            @else
                            <h6 class="mb-0">Guest</h6>
                            <small>Not authenticated</small>
                            @endauth
                        </div>
                        <a class="btn btn-icon btn-link-secondary avtar" data-bs-toggle="collapse"
                            href="#pc_sidebar_userlink"><svg class="pc-icon">
                                <use xlink:href="#custom-sort-outline"></use>
                            </svg></a>
                    </div>
                    <div class="collapse pc-user-links" id="pc_sidebar_userlink">
                        <div class="pt-3">
                            <a href="#!"><i class="ti ti-user"></i>
                                <span>My Account</span> </a>
                            <a href="{{ route('sign-out') }}"><i class="ti ti-power"></i>
                                <span>Logout</span></a>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="pc-navbar">
                <li class="pc-item pc-caption">
                    <label>Dashboard</label>
                    <svg class="pc-icon">
                        <use xlink:href="#custom-presentation-chart"></use>
                    </svg>
                </li>
                <li class="pc-item {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="pc-link">
                        <span class="pc-micon"><i class="fas fa-tachometer-alt"></i></span>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>

                <li class="pc-item pc-caption">
                    <label>Class Management</label>
                </li>
                <li class="pc-item {{ request()->routeIs('admin.calendar*') ? 'active' : '' }}">
                    <a href="{{ route('admin.calendar') }}" class="pc-link">
                        <span class="pc-micon"><i class="fas fa-calendar-alt"></i></span>
                        <span class="pc-mtext">Calendar</span>
                    </a>
                </li>
                <li class="pc-item {{ request()->routeIs('admin.batch-classes*') ? 'active' : '' }}">
                    <a href="{{ route('admin.batch-classes.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="fas fa-calendar-check"></i></span>
                        <span class="pc-mtext">Batch Classes</span>
                    </a>
                </li>

                <li class="pc-item pc-caption">
                    <label>Bookings</label>
                </li>
                <li class="pc-item {{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">
                    <a href="{{ route('admin.bookings.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="fas fa-bookmark"></i></span>
                        <span class="pc-mtext">Manage Bookings</span>
                    </a>
                </li>
                <li class="pc-item {{ request()->routeIs('admin.check-in*') ? 'active' : '' }}">
                    <a href="{{ route('admin.check-in') }}" class="pc-link">
                        <span class="pc-micon"><i class="fas fa-check-circle"></i></span>
                        <span class="pc-mtext">Check-In</span>
                    </a>
                </li>

                <li class="pc-item pc-caption">
                    <label>Payments</label>
                </li>
                <li class="pc-item {{ request()->routeIs('admin.payment-verification*') ? 'active' : '' }}">
                    <a href="{{ route('admin.payment-verification.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="fas fa-credit-card"></i></span>
                        <span class="pc-mtext">Payment Verification</span>
                        @php
                        $pendingPayments = \App\Models\Transaction::where('payment_status', 'pending')->count();
                        @endphp
                        @if($pendingPayments > 0)
                        <span class="badge bg-warning ms-2">{{ $pendingPayments }}</span>
                        @endif
                    </a>
                </li>

                <li class="pc-item pc-caption">
                    <label>Reports</label>
                </li>
                <li class="pc-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    <a href="{{ route('admin.reports.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="fas fa-chart-line"></i></span>
                        <span class="pc-mtext">Revenue Summary</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

