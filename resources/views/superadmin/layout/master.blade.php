<!doctype html>
<html lang="en">
@include('superadmin.partials.head')

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr"
    data-pc-theme_contrast="" data-pc-theme="light">
    @include('superadmin.partials.preloader')
    @include('superadmin.partials.navbar')
    @include('superadmin.partials.header')

    <div class="pc-container">
        @hasSection('page-banner')
            <div style="padding: 16px 16px 0;">
                @yield('page-banner')
            </div>
        @endif

        <div class="pc-content">
            @yield('content')
        </div>
    </div>

    @include('superadmin.partials.footer')
    @include('superadmin.partials.scripts')
</body>

</html>
