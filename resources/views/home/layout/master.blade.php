<!doctype html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', ($site['seo_title'] ?? '') ?: 'InkluSyncID — Identifikasi Anak Berkebutuhan Khusus')</title>
    <meta name="description" content="@yield('description', ($site['seo_description'] ?? '') ?: 'Platform identifikasi awal Anak Berkebutuhan Khusus (ABK) tingkat Sekolah Dasar untuk guru dan orang tua.')" />
    @if(!empty($site['seo_keywords']))
    <meta name="keywords" content="{{ $site['seo_keywords'] }}" />
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    {{-- Open Graph --}}
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="{{ $site['site_name'] ?? 'InkluSyncID' }}" />
    <meta property="og:title" content="@yield('og_title', $site['seo_title'] ?? 'InkluSyncID')" />
    <meta property="og:description" content="@yield('og_description', $site['seo_description'] ?? '')" />
    @if(!empty($site['og_image']))
    <meta property="og:image" content="{{ asset('storage/' . $site['og_image']) }}" />
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display+SC:ital,wght@0,400;0,700;0,900;1,400&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&family=Josefin+Sans:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen" style="background:#F5F5F6; color:#26223A; font-family:'Josefin Sans',system-ui,sans-serif;">

    @include('home.partials.nav')

    <main>
        @yield('content')
    </main>

    @include('home.partials.footer')

    <div id="toast" class="toast"></div>

    @stack('scripts')
</body>
</html>
