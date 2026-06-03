<!doctype html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard') — Child See</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Josefin+Sans:wght@300;400;600&display=swap" rel="stylesheet" />
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('vendor/sweetalert') }}/sweetalert.css">
  <style>
    body { font-family: 'Josefin Sans', sans-serif; background: #F0EEF5; }
    .sidebar { width: 260px; background: #2E2046; min-height: 100vh; flex-shrink: 0; position: fixed; left: 0; top: 0; bottom: 0; z-index: 50; transition: transform .3s; overflow-y: auto; }
    .sidebar-link { display: flex; align-items: center; gap: 12px; padding: 10px 20px; color: rgba(255,255,255,.65); font-size: 14px; font-weight: 500; border-radius: 10px; margin: 2px 12px; transition: all .2s; text-decoration: none; }
    .sidebar-link:hover { background: rgba(90,71,127,.5); color: #fff; }
    .sidebar-link.active { background: rgba(142,119,171,.3); color: #B9A5D6; }
    .sidebar-link i { width: 18px; text-align: center; font-size: 15px; }
    .sidebar-section { padding: 6px 20px; font-size: 10px; font-weight: 700; color: rgba(255,255,255,.3); letter-spacing: .1em; text-transform: uppercase; margin-top: 8px; }
    .main-content { margin-left: 260px; min-height: 100vh; display: flex; flex-direction: column; }
    .topbar { background: #fff; border-bottom: 1px solid rgba(186,166,214,.2); padding: 0 28px; height: 64px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 40; }
    .dash-card { background: #fff; border: 1px solid rgba(186,166,214,.2); border-radius: 16px; padding: 24px; transition: all .2s; }
    .dash-card:hover { box-shadow: 0 4px 20px rgba(46,32,70,.08); }
    .form-input { width: 100%; padding: 10px 14px; border: 1.5px solid rgba(186,166,214,.4); border-radius: 10px; font-size: 14px; color: #1a1a1a; background: #F5F5F6; outline: none; transition: all .2s; font-family: 'Josefin Sans', sans-serif; }
    .form-input:focus { border-color: #8E77AB; background: #fff; box-shadow: 0 0 0 3px rgba(142,119,171,.15); }
    .badge-low { background: #dcfce7; color: #166534; font-size: 11px; font-weight: 600; padding: 2px 10px; border-radius: 999px; }
    .badge-medium { background: #fef9c3; color: #854d0e; font-size: 11px; font-weight: 600; padding: 2px 10px; border-radius: 999px; }
    .badge-high { background: #fee2e2; color: #991b1b; font-size: 11px; font-weight: 600; padding: 2px 10px; border-radius: 999px; }
    .badge-none { background: #f3f4f6; color: #6b7280; font-size: 11px; font-weight: 600; padding: 2px 10px; border-radius: 999px; }
    .btn-primary { display: inline-flex; align-items: center; gap: 8px; background: #8E77AB; color: #fff; padding: 10px 20px; border-radius: 10px; font-size: 14px; font-weight: 600; text-decoration: none; transition: all .2s; border: none; cursor: pointer; font-family: 'Josefin Sans', sans-serif; }
    .btn-primary:hover { background: #4A3769; }
    .btn-secondary { display: inline-flex; align-items: center; gap: 8px; background: #F0EEF5; color: #4A3769; padding: 10px 20px; border-radius: 10px; font-size: 14px; font-weight: 600; text-decoration: none; transition: all .2s; border: 1px solid rgba(186,166,214,.4); cursor: pointer; font-family: 'Josefin Sans', sans-serif; }
    .btn-secondary:hover { background: #BAA6D6; color: #fff; }
    @media (max-width: 768px) {
      .sidebar { transform: translateX(-100%); }
      .sidebar.open { transform: translateX(0); }
      .main-content { margin-left: 0; }
    }
    .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 49; }
    .sidebar-overlay.visible { display: block; }
  </style>
  @stack('styles')
</head>
<body>

{{-- Sidebar overlay (mobile) --}}
<div class="sidebar-overlay" id="sidebar-overlay" onclick="closeSidebar()"></div>

{{-- Sidebar --}}
<aside class="sidebar" id="sidebar">
  <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#8E77AB">
      <i class="fas fa-eye text-white text-base"></i>
    </div>
    <div>
      <div class="text-white font-semibold text-sm" style="font-family:'Playfair Display',serif">Child See</div>
      <div class="text-white/40 text-xs">Member Portal</div>
    </div>
  </div>

  <nav class="py-4">
    <div class="sidebar-section">Menu Utama</div>
    <a href="{{ route('member.dashboard') }}" class="sidebar-link {{ request()->routeIs('member.dashboard') ? 'active' : '' }}">
      <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('member.children.index') }}" class="sidebar-link {{ request()->routeIs('member.children.*') ? 'active' : '' }}">
      <i class="fas fa-child"></i> Profil Anak
    </a>
    <a href="{{ route('member.assessment.start') }}" class="sidebar-link {{ request()->routeIs('member.assessment.start') || request()->routeIs('member.assessment.questions') ? 'active' : '' }}">
      <i class="fas fa-clipboard-list"></i> Mulai Asesmen
    </a>
    <a href="{{ route('member.assessment.history') }}" class="sidebar-link {{ request()->routeIs('member.assessment.history') || request()->routeIs('member.assessment.result') ? 'active' : '' }}">
      <i class="fas fa-history"></i> Riwayat Asesmen
    </a>

    <div class="sidebar-section">Akun</div>
    <a href="{{ route('member.profile.index') }}" class="sidebar-link {{ request()->routeIs('member.profile.*') ? 'active' : '' }}">
      <i class="fas fa-user"></i> Profil Saya
    </a>
    <div class="mx-3 mt-2">
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="sidebar-link w-full text-left" style="background:rgba(220,38,38,.15);color:#fca5a5">
          <i class="fas fa-sign-out-alt"></i> Keluar
        </button>
      </form>
    </div>
  </nav>
</aside>

{{-- Main content --}}
<div class="main-content">
  {{-- Topbar --}}
  <header class="topbar">
    <div class="flex items-center gap-3">
      <button onclick="toggleSidebar()" class="text-[#4A3769] hover:text-[#5C477F] md:hidden">
        <i class="fas fa-bars text-lg"></i>
      </button>
      <h2 class="text-sm font-semibold text-[#2E2046]" style="font-family:'Playfair Display',serif">@yield('page-title', 'Dashboard')</h2>
    </div>
    <div class="flex items-center gap-3">
      <div class="flex items-center gap-2">
        <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-semibold" style="background:linear-gradient(135deg,#4A3769,#5C477F)">
          {{ strtoupper(substr(Auth::user()->name ?? 'M', 0, 1)) }}
        </div>
        <div class="hidden sm:block">
          <div class="text-xs font-semibold text-[#2E2046]">{{ Auth::user()->full_name ?? Auth::user()->name }}</div>
          <div class="text-[10px] text-[#7a7a7a]">Member</div>
        </div>
      </div>
    </div>
  </header>

  {{-- Page content --}}
  <main class="flex-1 p-6 md:p-8">
    @yield('content')
  </main>

  {{-- Footer --}}
  <footer class="px-8 py-4 border-t bg-white text-xs text-center" style="border-color:rgba(186,166,214,.2);color:#9ca3af">
    &copy; {{ date('Y') }} Child See
  </footer>
</div>

<!-- SweetAlert2 -->
<script src="{{ asset('vendor/sweetalert') }}/sweetalert.js"></script>
@include('sweetalert::alert')

<script>
function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('open');
  document.getElementById('sidebar-overlay').classList.add('visible');
}
function closeSidebar() {
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('sidebar-overlay').classList.remove('visible');
}
</script>
@stack('scripts')
</body>
</html>