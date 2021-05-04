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
            <?php if ($status_edit == 1) { ?>
            <div class="col-md-12">
                <div class="callout callout-info">
                  <h5>HEADER RULE CATEGORY</h5>
                    <div class="row">
                         <table  class="table table-striped">
                            <thead>
                            <tr>
                              <th>Rule Tittle</th>
                              <th>Start Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
                             foreach ($header as $row): ?>
                                <tr>
                                <td><?php echo $row->RuleTitle ?></td>
                                <td><?php echo date("d-M-Y H:i:s", strtotime($row->StartDate)); ?></td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php } ?>
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
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Rule Title</label>
                                     <div class="col-sm-6">
                                      <select class="form-control select2" style="width: 100%;"  name="RuleTitle">

                                        <option  value="<?= isset($all_data->PDID) ? $all_data->PDID : '' ?>"><?= isset($all_data->RuleTitle) ? $all_data->RuleTitle : 'Select Rule Title'?></option>
                                        <?php foreach ($list as $list ) { ?>
                                        <option  value="<?= $list->DID ?>"><?= $list->RuleTitle ?></option>
                                        <?php } ?>
                                      </select>
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Rule Category</label>
                                     <div class="col-sm-10">
                                         <input type="text" name='RuleCategory' class="form-control"  value="<?= isset($all_data->RuleCategory) ? $all_data->RuleCategory : '' ?>" placeholder="Enter Rule Category" >
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Point Ratio</label>
                                     <div class="col-sm-10">
                                         <input type="text" name='PointRatio' class="form-control numeric2"  value="<?= isset($all_data->PointRatio) ? $all_data->PointRatio : '' ?>" placeholder="Enter Point Ratio" >
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Event Multiply</label>
                                     <div class="col-sm-10">
                                         <input type="number" name='EventMultiply' class="form-control"  value="<?= isset($all_data->EventMultiply) ? $all_data->EventMultiply : '' ?>" placeholder="Enter Event Multiply" >
                                     </div>
                                 </div>
                                 <!-- /.card-body -->
                                 <div class="card-footer">
                                     <div class="form-actions form-group">
                                         <button type="button" onclick="window.history.back();" class="btn btn-danger float-right">Cancel</a>
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
 <script src="<?= base_url('assets') ?>/jquery.number.js"></script>

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


    //number formatting.
    $('.numeric2').number(true,2);
    $('.numeric0').number(true);
    $("[decimals*='2']").number(true,2);
    $("[decimals*='0']").number(true);


    
     //ajax add
      $('#save').on('click', function(){
        <?php if ($status_edit == 0) : ?>
                var link = '<?php echo base_url('backend/RuleCategory/add2')?>';
        <?php else  : ?>
            var link = '<?php echo base_url()?>backend/RuleCategory/edit2/<?php echo $all_data->DIDdetail ?>';
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
                                window.history.back();
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