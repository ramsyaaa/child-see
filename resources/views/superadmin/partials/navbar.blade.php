<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        {{-- Logo --}}
        <div class="m-header" style="background:var(--inklu-sidebar,#2E2046)!important;border-bottom:1px solid rgba(186,166,214,.15);">
            <a href="{{ route('superadmin.dashboard') }}" class="b-brand text-white"
               style="display:flex;align-items:center;gap:0.6rem;padding:0 1rem;text-decoration:none;">
                @if(!empty($site['site_logo']))
                    <img src="{{ asset('storage/'.$site['site_logo']) }}" alt="{{ $site['site_name'] ?? 'Child See' }}" class="brand-logo-img">
                @else
                    <svg width="30" height="30" viewBox="0 0 48 48" fill="none">
                        <circle cx="24" cy="24" r="22" stroke="#BAA6D6" stroke-width="2"/>
                        <circle cx="24" cy="24" r="15" stroke="#BAA6D6" stroke-width="1.5"/>
                        <circle cx="24" cy="24" r="8"  fill="#BAA6D6" opacity="0.35"/>
                        <circle cx="24" cy="24" r="3"  fill="#BAA6D6"/>
                    </svg>
                    <span style="font-family:'Playfair Display SC',serif;color:#F5F5F6;font-size:0.95rem;letter-spacing:0.06em;">Child See</span>
                @endif
            </a>
        </div>

        <div class="navbar-content">
            {{-- User card --}}
            <div class="card pc-user-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div style="width:42px;height:42px;border-radius:50%;background:rgba(186,166,214,.25);display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-user-circle" style="font-size:1.5rem;color:#BAA6D6;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3 me-2">
                            @auth
                            <h6 class="mb-0" style="font-size:0.875rem;">{{ Auth::user()->full_name ?? Auth::user()->name }}</h6>
                            <small style="font-family:'JetBrains Mono',monospace;font-size:0.7rem;text-transform:uppercase;letter-spacing:0.1em;">Superadmin</small>
                            @endauth
                        </div>
                        <a class="btn btn-icon btn-link-secondary avtar" data-bs-toggle="collapse" href="#pc_sidebar_userlink">
                            <svg class="pc-icon"><use xlink:href="#custom-sort-outline"></use></svg>
                        </a>
                    </div>
                    <div class="collapse pc-user-links" id="pc_sidebar_userlink">
                        <div class="pt-3">
                            <a href="{{ route('home') }}" target="_blank"><i class="ti ti-home"></i><span>Lihat Situs</span></a>
                            <form action="{{ route('sign-out') }}" method="POST" style="display:inline;">@csrf<button type="submit" style="background:none;border:none;padding:0;cursor:pointer;display:flex;align-items:center;gap:8px;color:inherit;font-size:inherit;"><i class="ti ti-power"></i><span>Keluar</span></button></form>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="pc-navbar">
                {{-- ── Overview ── --}}
                <li class="pc-item pc-caption"><label>Overview</label></li>
                <li class="pc-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('superadmin.dashboard') }}" class="pc-link">
                        <span class="pc-micon"><i class="fas fa-chart-pie"></i></span>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>

                {{-- ── Asesmen ── --}}
                <li class="pc-item pc-caption"><label>Asesmen</label></li>
                <li class="pc-item {{ request()->routeIs('superadmin.assessments*') ? 'active' : '' }}">
                    <a href="{{ route('superadmin.assessments.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="fas fa-clipboard-check"></i></span>
                        <span class="pc-mtext">Hasil Asesmen</span>
                    </a>
                </li>
                <li class="pc-item {{ request()->routeIs('superadmin.categories*') ? 'active' : '' }}">
                    <a href="{{ route('superadmin.categories.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="fas fa-layer-group"></i></span>
                        <span class="pc-mtext">Kategori ABK</span>
                    </a>
                </li>
                <li class="pc-item {{ request()->routeIs('superadmin.domains*') ? 'active' : '' }}">
                    <a href="{{ route('superadmin.domains.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="fas fa-sitemap"></i></span>
                        <span class="pc-mtext">Domain</span>
                    </a>
                </li>
                <li class="pc-item {{ request()->routeIs('superadmin.questionnaires*') ? 'active' : '' }}">
                    <a href="{{ route('superadmin.questionnaires.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="fas fa-tasks"></i></span>
                        <span class="pc-mtext">Pertanyaan</span>
                    </a>
                </li>
                <li class="pc-item {{ request()->routeIs('superadmin.rules*') ? 'active' : '' }}">
                    <a href="{{ route('superadmin.rules.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="fas fa-balance-scale"></i></span>
                        <span class="pc-mtext">Aturan Penilaian</span>
                    </a>
                </li>

                {{-- ── Pengguna ── --}}
                <li class="pc-item pc-caption"><label>Pengguna</label></li>
                <li class="pc-item {{ request()->routeIs('superadmin.members*') ? 'active' : '' }}">
                    <a href="{{ route('superadmin.members.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="fas fa-users"></i></span>
                        <span class="pc-mtext">Manajemen Akun</span>
                    </a>
                </li>

                {{-- ── Konten Landing ── --}}
                <li class="pc-item pc-caption"><label>Konten Landing</label></li>
                <li class="pc-item {{ request()->routeIs('superadmin.landing.index') ? 'active' : '' }}">
                    <a href="{{ route('superadmin.landing.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="fas fa-globe"></i></span>
                        <span class="pc-mtext">Ringkasan Landing</span>
                    </a>
                </li>
                <li class="pc-item {{ request()->routeIs('superadmin.landing.facts*') ? 'active' : '' }}">
                    <a href="{{ route('superadmin.landing.facts') }}" class="pc-link">
                        <span class="pc-micon"><i class="fas fa-lightbulb"></i></span>
                        <span class="pc-mtext">Fakta Unik</span>
                    </a>
                </li>
                <li class="pc-item {{ request()->routeIs('superadmin.landing.team*') ? 'active' : '' }}">
                    <a href="{{ route('superadmin.landing.team') }}" class="pc-link">
                        <span class="pc-micon"><i class="fas fa-user-friends"></i></span>
                        <span class="pc-mtext">Tim Pengembang</span>
                    </a>
                </li>
                <li class="pc-item {{ request()->routeIs('superadmin.landing.hki*') ? 'active' : '' }}">
                    <a href="{{ route('superadmin.landing.hki') }}" class="pc-link">
                        <span class="pc-micon"><i class="fas fa-certificate"></i></span>
                        <span class="pc-mtext">HKI / Paten</span>
                    </a>
                </li>
                <li class="pc-item {{ request()->routeIs('superadmin.landing.partners*') ? 'active' : '' }}">
                    <a href="{{ route('superadmin.landing.partners') }}" class="pc-link">
                        <span class="pc-micon"><i class="fas fa-handshake"></i></span>
                        <span class="pc-mtext">Partner</span>
                    </a>
                </li>

                {{-- ── Pengaturan ── --}}
                <li class="pc-item pc-caption"><label>Pengaturan</label></li>
                <li class="pc-item {{ request()->routeIs('superadmin.settings*') ? 'active' : '' }}">
                    <a href="{{ route('superadmin.settings.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="fas fa-sliders-h"></i></span>
                        <span class="pc-mtext">Pengaturan Situs</span>
                    </a>
                </li>
                <li class="pc-item {{ request()->routeIs('superadmin.content*') ? 'active' : '' }}">
                    <a href="{{ route('superadmin.content.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="fas fa-file-alt"></i></span>
                        <span class="pc-mtext">Konten CMS</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
