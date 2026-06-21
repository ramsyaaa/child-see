<head>
    <title>Child See - Member Portal</title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Child See Booking System - Member Portal" />
    <meta name="keywords" content="Fitness Studio, Booking, Classes" />
    <meta name="author" content="Child See" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ faviconUrl($site ?? null) }}" type="image/x-icon" />
    @if(!empty($site['og_image']))
    <meta property="og:image" content="{{ asset('storage/'.$site['og_image']) }}" />
    @endif
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

    <!-- Child See Custom Styles -->
    <style>
        :root {
            --inklu-primary:  #8E77AB;
            --inklu-indigo:   #4A3769;
            --inklu-lavender: #B9A5D6;
            --inklu-blue:     #8499B6;
            --inklu-sky:      #C6D9E8;
            --inklu-cream:    #F5F5F6;
            --inklu-sidebar:  #2E2046;
        }

        body { background: #F0EDF7 !important; }

        /* Sidebar */
        .pc-sidebar, .pc-sidebar .navbar-wrapper, .pc-sidebar .navbar-content { background: var(--inklu-sidebar) !important; }
        .pc-sidebar .m-header { background: var(--inklu-sidebar) !important; border-bottom: 1px solid rgba(185,165,214,.15) !important; }
        .pc-sidebar .pc-link { color: rgba(245,245,246,.65) !important; border-radius: 10px; margin: 2px 8px; }
        .pc-sidebar .pc-link:hover { background: rgba(185,165,214,.15) !important; color: #fff !important; }
        .pc-sidebar .pc-item.active > .pc-link { background: rgba(185,165,214,.2) !important; color: var(--inklu-lavender) !important; }
        .pc-sidebar .pc-caption label { color: rgba(185,165,214,.45) !important; font-size: 10px !important; letter-spacing: .14em !important; text-transform: uppercase !important; }

        /* Buttons */
        .btn-primary { background-color: var(--inklu-primary) !important; border-color: var(--inklu-primary) !important; color: #fff !important; }
        .btn-primary:hover { background-color: var(--inklu-indigo) !important; border-color: var(--inklu-indigo) !important; }
        .btn-outline-primary { color: var(--inklu-primary) !important; border-color: var(--inklu-primary) !important; }
        .btn-outline-primary:hover { background: var(--inklu-primary) !important; color: #fff !important; }

        /* Badges */
        .badge.bg-primary { background-color: var(--inklu-primary) !important; }

        /* Links */
        a.text-primary, .text-primary { color: var(--inklu-primary) !important; }

        /* Cards */
        .card { border: 1.5px solid rgba(185,165,214,.2) !important; box-shadow: 0 2px 10px rgba(46,32,70,.05) !important; border-radius: 14px !important; }

        /* Member-specific aliases */
        .dash-card { background: #fff; border-radius: 1rem; padding: 1.25rem; border: 1px solid rgba(185,165,214,.18); }
        .btn-primary-outline { color: var(--inklu-primary); border: 1.5px solid var(--inklu-primary); padding: .4rem 1rem; border-radius: .5rem; text-decoration: none; font-size: .85rem; }
    </style>
</head>

