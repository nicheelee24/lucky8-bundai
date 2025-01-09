
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<script>
   //alert(window.location.href);
   if (window.location.href.indexOf("dashboard") > -1) {
       //alert('dashboard');
       $("#lnkDashboard").addClass("active");
   }
   if (window.location.href.indexOf("members") > -1) {
       //alert('dashboard');
       $("#lnkMembers").addClass("active");
   }
   if (window.location.href.indexOf("employee") > -1) {
       //alert('dashboard');
       $("#lnkEmployees").addClass("active");
   }
   if (window.location.href.indexOf("players") > -1) {
       
       $("#lnkPlayrstcs").addClass("active");
   }
   if (window.location.href.indexOf("new-player-stats") > -1) {
       //alert('dashboard');
       $("#lnkNewPlyr").addClass("active");
   }
   if (window.location.href.indexOf("subagent") > -1) {
       //alert('dashboard');
       $("#lnkSubAgents").addClass("active");
   }
   if (window.location.href.indexOf("PLReport") > -1) {
       //alert('dashboard');
       $("#lnkReports").addClass("active");
   }
   if (window.location.href.indexOf("bonus") > -1) {
       //alert('dashboard');
       $("#lnkBonusRep").addClass("active");
   }
   if (window.location.href.indexOf("summary") > -1) {
       //alert('dashboard');
       $("#lnkSummaryRep").addClass("active");
   }
   if (window.location.href.indexOf("history") > -1) {
       //alert('dashboard');
       $("#lnkPlayersHistory").addClass("active");
   }
   if (window.location.href.indexOf("profile") > -1) {
       //alert('dashboard');
       $("#lnkProfile").addClass("active");
   }
   if (window.location.href.indexOf("agent") > -1) {
       //alert('dashboard');
       $("#lnkAgents").addClass("active");
   }
   if (window.location.href.indexOf("settings") > -1) {
       //alert('dashboard');
       $("#lnkSettings").addClass("active");
   }
   if (window.location.href.indexOf("promote") > -1 || window.location.href.indexOf("promotion") > -1) {
       //alert('dashboard');
       $("#lnkPromote").addClass("active");
   }
    </script>
</body>
</html>