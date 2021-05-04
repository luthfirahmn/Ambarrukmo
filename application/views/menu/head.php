<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>AdminLTE 3 | Dashboard</title>
<!-- Dashboard -->
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/fontawesome-free/css/all.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
<!-- iCheck -->
<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
<!-- JQVMap -->
<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/jqvmap/jqvmap.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="<?= base_url('assets') ?>/dist/css/adminlte.min.css">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
<!-- Daterange picker -->
<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/daterangepicker/daterangepicker.css">
<!-- summernote -->
<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/summernote/summernote-bs4.min.css">
<!-- Dashboard -->


<!-- Data tabel -->
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/fontawesome-free/css/all.min.css">
<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="<?= base_url('assets') ?>/dist/css/adminlte.min.css">
<!-- Data tabel -->
<style>

.loading{position:fixed;top:50%;left:50%;z-index:9999;margin-top:-50px;margin-left:-50px;display:none}.mask{position:absolute;left:0;top:0;z-index:9000;background-color:#000;display:none}.category,.item,.chzn-container-single .chzn-single{font-family:sans-serif}.category{font-weight:bold}.chzn-results li.item{padding-left:25px}.packing-notification{background-color:#000;border-radius:35px;float:left;height:70px;text-align:center;font-weight:bold;width:70px;margin-right:10px}.border-radius{color:white;font-size:20px;position:relative;top:20px}.navbar-default .navbar-nav>.open>a,.navbar-default .navbar-nav>.open>a:hover,.navbar-default .navbar-nav>.open>a:focus,.navbar-default .navbar-nav>li>a,.navbar-default .navbar-nav .open .dropdown-menu>li>a,.dropdown-menu>li>a{color:#428bca}.dropdown-menu>li>a>div{color:#333}body{padding-top:30px}

</style>


<script src="<?= base_url('assets') ?>/plugins/jquery/jquery.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/jszip/jszip.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="<?= base_url('assets') ?>/dist/js/adminlte.min.js"></script>
<script src="<?= base_url('assets') ?>/dist/js/demo.js"></script>

<div class="mask"></div>
<div class="loading">
      <img alt="Loading.." src="<?php echo base_url(); ?>assets/img/ajax_loader.gif" style="border: 1px solid #AAAAAA;">
</div>