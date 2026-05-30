<!-- jQuery (Required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="{{ asset('vendor/dashboard') }}/assets/js/plugins/popper.min.js"></script>
<script src="{{ asset('vendor/dashboard') }}/assets/js/plugins/simplebar.min.js"></script>
<script src="{{ asset('vendor/dashboard') }}/assets/js/plugins/bootstrap.min.js"></script>
<script src="{{ asset('vendor/dashboard') }}/assets/js/fonts/custom-font.js"></script>
<script src="{{ asset('vendor/dashboard') }}/assets/js/pcoded.js"></script>
<script src="{{ asset('vendor/dashboard') }}/assets/js/plugins/feather.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<!-- End DataTables JS -->

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

