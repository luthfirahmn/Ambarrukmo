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
                        <form class="form-horizontal" method="post" enctype="multipart/form-data" id="formadd">
                             <div class="card-body">
                                 <div class="form-group row">
                                     <div class="col-sm-12">
                                        <textarea class="form-control" name="OurLocation" maxlength="250" id="summernote"><?= isset($all_data->OurLocation) ? $all_data->OurLocation : '' ?> </textarea>
                                   </div>
                                 </div>
                              

                                 <!-- /.card-body -->
                                 <div class="card-footer">
                                     <div class="form-actions form-group">
                                         <a href="<?= base_url("/backend/OurLocation") ?>" class="btn btn-danger float-right">Cancel</a>
                                         <button type="button" style=" margin-right : 6px;" id="save"   class="btn btn-primary float-right save"><i class="fa fa-check"></i> Save </button>
                                     </div>
                                 </div>
                                 <!-- /.card-footer -->
                        </form>
                 </div>
             </div>
         </div>
     </div>
 </section>

 <!-- Summernote -->
 <script src="<?= base_url('assets') ?>/plugins/summernote/summernote-bs4.min.js"></script>

 <script>

    /*SUMMERNOTE*/
    $(function () {
        $('#summernote').summernote()
    })
   

    //ajax add
    $('#save').on('click', function(){
        var link = '<?php echo base_url('backend/OurLocation/update')?>';
        var form = 
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
                    swal.fire({
                                title: "Success",
                                text: "Success",
                                type: "success"
                                }).then(function() {
                                    window.location.href = "<?php echo base_url('backend/OurLocation/index')?>";
                            });
                    }else{
                        $(".error-msg").css('display','block');
                        $(".error-msg").html(data.error);
                        window.scrollTo(0,0)
                    }
            },
           
        });
    }); 
 </script>
