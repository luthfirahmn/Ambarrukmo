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
           <form class="form-horizontal" action="<?= base_url() ?>backend/access/add" method="post">
             <div class="card-body">
               <div class="form-group row">
                 <label for="inputEmail3" class="col-sm-2 col-form-label">Privileges</label>
                 <div class="col-sm-10">
                   <input type="text" name='privileges' class="form-control" id="inputEmail3" placeholder="Enter Privileges" required>
                 </div>

               </div>
               <div class="form-group row">
                 <label for="inputEmail3" class="col-sm-2 col-form-label">Module</label>
                 <div class="col-sm-10">
                   <select name="module" class="form-control" id="exampleSelectRounded0">
                     <option value="" disabled selected> -- Select module --</option>
                     <?php foreach ($data_module as $key => $values) : ?>
                       <option value="<?php echo $values->id ?>"><?php echo $values->name ?></option>
                     <?php endforeach; ?>
                   </select>
                 </div>
               </div>

               <?php /* 
                            <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-2 col-form-label">Controllers</label>
                            <div class="col-sm-10">
                                <input type="text" name ='controllers' class="form-control" id="inputPassword3" placeholder="Enter Module Controllers" required>
                            </div>
                            </div>

                            <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-2 col-form-label">Description</label>
                            <div class="col-sm-10">
                                <textarea type="text" name ='description' class="form-control" id="inputPassword3" placeholder="Your Description"></textarea>
                            </div>
                            </div>
                            <div class="form-group row">
                            <div class="offset-sm-2 col-sm-10">
                                <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="exampleCheck2">
                                <label class="form-check-label" for="exampleCheck2">Remember me</label>
                                </div>
                            </div>
                            </div>
                            */ ?>
             </div>
             <!-- /.card-body -->
             <div class="card-footer">
               <div class="form-actions form-group">
                 <a href="#" class="btn btn-danger float-right">Cancel</a>
                 <?php if ($status_edit == 0) : ?>
                   <button type="submit" style=" margin-right : 6px;" name="save" value="yes" class="btn btn-primary float-right"><i class="fa fa-check"></i> Save</button>
                 <?php else : ?>
                   <button type="submit" style=" margin-right : 6px;" name="save" value="no" class="btn btn-primary float-right"><i class="fa fa-check"></i> Update</button>
                 <?php endif; ?>
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
 <script>
   $(function() {
     bsCustomFileInput.init();
   });