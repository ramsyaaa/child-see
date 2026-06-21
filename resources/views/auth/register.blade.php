<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Daftar — Child See</title>
    <link rel="icon" href="{{ faviconUrl($site ?? null) }}" type="image/x-icon" />
    @if(!empty($site['og_image']))
    <meta property="og:image" content="{{ asset('storage/'.$site['og_image']) }}" />
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display+SC:wght@400;700&family=Josefin+Sans:wght@300;400;600&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        .auth-brand-logo { height: 64px; width: auto; max-width: 180px; object-fit: contain; }
        .auth-brand-logo-mobile { height: 48px; width: auto; max-width: 150px; object-fit: contain; }
        @media (max-width: 575px) {
            .auth-brand-logo-mobile { height: 40px; max-width: 130px; }
        }
    </style>
</head>
<body style="background:#F5F5F6;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1.5rem;">

<div style="width:100%;max-width:900px;background:#fff;border-radius:1.5rem;overflow:hidden;display:grid;grid-template-columns:1fr 1fr;box-shadow:0 8px 40px rgba(74,55,105,0.12);" class="auth-grid">

    {{-- Left panel --}}
    <div class="auth-panel" style="background:#4A3769;position:relative;overflow:hidden;flex-direction:column;justify-content:space-between;padding:2.5rem;display:flex;">
        <div style="position:absolute;inset:0;opacity:0.06;background-image:var(--kawung);background-size:60px 60px;"></div>
        <div style="position:relative;z-index:1;">
            <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:0.5rem;text-decoration:none;">
                @if(!empty($site['site_logo']))
                    <img src="{{ asset('storage/'.$site['site_logo']) }}" alt="{{ $site['site_name'] ?? 'Child See' }}" class="auth-brand-logo">
                @else
                    <svg width="32" height="32" viewBox="0 0 48 48" fill="none">
                        <circle cx="24" cy="24" r="22" stroke="#BAA6D6" stroke-width="2"/>
                        <circle cx="24" cy="24" r="15" stroke="#BAA6D6" stroke-width="1.5"/>
                        <circle cx="24" cy="24" r="8" fill="#BAA6D6" opacity="0.5"/>
                        <circle cx="24" cy="24" r="3" fill="#BAA6D6"/>
                    </svg>
                    <span style="font-family:'Playfair Display SC',serif;color:#F5F5F6;font-size:1.1rem;letter-spacing:0.06em;">Child See</span>
                @endif
            </a>
        </div>
        <div style="position:relative;z-index:1;">
            <h2 style="font-family:'Playfair Display SC',serif;color:#F5F5F6;font-size:2rem;line-height:1.2;margin:0 0 0.75rem;">Buat Akun<br>Sekarang</h2>
            <p style="font-family:'Josefin Sans',sans-serif;color:rgba(245,245,246,0.65);font-size:0.9rem;line-height:1.6;margin:0 0 2rem;">
                Simpan riwayat identifikasi, pantau perkembangan anak dari waktu ke waktu, dan dapatkan laporan lengkap.
            </p>
            <ul style="list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:0.6rem;">
                @foreach(['Gratis selamanya', 'Hasil tersimpan permanen', 'Riwayat per anak', 'Panduan rekomendasi'] as $f)
                <li style="display:flex;align-items:center;gap:0.6rem;font-family:'Josefin Sans';color:rgba(245,245,246,0.8);font-size:0.875rem;">
                    <span style="width:18px;height:18px;background:rgba(186,166,214,0.3);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.65rem;color:#BAA6D6;flex-shrink:0;">✓</span>
                    {{ $f }}
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Right panel --}}
    <div style="padding:2.5rem;display:flex;flex-direction:column;justify-content:center;">
        <div class="auth-panel-mobile" style="display:none;margin-bottom:1.5rem;">
            <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:0.5rem;text-decoration:none;">
                @if(!empty($site['site_logo']))
                    <img src="{{ asset('storage/'.$site['site_logo']) }}" alt="{{ $site['site_name'] ?? 'Child See' }}" class="auth-brand-logo-mobile">
                @else
                    <svg width="24" height="24" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="22" stroke="#5C477F" stroke-width="2"/><circle cx="24" cy="24" r="15" stroke="#5C477F" stroke-width="1.5"/><circle cx="24" cy="24" r="3" fill="#5C477F"/></svg>
                    <span style="font-family:'Playfair Display SC',serif;color:#4A3769;font-size:1rem;">Child See</span>
                @endif
            </a>
        </div>

        <div style="font-family:'JetBrains Mono',monospace;font-size:0.7rem;letter-spacing:0.18em;text-transform:uppercase;color:#9F86C4;margin-bottom:0.5rem;">Akun Baru</div>
        <h1 style="font-family:'Playfair Display SC',serif;color:#4A3769;font-size:1.75rem;margin:0 0 0.25rem;">Daftar</h1>
        <p style="font-family:'Josefin Sans',sans-serif;color:rgba(74,55,105,0.60);font-size:0.9rem;margin:0 0 1.5rem;">Buat akun untuk menyimpan hasil identifikasi.</p>

        @if($errors->any())
        <div style="background:rgba(220,38,38,0.06);border:1px solid rgba(220,38,38,0.25);border-radius:0.75rem;padding:0.75rem 1rem;margin-bottom:1rem;font-family:'Josefin Sans';font-size:0.85rem;color:#b91c1c;">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST" style="display:flex;flex-direction:column;gap:0.85rem;">
            @csrf
            <div class="field">
                <label>Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Budi Santoso" required>
            </div>
            <div class="field">
                <label>Username <span style="font-weight:400;color:rgba(74,55,105,0.45);">(untuk login)</span></label>
                <input type="text" name="username" value="{{ old('username') }}" placeholder="budi.santoso" required autocomplete="username">
            </div>
            <div class="field">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
            </div>
            <div class="field">
                <label>No. Telepon</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+62 812-xxxx-xxxx" required>
            </div>
            <div class="field">
                <label>Kata Sandi <span style="font-weight:400;color:rgba(74,55,105,0.45);">(min. 8 karakter)</span></label>
                <input type="password" name="password" placeholder="••••••••" required minlength="8">
            </div>
            <div class="field">
                <label>Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation" placeholder="••••••••" required minlength="8">
            </div>
            <button type="submit" class="btn-primary" style="width:100%;padding:0.875rem;font-family:'Josefin Sans';font-size:0.95rem;font-weight:600;border-radius:3rem;margin-top:0.25rem;">
                Buat Akun →
            </button>
        </form>

        <p style="font-family:'Josefin Sans';font-size:0.875rem;color:rgba(74,55,105,0.65);text-align:center;margin-top:1.25rem;">
            Sudah punya akun?
            <a href="{{ route('login') }}" style="color:#5C477F;font-weight:600;text-decoration:none;">Masuk di sini</a>
        </p>
    </div>
</div>

<a href="{{ route('home') }}" style="position:fixed;top:1rem;left:1rem;display:flex;align-items:center;gap:0.4rem;font-family:'Josefin Sans';font-size:0.8rem;color:rgba(74,55,105,0.55);text-decoration:none;">
    ← Kembali ke beranda
</a>

<style>
@media(max-width:768px){
    .auth-grid{grid-template-columns:1fr!important;}
    .auth-panel{display:none!important;}
    .auth-panel-mobile{display:flex!important;}
}
</style>
</body>
</html>
