<!doctype html>
<html lang="en">
@include('admin_new.partials.head')

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr"
    data-pc-theme_contrast="" data-pc-theme="light">
    @include('admin_new.partials.preloader')
    @include('admin_new.partials.navbar')
    @include('admin_new.partials.header')
    <div class="pc-container">
        <div class="pc-content">
            @yield('content')
        </div>
    </div>
    @include('admin_new.partials.footer')
    @include('admin_new.partials.scripts')
</body>

</html>

