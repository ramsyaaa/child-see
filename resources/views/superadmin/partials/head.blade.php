<head>
    <title>@yield('page-title', 'Dashboard') — Child See</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Child See — Panel Superadmin" />
    <meta name="author" content="Child See" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display+SC:wght@400;700&family=Josefin+Sans:wght@300;400;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="icon" href="{{ !empty($site['site_logo']) ? asset('storage/'.$site['site_logo']) : asset('favicon-childsee.png') }}" type="image/x-icon" />
    @if(!empty($site['og_image']))
    <meta property="og:image" content="{{ asset('storage/'.$site['og_image']) }}" />
    @endif
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/fonts/inter/inter.css" id="main-font-link" />
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/fonts/tabler-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/fonts/feather.css" />
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/fonts/fontawesome.css" />
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/fonts/material.css" />
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/css/style.css" />
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/css/plugins/style.css" />
    <script src="{{ asset('vendor/dashboard') }}/assets/js/tech-stack.js"></script>
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/css/style-preset.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert') }}/sweetalert.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

    <style>
        /* ── Child See Design Tokens ── */
        :root {
            --inklu-indigo:   #4A3769;
            --inklu-violet:   #5C477F;
            --inklu-lavender: #BAA6D6;
            --inklu-sogan:    #9F86C4;
            --inklu-sage:     #839986;
            --inklu-cream:    #F5F5F6;
            --inklu-border:   rgba(186,166,214,0.25);
            --inklu-sidebar:  #2E2046;
        }

        /* ── Body & background ── */
        body { background: #F0EEF5 !important; font-family: 'Josefin Sans', system-ui, sans-serif !important; }

        /* ── Sidebar ── */
        .pc-sidebar,
        .pc-sidebar .navbar-wrapper,
        .pc-sidebar .navbar-content { background: var(--inklu-sidebar) !important; }
        .pc-sidebar .m-header { background: var(--inklu-sidebar) !important; border-bottom: 1px solid rgba(186,166,214,.15) !important; }
        .pc-sidebar .pc-user-card { background: rgba(186,166,214,.08) !important; border: 1px solid rgba(186,166,214,.18) !important; }
        .pc-sidebar .pc-user-card h6 { color: #F5F5F6 !important; font-family: 'Josefin Sans', sans-serif !important; }
        .pc-sidebar .pc-user-card small { color: var(--inklu-lavender) !important; }
        .pc-sidebar .pc-link { color: rgba(245,245,246,.65) !important; border-radius: 10px; margin: 2px 8px; font-family: 'Josefin Sans', sans-serif !important; font-size: 0.875rem !important; }
        .pc-sidebar .pc-link:hover { background: rgba(186,166,214,.15) !important; color: #fff !important; }
        .pc-sidebar .pc-item.active > .pc-link { background: rgba(186,166,214,.22) !important; color: var(--inklu-lavender) !important; }
        .pc-sidebar .pc-caption label { color: rgba(186,166,214,.5) !important; font-size: 10px !important; letter-spacing: .14em !important; font-family: 'JetBrains Mono', monospace !important; text-transform: uppercase !important; }
        .pc-sidebar .pc-micon i { color: inherit !important; }
        .pc-sidebar .pc-user-links a { color: rgba(245,245,246,.7) !important; }

        /* ── Top header ── */
        .pc-header { background: #fff !important; border-bottom: 1px solid var(--inklu-border) !important; }

        /* ── Global overflow guard ── */
        html, body { overflow-x: hidden; max-width: 100%; }
        .pc-container { max-width: 100%; overflow-x: hidden; }
        .pc-content { max-width: 100%; }

        /* ── Child See Page Banner (replaces vendor .page-header) ── */
        .inklu-page-banner {
            background: linear-gradient(135deg, #4A3769 0%, #6B5A8E 55%, #8E77AB 100%);
            border-radius: 14px;
            margin-bottom: 24px;
            padding: 18px 24px;
            border: none;
            box-shadow: 0 4px 20px rgba(46,32,70,.28);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            max-width: 100%;
        }
        .inklu-page-banner > div:first-child {
            min-width: 0;
            max-width: 100%;
            flex: 1 1 auto;
        }
        .inklu-page-banner h2,
        .inklu-page-banner .inklu-subtitle {
            overflow-wrap: break-word;
            word-break: break-word;
        }
        .inklu-page-banner::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle at 88% 50%, rgba(186,166,214,.18) 0%, transparent 55%);
            pointer-events: none;
            z-index: 0;
        }
        .inklu-page-banner > * { position: relative; z-index: 1; }
        .inklu-page-banner .inklu-breadcrumb {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 4px;
            list-style: none;
            padding: 0;
            margin: 0 0 6px 0;
            font-size: 11px;
            max-width: 100%;
            overflow-wrap: break-word;
        }
        .inklu-page-banner .inklu-breadcrumb li { display: flex; align-items: center; gap: 4px; }
        .inklu-page-banner .inklu-breadcrumb li::after { content: '/'; color: rgba(245,245,246,.3); margin-left: 4px; }
        .inklu-page-banner .inklu-breadcrumb li:last-child::after { display: none; }
        .inklu-page-banner .inklu-breadcrumb a { color: rgba(245,245,246,.6); text-decoration: none; }
        .inklu-page-banner .inklu-breadcrumb a:hover { color: var(--inklu-lavender); }
        .inklu-page-banner .inklu-breadcrumb .active { color: rgba(245,245,246,.9); }
        .inklu-page-banner h2 {
            color: #fff;
            font-family: 'Playfair Display SC', serif;
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }
        .inklu-page-banner .inklu-subtitle {
            color: rgba(245,245,246,.6);
            font-size: 0.8rem;
            margin: 3px 0 0 0;
        }
        .inklu-page-banner .inklu-banner-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
            flex-shrink: 0;
        }
        .inklu-page-banner .btn-banner {
            background: rgba(186,166,214,.2);
            color: #fff;
            border: 1px solid rgba(186,166,214,.35);
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 0.82rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background .15s;
        }
        .inklu-page-banner .btn-banner:hover { background: rgba(186,166,214,.35); color: #fff; }
        @media (max-width: 575px) {
            .inklu-page-banner { padding: 14px 16px; }
            .inklu-page-banner h2 { font-size: 1.1rem; }
            .inklu-page-banner .inklu-banner-actions { width: 100%; }
        }

        /* ── Buttons ── */
        .btn-primary { background: var(--inklu-violet) !important; border-color: var(--inklu-violet) !important; color: #fff !important; }
        .btn-primary:hover, .btn-primary:focus { background: var(--inklu-indigo) !important; border-color: var(--inklu-indigo) !important; }
        .btn-outline-primary { color: var(--inklu-violet) !important; border-color: var(--inklu-violet) !important; }
        .btn-outline-primary:hover { background: var(--inklu-violet) !important; color: #fff !important; }

        /* ── Badges ── */
        .badge.bg-primary { background: var(--inklu-violet) !important; }

        /* ── Links ── */
        a.text-primary, .text-primary { color: var(--inklu-violet) !important; }

        /* ── Cards ── */
        .card { border: 1.5px solid var(--inklu-border) !important; box-shadow: 0 2px 10px rgba(46,32,70,.06) !important; border-radius: 14px !important; }
        .card-header { background: #fff !important; border-bottom: 1px solid var(--inklu-border) !important; }

        /* ── Table ── */
        .table thead th { background: rgba(186,166,214,.08); color: rgba(74,55,105,.65); font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; border-color: var(--inklu-border) !important; }
        .table { color: #26223A; }
        .table-hover tbody tr:hover { background: rgba(186,166,214,.07) !important; }

        /* ── DataTables pagination ── */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: var(--inklu-violet) !important;
            border-color: var(--inklu-violet) !important;
            color: #fff !important;
        }

        /* ── Form controls ── */
        .form-control:focus, .form-select:focus { border-color: var(--inklu-lavender) !important; box-shadow: 0 0 0 0.2rem rgba(186,166,214,.25) !important; }

        /* ── Preloader ── */
        .loader-bg { background: var(--inklu-sidebar) !important; }
        .loader-track { background: rgba(186,166,214,.2) !important; }
        .loader-fill { background: var(--inklu-lavender) !important; }

        /* ── Alert / Flash ── */
        .alert-success { background: rgba(131,153,134,.12) !important; border-color: rgba(131,153,134,.4) !important; color: #1f4d22 !important; }
        .alert-danger  { background: rgba(220,38,38,.06) !important; border-color: rgba(220,38,38,.25) !important; color: #991b1b !important; }

        /* ── Inklu stat card ── */
        .inklu-stat-card {
            background: linear-gradient(135deg, #4A3769 0%, #8E77AB 100%);
            border-radius: 14px !important;
            color: #fff;
            padding: 22px 24px;
            position: relative;
            overflow: hidden;
            border: none !important;
        }
        .inklu-stat-card::before {
            content: '';
            position: absolute;
            top: -30px; right: -30px;
            width: 100px; height: 100px;
            border-radius: 50%;
            background: rgba(186,166,214,.18);
        }
        .inklu-stat-card .stat-value { font-family: 'Playfair Display SC', serif; font-size: 2rem; font-weight: 700; line-height: 1; }
        .inklu-stat-card .stat-label { font-size: 11px; opacity: .7; text-transform: uppercase; letter-spacing: .1em; margin-top: 4px; font-family: 'JetBrains Mono', monospace; }

        /* ── pc-content padding ── */
        .pc-content { padding: 16px !important; }
        @media (min-width: 768px) { .pc-content { padding: 24px !important; } }

        /* ── Legacy alias ── */
        .ferensa-stat-card { background: linear-gradient(135deg, var(--inklu-sidebar) 0%, var(--inklu-indigo) 100%); border-radius: 14px !important; color: #fff; padding: 22px 24px; position: relative; overflow: hidden; border: none !important; }

        /* ── Fix vendor .page-header inside .pc-content (old Ferensa views) ── */
        .pc-content .page-header {
            position: relative !important;
            left: auto !important;
            top: auto !important;
            right: auto !important;
            display: block !important;
            background: linear-gradient(135deg, #4A3769 0%, #6B5A8E 55%, #8E77AB 100%) !important;
            border-radius: 14px !important;
            margin-bottom: 24px !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: 0 4px 20px rgba(46,32,70,.28) !important;
            overflow: hidden;
        }
        .pc-content .page-header .page-block { padding: 18px 24px !important; }
        .pc-content .page-header .breadcrumb { margin-bottom: 4px !important; font-size: 12px !important; }
        .pc-content .page-header .breadcrumb-item,
        .pc-content .page-header .breadcrumb-item a { color: rgba(245,245,246,.65) !important; text-decoration: none !important; }
        .pc-content .page-header .breadcrumb-item + .breadcrumb-item::before { color: rgba(245,245,246,.3) !important; }
        .pc-content .page-header li.breadcrumb-item[aria-current],
        .pc-content .page-header li.breadcrumb-item.active { color: rgba(245,245,246,.95) !important; }
        .pc-content .page-header h2,
        .pc-content .page-header h5 { color: #fff !important; font-family: 'Playfair Display SC', serif !important; font-size: 1.25rem !important; font-weight: 700 !important; margin-bottom: 0 !important; }
        .pc-content .page-header p.text-muted { color: rgba(245,245,246,.6) !important; }
        /* Also update token colors to match the softer palette */
        :root {
            --inklu-primary: #8E77AB;
            --inklu-accent:  #B9A5D6;
            --inklu-blue:    #8499B6;
            --inklu-sky:     #C6D9E8;
        }
        .btn-primary { background: var(--inklu-primary) !important; border-color: var(--inklu-primary) !important; }
        .btn-primary:hover { background: var(--inklu-indigo) !important; border-color: var(--inklu-indigo) !important; }
        .btn-outline-primary { color: var(--inklu-primary) !important; border-color: var(--inklu-primary) !important; }
        .btn-outline-primary:hover { background: var(--inklu-primary) !important; color: #fff !important; }
        @media (max-width: 575px) {
            .pc-content .page-header .page-block { padding: 14px 16px !important; }
            .pc-content .page-header h2 { font-size: 1.1rem !important; }
        }

    </style>
    @stack('styles')
</head>
