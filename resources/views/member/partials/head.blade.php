<head>
    <title>InkluSyncID - Member Portal</title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="InkluSyncID Booking System - Member Portal" />
    <meta name="keywords" content="Fitness Studio, Booking, Classes" />
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

    <!-- InkluSyncID Custom Styles -->
    <style>
        :root {
            --inklu-coral: #FF6F51;
            --inklu-sage: #97B5A9;
            --inklu-rose: #E3B7B4;
            --inklu-yellow: #F5DD89;
            --inklu-cream: #FFF6DF;
        }

        .pc-sidebar .m-header {
            background: var(--inklu-coral) !important;
        }

        .btn-primary {
            background-color: var(--inklu-coral);
            border-color: var(--inklu-coral);
        }

        .btn-primary:hover {
            background-color: #e65a3d;
            border-color: #e65a3d;
        }

        .pc-navbar .pc-item.active > .pc-link {
            background-color: rgba(255, 111, 81, 0.1);
            color: var(--inklu-coral);
        }

        .badge.bg-primary {
            background-color: var(--inklu-coral) !important;
        }

        .class-card {
            transition: transform 0.3s ease;
        }

        .class-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>

