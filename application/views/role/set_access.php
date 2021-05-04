 <!-- Content Header (Page header) -->
 <section class="content-header">
   <div class="container-fluid">
     <div class="row mb-2">
       <div class="col-sm-6">
         <h1><?= $title ?></h1>
       </div>
       <div class="col-sm-6">
         <ol class="breadcrumb float-sm-right">
           <li class="breadcrumb-item"><a href="<?= base_url() ?>backend/user"><?= $breadcrumb ?></a></li>
           <li class="breadcrumb-item active"><?= $breadcrumb1 ?></li>
         </ol>
       </div>
     </div>
   </div><!-- /.container-fluid -->
 </section>
 <section class="content">
   <div class="container-fluid">
     <div class="row">
       <!-- left column -->
       <div class="col-md-12">
         <!-- general form elements -->
         <div class="card card-info">
           <div class="card-header">
             <h3 class="card-title"><?= $data_tabel ?></h3>
           </div>
           <!-- /.card-header -->
           <!-- form start -->
           <form class="form-horizontal" action="<?= base_url() ?>backend/role/add_access_role" method="post">
             <div class="card-body">
               <div class="form-group row">
                 <label for="inputEmail3" class="col-sm-2 col-form-label">Role</label>
                 <div class="col-sm-10">
                   <input type="hidden" name='id' value="<?php echo $data_role->id; ?>">
                   <input type="text" name='name' class="form-control" id="inputEmail3" value="<?php echo $data_role->name; ?>" readonly>
                 </div>
               </div>

               <div class="form-group row">
                 <label for="inputEmail3" class="col-sm-2 col-form-label">Auto</label>
                 <div class="col-sm-10">
                   <label class="checkbox"><input type="radio" id="check_all" name="check_all" value="" /> Check All</label>&nbsp; &nbsp; &nbsp;
                   <label class="checkbox"><input type="radio" id="uncheck_all" name="check_all" value="" /> Uncheck All</label>
                 </div>
               </div>
               <div class="form-group row">
                 <label for="inputEmail3" class="col-sm-2 col-form-label">Access</label>
                 <div class="col-sm-10">

                   <!-- accordion -->

                   <!-- we are adding the accordion ID so Bootstrap's collapse plugin detects it -->

                   <?php
                    $tag = 0;
                    foreach ($data_role_access as $keys => $values) : ?>
                     <div id="accordion">
                       <div class="card ">
                         <div class="card-header">
                           <h4 class="card-title w-100">
                             <a class="d-block w-100" data-toggle="collapse" href="#collapseOne<?php echo $tag ?>">
                               <?php echo $values['module'] ?>
                             </a>
                           </h4>
                         </div>
                         <div id="collapseOne<?php echo $tag ?>" class="collapse show" data-parent="#accordion">
                           <div class="card-body">
                             <div class="panel-body">
                               <?php asort($values['privileges']) ?>
                               <?php foreach ($values['privileges'] as $ky => $val) : ?>
                                 <?php $checked = '' ?>
                                 <?php if ($val['status'] != 0) {
                                    $checked = 'checked="checked"';
                                  } ?>
                                 <label class="checkbox"><input type="checkbox" class="module_check" name="access[]" value="<?php echo $val['id']; ?>" <?php echo $checked; ?> />
                                   <? echo $val['action']?>
                                 </label>
                               <?php endforeach; ?>
                             </div>
                           </div>
                         </div>
                       </div>
                     </div>
                     <?php $tag++; ?>
                   <?php endforeach; ?>

                   <!-- accordion -->

                 </div>
               </div>
             </div>
             <!-- /.card-body -->
             <div class="card-footer">
               <!-- <button type="submit" class="btn btn-info">Sign in</button> -->
               <button type="submit" style=" margin-right : 6px;" name="save" value="yes" class="btn btn-primary float-right"><i class="fa fa-check"></i> Save</button>
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
 <script>
   $(document).ready(function() {
     $("#uncheck_all").change(function() {
       $(".module_check").prop('checked', false);
     })

     $("#check_all").change(function() {
       $(".module_check").prop('checked', true);
     })
   });
 </script>

<script>
  $(function() {
  bsCustomFileInput.init();
  });