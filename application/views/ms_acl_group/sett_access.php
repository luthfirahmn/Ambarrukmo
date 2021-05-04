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
                     <form class="form-horizontal" action="<?= base_url() ?>backend/aclGroup/access_menu_seve" method="post">
                         <div class="card-body">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-2 col-form-label">Parent</label>
                                <div class="col-sm-10">
                                    <select name="parent" class="form-control" id="exampleSelectRounded0"  onchange='this.form.submit()'>
                                        <?php if(empty($parent)):?>
                                            <option value="" >-- SELECT ACL GROUP --</option>
                                        <?php else:?>
                                            <option value="" disabled ="true">-- SELECT ACL GROUP --</option>
                                        <?php endif;?>
                                        
                                        <?php foreach ($acl_user_group as $key => $values) : ?>
                                        <option value="<?php echo $values->ParamValue ?>" <?php $values->ParamValue == $parent ? "selected" : "" ?>><?php echo $values->ParamValue ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                             <div class="form-group row">
                                 <label for="inputEmail3" class="col-sm-2 col-form-label">Auto</label>
                                 <div class="col-sm-10">
                                     <label class="checkbox"><input type="radio" id="check_all" name="check_all" value="" /> Check All</label>&nbsp; &nbsp; &nbsp;
                                     <label class="checkbox"><input type="radio" id="uncheck_all" name="check_all" value="" /> Uncheck All</label>
                                 </div>
                             </div>
                            <?php if(!empty($menu)):?>
                             <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">Access</label>
                            <div class="col-sm-10">
                                <?php
                                $tag = 0;
                                foreach ($menu as $keys => $val) : ?>
                                    <div id="accordion">
                                        <div class="card ">
                                            <div class="card-header">
                                                <h4 class="card-title w-100">
                                                    <a class="d-block w-100" data-toggle="collapse" href="#collapseOne<?php echo $tag ?>">
                                                        <?php echo $val->Menu ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseOne<?php echo $tag ?>" class="collapse show" data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="panel-body">
                                                    <?php $checked = 'checked="checked"';?>
                                                        <label class="checkbox"><input type="checkbox" class="module_check" name="test[]" value="<?php echo $val->MView; ?>" <?php  $val->MView == 1 ? $checked :''?> /> MView</label>
                                                        <label class="checkbox"><input type="checkbox" class="module_check" name="test[]" value="<?php echo $val->MAdd; ?>" <?php $val->MAdd == 1 ? 'checked="checked"' :''?> /> MAdd</label>
                                                        <label class="checkbox"><input type="checkbox" class="module_check" name="test[]" value="<?php echo $val->MEdit; ?>" <?php $val->MEdit == 1 ? 'checked="checked"' :''?> /> MEdit</label>
                                                        <label class="checkbox"><input type="checkbox" class="module_check" name="test[]" value="<?php echo $val->MDelete; ?>" <?php $val->MDelete == 1 ? 'checked="checked"' :''?> /> MDelete</label>
                                                        <label class="checkbox"><input type="checkbox" class="module_check" name="test[]" value="<?php echo $val->MPrint; ?>" <?php $val->MPrint == 1 ? 'checked="checked"' :''?> /> MPrint</label>
                                                        <label class="checkbox"><input type="checkbox" class="module_check" name="test[]" value="<?php echo $val->MExport; ?>" <?php $val->MExport == 1 ? 'checked="checked"' :''?> /> MExport</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $tag++; ?>
                                <?php endforeach; ?>
                            </div>
                            </div>
                            <?php endif;?>
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