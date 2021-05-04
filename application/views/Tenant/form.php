 <!-- Content Header (Page header) -->
 <style>
     .file {
         visibility: hidden;
         position: absolute;
     }
 </style>

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
         <div class="alert alert-danger error-msg" style="display:none"></div>
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

                         <form class="form-horizontal "  method="post" enctype="multipart/form-data" id="formadd">

                       <?php else : ?>
                             <form class="form-horizontal" method="post" enctype="multipart/form-data" id="formadd">
                         <?php endif; ?> 
                             <div class="card-body">

                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Tenant Image</label>
                                     <div class="col-sm-10">
                                         <div class="ml-2 col-sm-6">
                                             <div id="msg"></div>
                                             <form method="post" id="image-form">
                                                 <input type="file" name="ImagePath" class="file" accept="image/*">
                                                 <div class="input-group my-3">
                                                     <input type="text" class="form-control" disabled placeholder="Upload File" id="file">
                                                     <div class="input-group-append">
                                                         <button type="button" class="browse btn btn-primary">Browse...</button>
                                                     </div>
                                                 </div>
                                             </form>
                                         </div>
                                         <div class="ml-2 col-sm-6">
                                             <?php if (isset($all_data->ImagePath) == "") : ?>
                                                 <img src="<?= base_url() ?>/assets/uploads/200x100.png" id="preview" class="img-thumbnail">
                                             <?php else : ?>
                                                 <img src="<?= base_url() ?>/upload/TENANT/<?= $all_data->ImagePath ?>" id="preview" class="img-thumbnail" width="200" height="100">
                                             <?php endif; ?>
                                         </div>
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Tenant Name</label>
                                     <div class="col-sm-10">
                                         <input type="text" name='TenantName' class="form-control"  value="<?= isset($all_data->TenantName) ? $all_data->TenantName : '' ?>" placeholder="Enter Tenant Name" >
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Tenant Floor</label>
                                     <div class="col-sm-10">
                                         <input type="text" name='TenantFloor' class="form-control"  value="<?= isset($all_data->TenantFloor) ? $all_data->TenantFloor : '' ?>" placeholder="Enter Tenant Floor" >
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">No Order</label>
                                     <div class="col-sm-10">
                                         <input type="number" name='OrderNo' class="form-control"  value="<?= isset($all_data->OrderNo) ? $all_data->OrderNo : '' ?>" placeholder="Enter No Order" max="10">
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                     <label for="inputPassword3" class="col-sm-2 col-form-label">Active</label>
                                     <div class="col-sm-10">
                                         <?php foreach ($active as $keys => $val) : ?>
                                             <?php if (isset($all_data->Active) ? true : false) : ?>
                                                 <div class="form-check form-check-inline">
                                                     <input class="form-check-input" type="radio" name="Active" id="inlineRadio1" value="<?php echo $val->ParamID ?>" <?php echo $all_data->Active == $val->ParamID ? "checked" : "" ?> >
                                                     <label class="form-check-label" for="inlineRadio1"><?php echo $val->ParamValue ?></label>
                                                 </div>
                                             <?php else : ?>
                                                 <div class="form-check form-check-inline">
                                                     <input class="form-check-input" type="radio" name="Active" id="inlineRadio1" value="<?php echo $val->ParamID ?>" >
                                                     <label class="form-check-label" for="inlineRadio1"><?php echo $val->ParamValue ?></label>
                                                 </div>
                                             <?php endif; ?>
                                         <?php endforeach; ?>
                                     </div>
                                 </div>
                                 
                                 <!-- /.card-body -->
                                 <div class="card-footer">
                                     <div class="form-actions form-group">
                                         <a href="<?= base_url("/backend/voucher") ?>" class="btn btn-danger float-right">Cancel</a>
                                         <button type="button" style=" margin-right : 6px;" id="save"  name="save" value="savereturn" class="btn btn-primary float-right save"><i class="fa fa-check"></i> Save </button>
                                     </div>
                                 </div>
                                 <!-- /.card-footer -->
                             </form>
                 </div>
             </div>
         </div>
     </div>
 </section>

<!--  <script src="<?= base_url('assets') ?>/plugins/jquery/jquery.min.js"></script>

 <script src="<?= base_url('assets') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

 <script src="<?= base_url('assets') ?>/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

 <script src="<?= base_url('assets') ?>/dist/js/adminlte.min.js"></script>

 <script src="<?= base_url('assets') ?>/dist/js/demo.js"></script> -->

 <script>



    $(document).on("click", ".browse", function() {
        var file = $(this).parents().find(".file");
        file.trigger("click");
    });
    $('input[type="file"]').change(function(e) {
        var fileName = e.target.files[0].name;
        $("#file").val(fileName);

        var reader = new FileReader();
        reader.onload = function(e) {
        // get loaded data and render thumbnail.
        document.getElementById("preview").src = e.target.result;
    };
    // read the image file as a data URL.
        reader.readAsDataURL(this.files[0]);
    });

     //ajax add
      $('#save').on('click', function(){
        <?php if ($status_edit == 0) : ?>
                var link = '<?php echo base_url('backend/Tenant/add')?>';
        <?php else  : ?>
            var link = '<?php echo base_url()?>backend/Tenant/edit/<?php echo $all_data->DID ?>';
            var form = 
            <?php endif; ?>
        $.ajax({
            type: "post",
            url : link,
            data:new FormData($('#formadd')[0]), 
            processData:false,
            contentType:false,
            cache:false,
            dataType: "json",
            beforeSend: function(){
                $('.save').attr('disable','disabled');
                $('.save').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            complete: function(){
                $('.save').removeAttr('disable');
                $('.save').html('<i class="ti-save"> </i> Save');
            },
            success: function (data){
                  if($.isEmptyObject(data.error)){
                        window.location.href = "<?php echo base_url('backend/Tenant/index')?>";
                    }else{
                        $(".error-msg").css('display','block');
                        $(".error-msg").html(data.error);
                        window.scrollTo(0,0)
                    }
            },
           
        });
    }); 
 </script>