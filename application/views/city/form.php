 <!-- Content Header (Page header) -->
 <section class="content-header">
   <div class="container-fluid">
     <div class="row mb-2">
       <div class="col-sm-6">
         <h1><?= $title ?></h1>
       </div>
       <div class="col-sm-6">
         <ol class="breadcrumb float-sm-right">
           <li class="breadcrumb-item"><a href="<?= base_url() ?>backend/city"><?= $breadcrumb ?></a></li>
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
           <?php if ($status_edit == 0) : ?>
             <form class="form-horizontal" action="<?= base_url() ?>backend/city/add" method="post">
             <?php else : ?>
               <form class="form-horizontal" action="<?= base_url() ?>backend/city/edit" method="post">
               <?php endif; ?>
               <div class="card-body">
                 <input type="hidden" name='id' value="<?= isset($all_data->city_id) ? $all_data->city_id : '' ?>">
                 <div class="form-group row">
                   <label for="inputEmail3" class="col-sm-2 col-form-label">City name</label>
                   <div class="col-sm-10">
                     <input type="text" name='city_name' class="form-control" id="inputEmail3" value="<?= isset($all_data->city_name) ? $all_data->city_name : '' ?>" placeholder="Enter Module City" required>
                   </div>
                 </div>
                 <div class="form-group row">
                   <label for="inputEmail3" class="col-sm-2 col-form-label">Slug</label>
                   <div class="col-sm-10">
                     <input type="text" name='city_name' class="form-control" id="inputEmail3" value="<?= isset($all_data->city_name) ? $all_data->city_name : '' ?>" placeholder="Enter Module City" required>
                   </div>
                 </div>

                 <div class="form-group row">
                   <label for="inputEmail3" class="col-sm-2 col-form-label">Country id</label>
                   <div class="col-sm-10">
                     <input type="text" name='country' class="form-control" id="inputEmail3" value="<?= isset($all_data->country_id) ? $all_data->country_id : '' ?>" placeholder="Enter Module Country" required>
                   </div>
                 </div>

                 <div class="form-group row">
                   <label for="inputPassword3" class="col-sm-2 col-form-label">About</label>
                   <div class="col-sm-10">
                     <input type="text" name='about' class="form-control" id="inputPassword3" value="<?= isset($all_data->about) ? $all_data->about : '' ?>" placeholder="Enter Module About" required>
                   </div>
                 </div>


                 <div class="form-group row">
                   <label for="inputEmail3" class="col-sm-2 col-form-label">Oprating house</label>
                   <div class="col-sm-10">
                     <input type="text" name='city_name' class="form-control" id="inputEmail3" value="<?= isset($all_data->city_name) ? $all_data->city_name : '' ?>" placeholder="Enter Module City" required>
                   </div>
                 </div>

                 <div class="form-group row">
                   <label for="inputEmail3" class="col-sm-2 col-form-label">Adress 1</label>
                   <div class="col-sm-10">
                     <input type="text" name='city_name' class="form-control" id="inputEmail3" value="<?= isset($all_data->city_name) ? $all_data->city_name : '' ?>" placeholder="Enter Module City" required>
                   </div>
                 </div>

                 <div class="form-group row">
                   <label for="inputEmail3" class="col-sm-2 col-form-label">Address 2</label>
                   <div class="col-sm-10">
                     <input type="text" name='city_name' class="form-control" id="inputEmail3" value="<?= isset($all_data->city_name) ? $all_data->city_name : '' ?>" placeholder="Enter Module City" required>
                   </div>
                 </div>

                 <div class="form-group row">
                   <label for="inputEmail3" class="col-sm-2 col-form-label">Phone 1</label>
                   <div class="col-sm-10">
                     <input type="text" name='city_name' class="form-control" id="inputEmail3" value="<?= isset($all_data->city_name) ? $all_data->city_name : '' ?>" placeholder="Enter Module City" required>
                   </div>
                 </div>

                 <div class="form-group row">
                   <label for="inputEmail3" class="col-sm-2 col-form-label">Phone 2</label>
                   <div class="col-sm-10">
                     <input type="text" name='city_name' class="form-control" id="inputEmail3" value="<?= isset($all_data->city_name) ? $all_data->city_name : '' ?>" placeholder="Enter Module City" required>
                   </div>
                 </div>

                 <div class="form-group row">
                   <label for="inputEmail3" class="col-sm-2 col-form-label">Wa Phone</label>
                   <div class="col-sm-10">
                     <input type="text" name='city_name' class="form-control" id="inputEmail3" value="<?= isset($all_data->city_name) ? $all_data->city_name : '' ?>" placeholder="Enter Module City" required>
                   </div>
                 </div>
                

                 
                 <div class="form-group row">
                   <label for="inputPassword3" class="col-sm-2 col-form-label">Description</label>
                   <div class="col-sm-10">
                     <textarea type="text" name='description' class="form-control" id="inputPassword3" placeholder="Your Description"><?= isset($all_data->short_description) ? $all_data->short_description : '' ?></textarea>
                   </div>
                 </div>
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