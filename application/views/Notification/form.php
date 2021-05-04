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

                                 <!-- SELECT NOTIF TYPE -->
                                <div class="form-group required row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Notif Type</label>
                                     <div class="col-sm-4">
                                         <select class="form-control" name="NotifType">
                                             <option value="<?= isset($all_data->NotifType) ? $all_data->NotifType : '' ?>"><?= isset($all_data->NotifType) ? $all_data->NotifType : 'Select Notif Type' ?></option>
                                            <?php foreach ($NotifType as $row) : ?>
                                             <option value="<?php echo $row->ParamID ?>"><?php echo $row->ParamValue ?></option>
                                             <?php endforeach; ?>
                                         </select>
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                     <label for="example-date-input" class="col-sm-2 col-form-label">Send Time</label>
                                     <div class="col-sm-4">
                                         <input class="form-control" name='SendTime' type="datetime-local" value="<?= isset($all_data->SendTime)? date_format(date_create($all_data->SendTime), "Y-m-d"):''?>" id="example-date-input" >
                                     </div>
                                 </div>

                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Message</label>
                                     <div class="col-sm-10">
                                        <textarea  name="Message" id="summernote" ><?= isset($all_data->Message) ? $all_data->Message : '' ?></textarea>
                                     </div>
                                 </div>
                                 <!-- /.card-body -->
                                 <div class="card-footer">
                                     <div class="form-actions form-group">
                                         <a href="<?= base_url("/backend/Notification") ?>" class="btn btn-danger float-right">Cancel</a>
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

 <!-- Auto Number -->
 <!--  <script src="<?= base_url('assets') ?>/jquery.number.js"></script> -->

 <!-- SUMMERNOTE -->
 <script src="<?= base_url('assets') ?>/plugins/summernote/summernote-bs4.min.js"></script>
 

 <script>
     //DATE NOW MIN
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

    //SUMERNOTE
    $(function (){
        $('#summernote').summernote()
    })
    
     //ajax add
      $('#save').on('click', function(){
        <?php if ($status_edit == 0) : ?>
                var link = '<?php echo base_url('backend/Notification/add')?>';
        <?php else  : ?>
            var link = '<?php echo base_url()?>backend/Notification/edit/<?php echo $all_data->DID ?>';
            <?php endif; ?>
        $.ajax({
            type: "post",
            url : link,
            data: $("#formadd").serialize(),
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

                        swal.fire({
                                title: "Success",
                                text: "Success",
                                type: "success"
                                }).then(function() {
                                 window.location.href = "<?php echo base_url('backend/Notification/index')?>";
                            });
                        /*
                        Swal.fire('Success', '', 'success');
                       */
                    }else{
                        $(".error-msg").css('display','block');
                        $(".error-msg").html(data.error);
                        window.scrollTo(0,0)
                    }
            },
           
        });
    }); 
 </script>