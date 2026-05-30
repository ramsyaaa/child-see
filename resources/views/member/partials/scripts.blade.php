<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="{{ asset('vendor/dashboard') }}/assets/js/plugins/popper.min.js"></script>
<script src="{{ asset('vendor/dashboard') }}/assets/js/plugins/simplebar.min.js"></script>
<script src="{{ asset('vendor/dashboard') }}/assets/js/plugins/bootstrap.min.js"></script>
<script src="{{ asset('vendor/dashboard') }}/assets/js/fonts/custom-font.js"></script>
<script src="{{ asset('vendor/dashboard') }}/assets/js/pcoded.js"></script>
<script src="{{ asset('vendor/dashboard') }}/assets/js/plugins/feather.min.js"></script>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    layout_change("light");
</script>
<script>
    change_box_container("false");
</script>
<script>
    layout_caption_change("true");
</script>
<script>
    layout_rtl_change("false");
</script>
<script>
    preset_change("preset-1");
</script>
<script>
    main_layout_change("vertical");
</script>
<script src="{{ asset('vendor/sweetalert') }}/sweetalert.min.js"></script>
@include('vendor.sweet.alert')
@yield('scripts')

