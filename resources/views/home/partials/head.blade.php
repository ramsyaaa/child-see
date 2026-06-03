<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    @php
        $seoTitle       = !empty($cmsSeo['seo_title'])       ? $cmsSeo['seo_title']       : null;
        $seoDescription = !empty($cmsSeo['seo_description']) ? $cmsSeo['seo_description'] : null;
        $seoKeywords    = !empty($cmsSeo['seo_keywords'])    ? $cmsSeo['seo_keywords']    : null;
        $ogImage        = !empty($cmsSeo['og_image'])        ? asset('storage/'.$cmsSeo['og_image']) : asset('logo.png');
        $canonicalUrl   = url()->current();
        $siteName       = config('app.name', 'Child See');
    @endphp

    <title>{{ $seoTitle ?? 'Child See — Your Wellness Sanctuary' }}</title>

    <meta name="description" content="{{ $seoDescription ?? 'Child See — a private, thoughtfully designed space dedicated to wellness, creativity, and connection in Depok, West Java.' }}" />
    @if($seoKeywords)
        <meta name="keywords" content="{{ $seoKeywords }}" />
    @endif

    {{-- Canonical --}}
    <link rel="canonical" href="{{ $canonicalUrl }}" />

    {{-- Open Graph (Facebook, WhatsApp, Telegram link previews) --}}
    <meta property="og:site_name" content="{{ $siteName }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ $canonicalUrl }}" />
    <meta property="og:title" content="{{ $seoTitle ?? $siteName }}" />
    <meta property="og:description" content="{{ $seoDescription ?? '' }}" />
    <meta property="og:image" content="{{ $ogImage }}" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />

    {{-- Twitter / X Card --}}
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $seoTitle ?? $siteName }}" />
    <meta name="twitter:description" content="{{ $seoDescription ?? '' }}" />
    <meta name="twitter:image" content="{{ $ogImage }}" />

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('vendor/inklu/assets/css/style.css') }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet" />
    @stack('styles')
</head>
