<head>
    <title>InkluSyncID - Admin</title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="InkluSyncID Booking System - Admin Dashboard" />
    <meta name="keywords" content="Fitness Studio, Booking System, Admin Dashboard" />
    <meta name="author" content="InkluSyncID" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon" />
    <!-- [Font] Family -->
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/fonts/inter/inter.css" id="main-font-link" />
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/fonts/tabler-icons.min.css" />
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/fonts/feather.css" />
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/fonts/fontawesome.css" />
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/fonts/material.css" />
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/css/style.css" />
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/css/plugins/style.css" />

    <script src="{{ asset('vendor/dashboard') }}/assets/js/tech-stack.js"></script>
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/css/style-preset.css" />

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert') }}/sweetalert.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

    <!-- InkluSyncID Custom Styles -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&display=swap');
        :root {
            --inklu-gold:  #C4923A;
            --inklu-rust:  #B85C38;
            --inklu-brown: #3d2410;
            --inklu-cream: #faf7f2;
            --inklu-border:#e8e0d0;
            --inklu-coral: #C4923A;
            --inklu-sage:  #97B5A9;
            --grad-start: #8B4513;
            --grad-mid:   #C4923A;
            --grad-end:   #3d2410;
        }
        .pc-sidebar { background: var(--inklu-brown) !important; }
        .pc-sidebar .navbar-wrapper,
        .pc-sidebar .navbar-content { background: var(--inklu-brown) !important; }
        .pc-sidebar .m-header { background: var(--inklu-brown) !important; border-bottom: 1px solid rgba(255,255,255,.08) !important; }
        .pc-sidebar .pc-user-card { background: rgba(255,255,255,.06) !important; border: 1px solid rgba(255,255,255,.1) !important; }
        .pc-sidebar .pc-user-card h6 { color: #fff !important; }
        .pc-sidebar .pc-user-card small { color: rgba(255,255,255,.55) !important; }
        .pc-sidebar .pc-link { color: rgba(255,255,255,.65) !important; border-radius: 10px; margin: 2px 8px; }
        .pc-sidebar .pc-link:hover { background: rgba(196,146,58,.2) !important; color: #fff !important; }
        .pc-sidebar .pc-item.active > .pc-link { background: rgba(196,146,58,.25) !important; color: var(--inklu-gold) !important; }
        .pc-sidebar .pc-caption label { color: rgba(255,255,255,.3) !important; font-size: 10px; letter-spacing: .1em; }
        .pc-sidebar .pc-micon i { color: inherit !important; }
        .pc-header { background: #fff !important; border-bottom: 1px solid var(--inklu-border) !important; }
        /* Page-header gradient banner — matches home hero/button gradient */
        .page-header {
            background: linear-gradient(135deg, #3d2410 0%, #8B4513 50%, #C4923A 100%) !important;
            border-radius: 14px !important;
            margin-bottom: 24px !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: 0 4px 20px rgba(61,36,16,.35) !important;
        }
        .page-header .page-block {
            padding: 18px 24px !important;
            background: radial-gradient(ellipse at 80% 50%, rgba(196,146,58,.18) 0%, transparent 60%);
            border-radius: 14px;
            border-bottom: 3px solid rgba(196,146,58,.55);
        }
        .page-header .breadcrumb { margin-bottom: 4px !important; }
        .page-header .breadcrumb-item,
        .page-header .breadcrumb-item a { color: rgba(255,255,255,.6) !important; font-size: 12px !important; text-decoration: none !important; }
        .page-header .breadcrumb-item a:hover { color: #C4923A !important; }
        .page-header .breadcrumb-item.active,
        .page-header li.breadcrumb-item[aria-current] { color: rgba(255,255,255,.9) !important; }
        .page-header .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.3) !important; }
        .page-header h2,
        .page-header .page-header-title h2 {
            color: #fff !important; font-family: 'Playfair Display', serif !important;
            font-size: 1.6rem !important; font-weight: 600 !important;
            margin-bottom: 0 !important; text-shadow: 0 1px 6px rgba(0,0,0,.3);
        }
        /* Buttons */
        .btn-primary { background: linear-gradient(135deg, var(--inklu-gold), var(--inklu-rust)) !important; border: none !important; color: #fff !important; }
        .btn-primary:hover { background: linear-gradient(135deg, var(--inklu-rust), var(--grad-end)) !important; }
        .btn-outline-primary { color: var(--inklu-gold) !important; border-color: var(--inklu-gold) !important; }
        .btn-outline-primary:hover { background-color: var(--inklu-gold) !important; color: #fff !important; }
        .badge.bg-primary { background: linear-gradient(135deg, var(--inklu-gold), var(--inklu-rust)) !important; }
        body { background: var(--inklu-cream) !important; }
        .card { border: 1px solid var(--inklu-border) !important; box-shadow: 0 2px 8px rgba(0,0,0,.04) !important; border-radius: 12px !important; }
        .card-header { background: #fff !important; border-bottom: 1px solid var(--inklu-border) !important; }
        .table thead th { background: #faf7f2; color: #4a4a4a; font-size: 11px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; border-color: var(--inklu-border) !important; }
    </style>
</head>

