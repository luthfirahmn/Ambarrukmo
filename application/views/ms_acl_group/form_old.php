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
                 <div class="card card-info">
                     <div class="card-header">
                         <h3 class="card-title"><?= $data_tabel ?></h3>
                     </div>
                     <?php if ($status_edit == 0) : ?>
                         <form class="form-horizontal" action="<?= base_url() ?>backend/aclGroup/add" method="post">
                         <?php else : ?>
                             <form class="form-horizontal" action="<?= base_url() ?>backend/aclGroup/edit/<?php echo $all_data->DID ?>" method="post">
                             <?php endif; ?>
                             <div class="card-body">

                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Param</label>
                                     <div class="col-sm-10">
                                         <input type="text" name='ParamID' class="form-control" id="inputEmail3" value="<?= isset($all_data->ParamID) ? $all_data->ParamID : '' ?>" placeholder="Enter Param" required>
                                     </div>
                                 </div>

                                 <div class="form-group row">
                                     <label for="inputPassword3" class="col-sm-2 col-form-label">Param Value</label>
                                     <div class="col-sm-10">
                                         <input type="text" name='ParamValue' class="form-control" id="inputPassword3" value="<?= isset($all_data->ParamValue) ? $all_data->ParamValue: '' ?>" placeholder="Enter Param Value">
                                     </div>
                                 </div>
                                 <!-- /.card-body -->
                                 <div class="card-footer">
                                     <div class="form-actions form-group">
                                         <a href="<?= base_url("/backend/aclGroup") ?>" class="btn btn-danger float-right">Cancel</a>
                                         <button type="submit" style=" margin-right : 6px;" name="save" value="savereturn" class="btn btn-primary float-right"><i class="fa fa-check"></i> Save & Return </button>
                                         <button type="submit" style=" margin-right : 6px;" name="save" value="savedit" class="btn btn-primary float-right"><i class="fa fa-check"></i> Save & Edit </button>
                                         <button type="submit" style=" margin-right : 6px;" name="save" value="savenew" class="btn btn-primary float-right"><i class="fa fa-check"></i> Save & New</button>

                                     </div>
                                 </div>
                                 <!-- /.card-footer -->
                             </form>
                 </div>
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