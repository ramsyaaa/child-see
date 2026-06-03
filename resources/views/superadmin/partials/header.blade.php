<!-- [ Header Topbar ] start -->
<header class="pc-header">
    <div class="header-wrapper">
        <!-- Mobile sidebar toggle -->
        <div class="me-auto pc-mob-drp">
            <ul class="list-unstyled">
                <li class="pc-h-item pc-sidebar-collapse">
                    <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="pc-h-item pc-sidebar-popup">
                    <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <!-- Breadcrumb in topbar for mobile -->
                <li class="pc-h-item d-none d-sm-flex align-items-center ms-2">
                    <nav aria-label="breadcrumb" style="margin-bottom:0">
                        <ol class="breadcrumb mb-0" style="font-size:0.78rem;background:none;padding:0;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('superadmin.dashboard') }}" style="color:var(--inklu-lavender,#BAA6D6);">
                                    <i class="ti ti-home-2" style="font-size:0.85rem;"></i>
                                    <span class="ms-1">Child See</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" style="color:rgba(46,32,70,.5);">
                                @yield('page-title', 'Dashboard')
                            </li>
                        </ol>
                    </nav>
                </li>
            </ul>
        </div>

        <!-- Right side -->
        <div class="ms-auto">
            <ul class="list-unstyled d-flex align-items-center gap-1 mb-0">
                <!-- View site -->
                <li class="pc-h-item">
                    <a href="{{ route('home') }}" target="_blank" class="pc-head-link"
                       title="Lihat Situs" style="font-size:0.8rem;">
                        <i class="ti ti-external-link"></i>
                    </a>
                </li>
                <!-- Notification bell -->
                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                       href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ti ti-bell"></i>
                    </a>
                    <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
                        <div class="dropdown-header d-flex align-items-center justify-content-between">
                            <h5 class="m-0">Notifikasi</h5>
                        </div>
                        <div class="dropdown-body text-wrap header-notification-scroll position-relative"
                             style="max-height:calc(100vh - 215px)">
                            <p class="text-center text-muted mt-2 px-3">Tidak ada notifikasi baru.</p>
                        </div>
                    </div>
                </li>
                <!-- User dropdown -->
                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0 d-flex align-items-center gap-2"
                       data-bs-toggle="dropdown" href="#" role="button">
                        <div style="width:32px;height:32px;border-radius:50%;background:rgba(186,166,214,.25);
                                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="ti ti-user" style="color:#BAA6D6;font-size:0.9rem;"></i>
                        </div>
                        <span class="d-none d-md-inline" style="font-size:0.82rem;font-weight:500;">
                            {{ Auth::user()->name ?? 'Superadmin' }}
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="dropdown-header">
                            <h6 class="mb-0">{{ Auth::user()->full_name ?? Auth::user()->name }}</h6>
                            <small class="text-muted text-uppercase" style="font-size:0.7rem;letter-spacing:.08em;">Superadmin</small>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('home') }}" target="_blank" class="dropdown-item">
                            <i class="ti ti-home me-2"></i>Lihat Situs
                        </a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('sign-out') }}" method="POST" class="m-0 p-0">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger" style="background:none;border:none;width:100%;text-align:left;">
                                <i class="ti ti-power me-2"></i>Keluar
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>
<!-- [ Header Topbar ] end -->
