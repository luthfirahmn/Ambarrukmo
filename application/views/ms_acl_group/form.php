 <!-- Content Header (Page header) -->
 <section class="content-header">
     <div class="container-fluid">
         <div class="row mb-2">
             <div class="col-sm-6">
                 <h1><?= $title ?></h1>
             </div>
             <div class="col-sm-6">
                 <ol class="breadcrumb float-sm-right">
                     <li class="breadcrumb-item"><a href="<?= base_url() ?>backend/module"><?= $breadcrumb ?></a></li>
                     <li class="breadcrumb-item active"><?= $breadcrumb1 ?></li>
                 </ol>
             </div>
         </div>
     </div><!-- /.container-fluid -->
 </section>
 <section class="content">
     <div class="container-fluid">
         <?php echo notify_message($success_msg, $error_msg, $info_msg); ?>
         <div class="row">
             <!-- left column -->
             <div class="col-md-12">
                 <!-- general form elements -->
                 
             </div>
         </div>
     </div>
 </section>


 <script src="<?= base_url('assets') ?>/plugins/jquery/jquery.min.js"></script>
 <!-- Bootstrap 4 -->
 <script src="<?= base_url('assets') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
 <!-- bs-custom-file-input -->
 <script src="<?= base_url('assets') ?>/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
 <!-- AdminLTE App -->
 <script src="<?= base_url('assets') ?>/dist/js/adminlte.min.js"></script>
 <!-- AdminLTE for demo purposes -->
 <script src="<?= base_url('assets') ?>/dist/js/demo.js"></script>
 <!-- Page specific script -->