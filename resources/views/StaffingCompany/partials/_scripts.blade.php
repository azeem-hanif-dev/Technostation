
<!-- jQuery -->
<script src="{{asset('staffing_company/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('staffing_company/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('staffing_company/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('staffing_company/plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('staffing_company/plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{asset('staffing_company/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{asset('staffing_company/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('staffing_company/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('staffing_company/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('staffing_company/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('staffing_company/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('staffing_company/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('staffing_company/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('staffing_company/dist/js/adminlte.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('staffing_company/dist/js/demo.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('staffing_company/dist/js/pages/dashboard.js')}}"></script>

<!-- DataTables  & Plugins -->
<script src="{{asset('staffing_company/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('staffing_company/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('staffing_company/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('staffing_company/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('staffing_company/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('staffing_company/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('staffing_company/plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('staffing_company/plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('staffing_company/plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('staffing_company/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('staffing_company/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('staffing_company/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>

<!-- SweetAlert2 -->
<script src="{{asset('staffing_company/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
<!-- Toaster -->
<script src="{{asset('staffing_company/plugins/toastr/toastr.min.js')}}"></script>
<script>
    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })
</script>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>


@stack('script')

