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
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Voucher Code</label>
                                     <div class="col-sm-10">
                                         <input type="text" name='VoucherCode' class="form-control"  value="<?= isset($all_data->VoucherCode) ? $all_data->VoucherCode : '' ?>" placeholder="Enter Voucher Code" >
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Voucher Name</label>
                                     <div class="col-sm-10">
                                         <input type="text" name='VoucherName' class="form-control"  value="<?= isset($all_data->VoucherName) ? $all_data->VoucherName : '' ?>" placeholder="Enter Voucher Name" >
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Redeem Point</label>
                                     <div class="col-sm-10">
                                         <input type="number" name='RedeemPoint' class="form-control"  value="<?= isset($all_data->RedeemPoint) ? $all_data->RedeemPoint : '' ?>" placeholder="Enter Redeem Point" >
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Redeem Code</label>
                                     <div class="col-sm-4">
                                         <input type="number" name='RedeemCode' class="form-control"  value="<?= isset($all_data->RedeemCode) ? $all_data->RedeemCode : '' ?>" id="RedeemCode" placeholder="Click button to redeem code" readonly>
                                     </div>
                                     <div class="col-sm-4">
                                         <button type="button" class="btn btn-info" id="generatecode" >Generate Code</button>
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Quantity</label>
                                     <div class="col-sm-10">
                                         <input type="number" name='Qty' class="form-control"  value="<?= isset($all_data->Qty) ? $all_data->Qty : '' ?>" placeholder="Enter Quantity" >
                                     </div>
                                 </div>

                                 <div class="form-group row">
                                     <label for="example-date-input" class="col-sm-2 col-form-label">Expired Time</label>
                                     <div class="col-sm-10">
                                         <input class="form-control" name='ExpiredTime' type="date" value="<?= isset($all_data->ExpiredTime)? date_format(date_create($all_data->ExpiredTime), "Y-m-d"):''?>" id="example-date-input" >
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Voucher IMG</label>
                                     <div class="col-sm-10">
                                         <div class="ml-2 col-sm-6">
                                             <div id="msg"></div>
                                             <form method="post" id="image-form">
                                                 <input type="file" name="VoucherIMG" class="file" accept="image/*">
                                                 <div class="input-group my-3">
                                                     <input type="text" class="form-control" disabled placeholder="Upload File" id="file">
                                                     <div class="input-group-append">
                                                         <button type="button" class="browse btn btn-primary">Browse...</button>
                                                     </div>
                                                 </div>
                                             </form>
                                         </div>
                                         <div class="ml-2 col-sm-6">
                                             <?php if (isset($all_data->VoucherIMG) == "") : ?>
                                                 <img src="<?= base_url() ?>/assets/uploads/200x100.png" id="preview" class="img-thumbnail">
                                             <?php else : ?>
                                                 <img src="<?= base_url() ?>/upload/VOUCHER/<?= $all_data->VoucherIMG ?>" id="preview" class="img-thumbnail" width="200" height="100">
                                             <?php endif; ?>
                                         </div>
                                     </div>
                                 </div>

                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Voucher Short Note</label>
                                     <div class="col-sm-10">
                                        <textarea class="form-control" name="VoucherShortNote" maxlength="150" id="summernote2" ><?= isset($all_data->VoucherShortNote) ? $all_data->VoucherShortNote : '' ?> </textarea>
                                   </div>
                                 </div>
  
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Voucher Note</label>
                                     <div class="col-sm-10">
                                        <textarea  name="VoucherNote" id="summernote" ><?= isset($all_data->VoucherNote) ? $all_data->VoucherNote : '' ?></textarea>
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
  <!-- Summernote -->
  <script src="<?= base_url('assets') ?>/plugins/summernote/summernote-bs4.min.js"></script>

 <script>
    $( document ).ready(function() {
        //min date
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();
         if(dd<10){
                dd='0'+dd
            } 
            if(mm<10){
                mm='0'+mm
            } 
        today = yyyy+'-'+mm+'-'+dd;
        document.getElementById("example-date-input").setAttribute("min", today);

    });



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

    $(function () {
        $('#summernote').summernote()
    })

    $(function () {
        $('#summernote2').summernote()
    })

     //ajax add
      $('#save').on('click', function(){
        <?php if ($status_edit == 0) : ?>
                var link = '<?php echo base_url('backend/Voucher/add')?>';
        <?php else  : ?>
            var link = '<?php echo base_url()?>backend/Voucher/edit/<?php echo $all_data->DID ?>';
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
                        window.location.href = "<?php echo base_url('backend/Voucher/index')?>";
                    }else{
                        $(".error-msg").css('display','block');
                        $(".error-msg").html(data.error);
                        window.scrollTo(0,0)
                    }
            },
           
        });
    }); 

       //generate code
      $('#generatecode').on('click', function(){
        var random = Math.floor(1000 + Math.random() * 9000);
         $('#RedeemCode').val(random);
    })
 </script>