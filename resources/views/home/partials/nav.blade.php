{{-- Child See Navigation --}}
<header class="sticky top-0 z-40" style="background:rgba(245,245,246,0.92);backdrop-filter:blur(14px);border-bottom:1px solid rgba(186,166,214,0.25);">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 h-16 flex items-center justify-between">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <span class="relative grid place-items-center w-10 h-10 rounded-full" style="background:#5C477F;">
                <svg viewBox="0 0 40 40" class="w-8 h-8">
                    <circle cx="20" cy="20" r="14" fill="none" stroke="#BAA6D6" stroke-width="1.4"/>
                    <circle cx="20" cy="20" r="7"  fill="rgba(186,166,214,0.3)"/>
                    <circle cx="20" cy="20" r="3"  fill="#BAA6D6"/>
                    <circle cx="20" cy="9"  r="2"  fill="#F5F5F6" opacity=".7"/>
                    <circle cx="31" cy="20" r="2"  fill="#F5F5F6" opacity=".7"/>
                    <circle cx="20" cy="31" r="2"  fill="#F5F5F6" opacity=".7"/>
                    <circle cx="9"  cy="20" r="2"  fill="#F5F5F6" opacity=".7"/>
                </svg>
            </span>
            <div class="leading-tight">
                <div class="font-display text-[18px] font-semibold" style="color:#4A3769;">Child <span style="color:#BAA6D6;">See</span></div>
                <div class="text-[10px] uppercase -mt-0.5" style="letter-spacing:0.16em;color:rgba(92,71,127,0.55);">Identifikasi ABK</div>
            </div>
        </a>

        {{-- Desktop Nav --}}
        <nav class="hidden md:flex items-center gap-8">
            <a href="{{ route('home') }}" class="text-[14px] font-medium transition-colors" style="color:{{ request()->routeIs('home') ? '#4A3769' : 'rgba(38,34,58,.65)' }};">Beranda</a>
            <a href="{{ route('about') }}" class="text-[14px] font-medium transition-colors" style="color:{{ request()->routeIs('about') ? '#4A3769' : 'rgba(38,34,58,.65)' }};">Tentang</a>
            @auth
                <a href="{{ route('member.dashboard') }}" class="text-[14px] font-medium" style="color:rgba(38,34,58,.65);">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-[14px] font-medium" style="color:rgba(38,34,58,.65);">Masuk</a>
            @endauth
        </nav>

        <div class="flex items-center gap-2">
            @auth
                <a href="{{ route('member.assessment.start') }}" class="hidden md:inline-flex items-center gap-2 px-5 py-2 rounded-full text-sm font-semibold text-white" style="background:linear-gradient(135deg,#4A3769,#5C477F);">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    Mulai Asesmen
                </a>
            @else
                <a href="{{ route('register') }}" class="hidden md:inline-flex items-center gap-2 px-5 py-2 rounded-full text-sm font-semibold text-white" style="background:linear-gradient(135deg,#4A3769,#5C477F);">
                    Mulai Gratis
                </a>
            @endauth
            <button id="menu-btn" class="md:hidden w-10 h-10 flex items-center justify-center rounded-full" style="background:rgba(92,71,127,0.08);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M4 6h16M4 12h16M4 18h16" stroke="#4A3769" stroke-width="1.8" stroke-linecap="round"/></svg>
            </button>
        </div>
    </div>
</header>

{{-- Mobile Drawer --}}
<div id="mobile-menu" class="fixed inset-0 z-[500] bg-black/40 opacity-0 pointer-events-none transition-opacity duration-300">
    <div id="mobile-drawer" class="absolute right-0 top-0 bottom-0 w-72 flex flex-col p-8 translate-x-full transition-transform duration-300" style="background:#F5F5F6;box-shadow:-20px 0 40px -10px rgba(0,0,0,0.18);">
        <div class="flex items-center justify-between mb-10">
            <span class="font-display text-xl" style="color:#4A3769;">Menu</span>
            <button id="menu-close" class="w-9 h-9 flex items-center justify-center rounded-full" style="background:#E9E9EB;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M18 6L6 18M6 6l12 12" stroke="#4A3769" stroke-width="1.8" stroke-linecap="round"/></svg>
            </button>
        </div>
        <nav class="flex flex-col gap-5 text-[15px] font-medium" style="color:#26223A;">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('about') }}">Tentang</a>
            @auth
                <a href="{{ route('member.dashboard') }}">Dashboard</a>
                <a href="{{ route('member.assessment.start') }}" style="color:#5C477F;font-weight:600;">Mulai Asesmen</a>
            @else
                <a href="{{ route('login') }}">Masuk</a>
                <a href="{{ route('register') }}" style="color:#5C477F;font-weight:600;">Daftar Gratis</a>
            @endauth
        </nav>
    </div>
</div>

<script>
(function(){
    const btn = document.getElementById('menu-btn');
    const close = document.getElementById('menu-close');
    const menu = document.getElementById('mobile-menu');
    const drawer = document.getElementById('mobile-drawer');
    function openMenu(){ menu.classList.remove('opacity-0','pointer-events-none'); drawer.classList.remove('translate-x-full'); }
    function closeMenu(){ menu.classList.add('opacity-0','pointer-events-none'); drawer.classList.add('translate-x-full'); }
    btn && btn.addEventListener('click', openMenu);
    close && close.addEventListener('click', closeMenu);
    menu && menu.addEventListener('click', e=>{ if(e.target===menu) closeMenu(); });
})();
</script>
