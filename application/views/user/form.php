 <!-- Content Header (Page header) -->
 <style>
    .notrequired {
          color:red;
        }

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

                                 <div class="form-group  row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label " >Email</label>
                                     <div class="col-sm-10">
                                         <input type="text" name='Email' class="form-control"  value="<?= isset($all_data->LogEmail) ? $all_data->LogEmail : '' ?>" placeholder="Enter Email" >
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label" >Password</label>
                                     <div class="col-sm-10">
                                         <input type="password" name='Password' class="form-control"  placeholder="Enter Password" >
                                         
                                        <?php if ($status_edit == 1) : ?>
                                         <div class="notrequired">Don't input the fields if wont change password</div>
                                        <?php endif;?>
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Confirm Password</label>
                                     <div class="col-sm-10">
                                         <input type="password" name='ConfirmPass' class="form-control"  placeholder="Confirm Password" >
                                         <?php if ($status_edit == 1) : ?>
                                         <div class="notrequired">Don't input the fields if wont change password</div>
                                        <?php endif;?>
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Employee</label>
                                     <div class="col-sm-4">
                                         <input type="hidden"  value="<?= isset($all_data->EmpID) ? $all_data->EmpID : '' ?>" name='EmpID' id="EmpID" class="form-control"  placeholder="Select Employee" readonly>
                                          <input type="text"  value="<?= isset($all_data->FullName) ? $all_data->FullName : '' ?>" name='FullName' id="FullName" class="form-control"  placeholder="Select Employee" readonly>
                                     </div>
                                     <div class="col-sm-4">
                                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-lg">
                                          Select Employee
                                        </button>
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
                                         <a href="<?= base_url("/backend/User") ?>" class="btn btn-danger float-right">Cancel</a>
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


<!-- MODAL -->
 <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Employee</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

            <div class="card">
            <div class="card-header">
              <h3 class="card-title">Table Employee</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>EmpID</th>
                  <th>Name</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $no=1; foreach ($emp as $row) { 
                 ?>
                <tr>
                  <td><?php echo $no++; ?></td>
                  <td><?php echo $row->EmpID; ?></td>
                  <td><?php echo $row->FullName; ?></td>
                  <td><button type="button" onclick="SelectEmp('<?php echo $row->EmpID; ?>','<?php echo $row->FullName; ?>')"  class="btn btn-success" >Select</button></td>
                </tr>
                <?php }
                 ?>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

<!--  <script src="<?= base_url('assets') ?>/plugins/jquery/jquery.min.js"></script>

 <script src="<?= base_url('assets') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

 <script src="<?= base_url('assets') ?>/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

 <script src="<?= base_url('assets') ?>/dist/js/adminlte.min.js"></script>

 <script src="<?= base_url('assets') ?>/dist/js/demo.js"></script> -->

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


    
     //ajax add
      $('#save').on('click', function(){
        <?php if ($status_edit == 0) : ?>
                var link = '<?php echo base_url('backend/User/add')?>';
        <?php else  : ?>
            var link = '<?php echo base_url()?>backend/User/edit/<?php echo $all_data->LogDID ?>';
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
                        window.location.href = "<?php echo base_url('backend/User/index')?>";
                    }else{
                        $(".error-msg").css('display','block');
                        $(".error-msg").html(data.error);
                        window.scrollTo(0,0)
                    }
            },
           
        });
    }); 

     /*Select*/
    function SelectEmp(EmpID,FullName){

            document.getElementById("EmpID").value = EmpID;
            document.getElementById("FullName").value = FullName;
            /*Modal Hide*/
            $('#modal-lg').modal('hide');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

         }

 </script>