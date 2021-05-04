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

                     <!-- /.card-header -->
                     <!-- form start -->
                     <?php if ($status_edit == 0) : ?>
                         <form class="form-horizontal" action="<?= base_url() ?>backend/menu/add" method="post">
                         <?php else : ?>
                             <form class="form-horizontal" action="<?= base_url() ?>backend/menu/edit/<?php echo $all_data->DID ?>" method="post">
                             <?php endif; ?>
                             <div class="card-body">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Parent</label>
                                     <div class="col-sm-10">
                                         <select name="parent" class="form-control" id="exampleSelectRounded0">
                                             <option value="0">#</option>
                                             <?php foreach ($data_parent as $key => $values) : ?>
                                                 <?php if (isset($all_data->ParentID) ? true : false) : ?>
                                                     <option value="<?php echo $values->DID ?>" <?php echo $all_data->ParentID == $values->DID ? "selected" : "" ?>><?php echo $values->Menu ?></option>
                                                 <?php else : ?>
                                                     <option value="<?php echo $values->DID ?>"><?php echo $values->Menu ?></option>
                                                 <?php endif; ?>
                                             <?php endforeach; ?>
                                         </select>
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Menu Name</label>
                                     <div class="col-sm-10">
                                         <input type="text" name='manu_name' class="form-control" id="inputEmail3" value="<?= isset($all_data->Menu) ? $all_data->Menu : '' ?>" placeholder="Enter Menu Name" required>
                                     </div>
                                 </div>

                                 <div class="form-group row">
                                     <label for="inputPassword3" class="col-sm-2 col-form-label">Menu File</label>
                                     <div class="col-sm-10">
                                         <input type="text" name='manu_file' class="form-control" id="inputPassword3" value="<?= isset($all_data->MenuFile) ? $all_data->MenuFile : '' ?>" placeholder="Enter Menu File">
                                     </div>
                                 </div>

                                 <div class="form-group row">
                                     <label for="inputPassword3" class="col-sm-2 col-form-label">Order No</label>
                                     <div class="col-sm-10">
                                         <input type="number" name='order_no' class="form-control" id="inputPassword3" value="<?= isset($all_data->OrderNo) ? $all_data->OrderNo : '' ?>" placeholder="Enter Order No" required>
                                     </div>
                                 </div>

                                 <div class="form-group row">
                                     <label for="inputPassword3" class="col-sm-2 col-form-label">Active</label>
                                     <div class="col-sm-10">
                                         <?php foreach ($active as $keys => $val) : ?>
                                             <?php if (isset($all_data->Active) ? true : false) : ?>
                                                 <div class="form-check form-check-inline">
                                                     <input class="form-check-input" type="radio" name="active" id="inlineRadio1" value="<?php echo $val->ParamID ?>" <?php echo $all_data->Active == $val->ParamID ? "checked" : "" ?> required>
                                                     <label class="form-check-label" for="inlineRadio1"><?php echo $val->ParamValue ?></label>
                                                 </div>
                                             <?php else : ?>
                                                 <div class="form-check form-check-inline">
                                                     <input class="form-check-input" type="radio" name="active" id="inlineRadio1" value="<?php echo $val->ParamID ?>" required>
                                                     <label class="form-check-label" for="inlineRadio1"><?php echo $val->ParamValue ?></label>
                                                 </div>
                                             <?php endif; ?>
                                         <?php endforeach; ?>
                                     </div>
                                 </div>
                                 <!-- /.card-body -->
                                 <div class="card-footer">
                                     <div class="form-actions form-group">
                                         <a href="<?= base_url('backend/menu')?>" class="btn btn-danger float-right">Cancel</a>
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

<?php /* 
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
*/?>