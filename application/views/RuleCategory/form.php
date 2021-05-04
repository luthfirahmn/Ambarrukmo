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
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Rule Title</label>
                                     <div class="col-sm-10">
                                         <input type="text" name='RuleTitle' class="form-control"  value="<?= isset($all_data->RuleTitle) ? $all_data->RuleTitle : '' ?>" placeholder="Enter Rule Title" >
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                     <label for="example-date-input" class="col-sm-2 col-form-label">Start Date</label>
                                     <div class="col-sm-10">
                                         <input class="form-control" name='StartDate' type="date" value="<?= isset($all_data->StartDate)? date_format(date_create($all_data->StartDate), "Y-m-d"):''?>" id="example-date-input" >
                                     </div>
                                 </div>
                                 <!-- /.card-body -->
                                 <div class="card-footer">
                                     <div class="form-actions form-group">
                                         <a href="<?= base_url("/backend/RuleCategory") ?>" class="btn btn-danger float-right">Cancel</a>
                                         <button type="button" style=" margin-right : 6px;" id="save"  name="save" value="savereturn" class="btn btn-primary float-right save"><i class="fa fa-check"></i> Save </button>
                                     </div>
                                 </div>
                                 <!-- /.card-footer -->
                             </form>
                 </div>
             </div>
             <!-- DETAIL -->
             <?php if ($status_edit == 1) { ?>
             <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Detail Rule Category</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>No</th>
                      <th>Rule Category</th>
                      <th>Point Ratio</th>
                      <th>Event Multiply</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1;
                    $urlDl =  base_url("backend/RuleCategory/delete");
                     foreach ($detail as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row->RuleCategory ?></td>
                            <td><?php echo number_format($row->PointRatio,2,",","."); ?></td>
                            <td><?php echo $row->EventMultiply ?></td>
                            <td><a class="btn btn-info btn-sm" href="<?php echo base_url('backend/RuleCategory/form_edit2/') ?><?php echo $row->DID ?>"><i class="fa fa-edit"></i></a>
                                <button class="btn btn-danger btn-sm" onclick="myDelete('<?php echo $row->DID ?>','<?php echo base_url("backend/RuleCategory/delete2") ?>')"><i class="fa fa-trash"></i></button></td>
                        </tr>

                    <?php endforeach ?>
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
          <!-- /.card -->
            </div>
            <!-- /.col -->
        <?php } ?>
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
 
<!-- DataTables -->
<script src="<?= base_url('assets') ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
 <script>

    $(function () {
        $("#example1").DataTable({
        "responsive": true,
        "autoWidth": false,
        });
    $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        });
    });

     //number formatting.
    $('.numeric2').number(true,2);
    $('.numeric0').number(true);
    $("[decimals*='2']").number(true,2);
    $("[decimals*='0']").number(true);


    


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

     //ajax add header
      $('#save').on('click', function(){
        <?php if ($status_edit == 0) : ?>
                var link = '<?php echo base_url('backend/RuleCategory/add')?>';
        <?php else  : ?>
            var link = '<?php echo base_url()?>backend/RuleCategory/edit/<?php echo $all_data->DID ?>';
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
                             window.location.href = "<?php echo base_url('backend/RuleCategory/index')?>";
                        });
                    }else{
                        $(".error-msg").css('display','block');
                        $(".error-msg").html(data.error);
                        window.scrollTo(0,0)
                    }
            },
           
        });
    }); 


    function myDelete(id, urls) {
    swal.fire({
            title: "Are you sure?",
            text: "Are you sure you want to delete this?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
            if (result.value) {
                $.ajax({
                    url: urls,
                    type: "POST",
                    data: {
                        DID: id,
                    },
                    success: function (result) {
                        reload_table();
                        swal({
                            title: "Success",
                            text: "success deleted..",
                            type: "success",
                            showConfirmButton: false,
                            confirmButtonText: false,
                            timer: 2000,
                        });
                    },
                    error: function (xhr, Status, err) {
                        $("Terjadi error : " + Status);
                    },
                });
            } else {
                return false;
            }
        });
}


      

 </script>