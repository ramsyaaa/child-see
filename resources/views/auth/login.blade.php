<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Masuk — Child See</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display+SC:wght@400;700&family=Josefin+Sans:wght@300;400;600&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body style="background:#F5F5F6;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1.5rem;">

<div style="width:100%;max-width:900px;background:#fff;border-radius:1.5rem;overflow:hidden;display:grid;grid-template-columns:1fr 1fr;box-shadow:0 8px 40px rgba(74,55,105,0.12);" class="auth-grid">

    {{-- Left panel --}}
    <div class="auth-panel hidden md:flex" style="background:#4A3769;position:relative;overflow:hidden;flex-direction:column;justify-content:space-between;padding:2.5rem;">
        <div class="kawung-overlay" style="position:absolute;inset:0;opacity:0.06;background-image:var(--kawung);background-size:60px 60px;"></div>
        <div style="position:relative;z-index:1;">
            <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:0.5rem;text-decoration:none;">
                <svg width="32" height="32" viewBox="0 0 48 48" fill="none">
                    <circle cx="24" cy="24" r="22" stroke="#BAA6D6" stroke-width="2"/>
                    <circle cx="24" cy="24" r="15" stroke="#BAA6D6" stroke-width="1.5"/>
                    <circle cx="24" cy="24" r="8" fill="#BAA6D6" opacity="0.5"/>
                    <circle cx="24" cy="24" r="3" fill="#BAA6D6"/>
                </svg>
                <span style="font-family:'Playfair Display SC',serif;color:#F5F5F6;font-size:1.1rem;letter-spacing:0.06em;">Child See</span>
            </a>
        </div>
        <div style="position:relative;z-index:1;">
            <h2 style="font-family:'Playfair Display SC',serif;color:#F5F5F6;font-size:2rem;line-height:1.2;margin:0 0 0.75rem;">Selamat<br>Datang Kembali</h2>
            <p style="font-family:'Josefin Sans',sans-serif;color:rgba(245,245,246,0.65);font-size:0.9rem;line-height:1.6;margin:0 0 2rem;">
                Masuk untuk melihat riwayat identifikasi dan melanjutkan asesmen anak yang sedang dalam pemantauan.
            </p>
            <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
                @foreach([['37', 'Butir Observasi'], ['3', 'Jenis Hambatan'], ['100%', 'Gratis']] as $s)
                <div style="background:rgba(186,166,214,0.15);border:1px solid rgba(186,166,214,0.25);border-radius:0.75rem;padding:0.6rem 1rem;text-align:center;">
                    <div style="font-family:'Playfair Display SC',serif;color:#F5F5F6;font-size:1.1rem;">{{ $s[0] }}</div>
                    <div style="font-family:'Josefin Sans',sans-serif;color:rgba(245,245,246,0.55);font-size:0.7rem;">{{ $s[1] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right panel --}}
    <div style="padding:2.5rem;display:flex;flex-direction:column;justify-content:center;" class="md:col-span-1 col-span-2">
        <div class="md:hidden mb-6">
            <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:0.5rem;text-decoration:none;">
                <svg width="24" height="24" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="22" stroke="#5C477F" stroke-width="2"/><circle cx="24" cy="24" r="15" stroke="#5C477F" stroke-width="1.5"/><circle cx="24" cy="24" r="3" fill="#5C477F"/></svg>
                <span style="font-family:'Playfair Display SC',serif;color:#4A3769;font-size:1rem;">Child See</span>
            </a>
        </div>

        <div style="font-family:'JetBrains Mono',monospace;font-size:0.7rem;letter-spacing:0.18em;text-transform:uppercase;color:#9F86C4;margin-bottom:0.5rem;">Akun Pengguna</div>
        <h1 style="font-family:'Playfair Display SC',serif;color:#4A3769;font-size:1.75rem;margin:0 0 0.25rem;">Masuk</h1>
        <p style="font-family:'Josefin Sans',sans-serif;color:rgba(74,55,105,0.60);font-size:0.9rem;margin:0 0 1.75rem;">Gunakan username atau email dan kata sandi Anda.</p>

        @if(session('success'))
        <div style="background:rgba(198,217,232,0.3);border:1px solid #C6D9E8;border-radius:0.75rem;padding:0.75rem 1rem;margin-bottom:1rem;font-family:'Josefin Sans';font-size:0.85rem;color:#2a5f6b;">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error') || $errors->any())
        <div style="background:rgba(220,38,38,0.06);border:1px solid rgba(220,38,38,0.25);border-radius:0.75rem;padding:0.75rem 1rem;margin-bottom:1rem;font-family:'Josefin Sans';font-size:0.85rem;color:#b91c1c;">
            {{ session('error') }}
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST" style="display:flex;flex-direction:column;gap:1rem;">
            @csrf
            <div class="field">
                <label>Username atau Email</label>
                <input type="text" name="email" value="{{ old('email') }}" placeholder="username atau nama@email.com" required autofocus autocomplete="username">
            </div>
            <div class="field">
                <label>Kata Sandi</label>
                <div style="position:relative;">
                    <input type="password" name="password" id="pwd" placeholder="••••••••" required style="padding-right:2.75rem;">
                    <button type="button" onclick="togglePwd()" style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:rgba(74,55,105,0.45);padding:0;line-height:1;" title="Tampilkan/sembunyikan">
                        <svg id="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;font-family:'Josefin Sans';font-size:0.85rem;">
                <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;color:rgba(74,55,105,0.75);">
                    <input type="checkbox" name="remember" style="accent-color:#5C477F;width:15px;height:15px;">
                    Ingat saya
                </label>
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="color:#5C477F;text-decoration:none;font-weight:600;">Lupa kata sandi?</a>
                @endif
            </div>
            <button type="submit" class="btn-primary" style="width:100%;padding:0.875rem;font-family:'Josefin Sans';font-size:0.95rem;font-weight:600;border-radius:3rem;margin-top:0.25rem;">
                Masuk →
            </button>
        </form>

        <p style="font-family:'Josefin Sans';font-size:0.875rem;color:rgba(74,55,105,0.65);text-align:center;margin-top:1.5rem;">
            Belum punya akun?
            <a href="{{ route('register') }}" style="color:#5C477F;font-weight:600;text-decoration:none;">Daftar di sini</a>
        </p>
        <div style="border-top:1px solid #E9E9EB;margin-top:1.25rem;padding-top:1rem;text-align:center;">
            <p style="font-family:'JetBrains Mono';font-size:0.7rem;color:rgba(74,55,105,0.40);letter-spacing:0.05em;">Superadmin dan admin dapat masuk dari halaman ini</p>
        </div>
    </div>
</div>

<a href="{{ route('home') }}" style="position:fixed;top:1rem;left:1rem;display:flex;align-items:center;gap:0.4rem;font-family:'Josefin Sans';font-size:0.8rem;color:rgba(74,55,105,0.55);text-decoration:none;">
    ← Kembali ke beranda
</a>

<style>
@media(max-width:768px){.auth-grid{grid-template-columns:1fr!important;}.auth-panel{display:none!important;}}
</style>
<script>
function togglePwd(){
    const p=document.getElementById('pwd');
    p.type=p.type==='password'?'text':'password';
}
</script>
</body>
</html>
