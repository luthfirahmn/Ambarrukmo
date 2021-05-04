 <!-- Content Header (Page header) -->
 <style>
     .file {
         visibility: hidden;
         position: absolute;

     }

     .form-group.required .col-form-label:after {
         content: "*";
         color: red;
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

                         <form class="form-horizontal " method="post" enctype="multipart/form-data" id="formadd">

                         <?php else : ?>
                             <form class="form-horizontal" method="post" enctype="multipart/form-data" id="formadd">
                             <?php endif; ?>
                             <div class="card-body">

                                 <!-- SELECT NID TYPE -->
                                 <div class="form-group required row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">NID Type</label>
                                     <div class="col-sm-10">
                                         <select class="form-control" name="NIDType">
                                             <option value="<?= isset($all_data->NIDType) ? $all_data->NIDType : '' ?>"><?= isset($all_data->NIDType) ? $all_data->NIDType : 'Select NID Type' ?></option>
                                             <?php foreach ($nid as $row) : ?>
                                                 <option value="<?php echo $row->ParamID ?>"><?php echo $row->ParamValue ?></option>
                                             <?php endforeach; ?>
                                         </select>
                                     </div>
                                 </div>

                                 <div class="form-group required row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">NID No</label>
                                     <div class="col-sm-10">
                                         <input type="text" name='NIDNo' class="form-control" value="<?= isset($all_data->NIDNo) ? $all_data->NIDNo : '' ?>" placeholder="Enter NID No">
                                     </div>
                                 </div>

                                 <div class="form-group required row ">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">NID Photo</label>
                                     <div class="col-sm-10">
                                         <div class="ml-2 col-sm-6">
                                             <div id="msg"></div>
                                             <form method="post" id="image-form">
                                                 <input type="file" name="IDPhoto" class="file" accept="image/*">
                                                 <div class="input-group my-3">
                                                     <input type="email" class="form-control" disabled placeholder="Upload File" id="file">
                                                     <div class="input-group-append">
                                                         <button type="button" class="browse btn btn-primary">Browse...</button>
                                                     </div>
                                                 </div>
                                             </form>
                                         </div>

                                         <div class="ml-2 col-sm-6">
                                             <?php if (isset($all_data->IDPhoto) == "") : ?>
                                                 <img src="<?= base_url() ?>/assets/uploads/200x100.png" id="preview" class="img-thumbnail">
                                             <?php else : ?>
                                                 <img src="<?= base_url() ?>/upload/MEMBER/<?= $all_data->IDPhoto ?>" id="preview" class="img-thumbnail" width="200" height="100">
                                             <?php endif; ?>
                                         </div>
                                     </div>
                                 </div>

                                 <!-- SELECT MEMBER LEVEL -->
                                 <div class="form-group required row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Member Level</label>
                                     <div class="col-sm-10">
                                         <?php if (isset($all_data->MemberLevel) == '') {
                                                $selectreadonly = '';
                                            } else {
                                                $selectreadonly = 'disabled=""';
                                            } ?>
                                           
                                         <select class="form-control" name="MemberLevel" <?php echo $selectreadonly ?>>
                                             <?php if (isset($all_data->MemberLevel) == '') : ?>
                                                 <option value="">Select Member Level</option>
                                                 <?php foreach ($level as $row) : ?>
                                                     <option value="<?php echo $row->ParamID ?>"><?php echo $row->ParamValue ?></option>
                                                 <?php endforeach; ?>
                                             <?php else : ?>
                                                 <?php foreach ($level as $row) : ?>
                                                     <option value="<?php echo $row->ParamID ?>" <?php echo $all_data->MemberLevel == $row->ParamValue ? "selected" : "" ?>><?php echo $row->ParamValue ?></option>
                                                 <?php endforeach; ?>
                                             <?php endif; ?>
                                         </select>
                                     </div>
                                 </div>

                                 <div class="form-group required row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Country Prefix</label>
                                     <div class="col-sm-2">
                                         <input type="text" name='CountryPrefixNo' class="form-control" value="<?= isset($all_data->CountryPrefixNo) ? $all_data->CountryPrefixNo : '' ?>" placeholder="Example : +62">
                                     </div>
                                 </div>

                                 <div class="form-group required row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Mobile Phone</label>
                                     <div class="col-sm-10">
                                         <input type="number" name='MobilePhoneNo' class="form-control" value="<?= isset($all_data->MobilePhoneNo) ? $all_data->MobilePhoneNo : '' ?>" placeholder="Enter Mobile Phone">
                                     </div>
                                 </div>

                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Phone No</label>
                                     <div class="col-sm-10">
                                         <input type="number" name='PhoneNo' class="form-control" value="<?= isset($all_data->PhoneNo) ? $all_data->PhoneNo : '' ?>" placeholder="Enter Phone No">
                                     </div>
                                 </div>

                                 <div class="form-group required row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                                     <div class="col-sm-10">
                                         <input type="email" name='Email' class="form-control" value="<?= isset($all_data->Email) ? $all_data->Email : '' ?>" placeholder="Enter Email">
                                     </div>
                                 </div>

                                 <div class="form-group required row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Password</label>
                                     <div class="col-sm-10">
                                         <input type="password" name='Password' class="form-control" value="" placeholder="Enter Password">
                                         <?php if ($status_edit == 1) : ?>
                                         <span class="text-danger">*Dont input password if you wont change the password</span>
                                     <?php endif; ?>
                                     </div>
                                 </div>

                                 <div class="form-group required row">
                                     <label class="col-sm-2 col-form-label">Join Date</label>
                                     <div class="col-sm-10">
                                        <input type="date" name='JoinDate' class="form-control" value="<?php echo isset($all_data->JoinDate) ? date_format(date_create($all_data->JoinDate), "Y-m-d") : date("Y-m-d")?>" placeholder="Enter Join Date">
                                     </div>
                                 </div>

                                 <div class="form-group required row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">First Name</label>
                                     <div class="col-sm-10">
                                         <input type="text" name='FirstName' class="form-control" value="<?= isset($all_data->FirstName) ? $all_data->FirstName : '' ?>" placeholder="Enter First Name">
                                     </div>
                                 </div>

                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Last Name</label>
                                     <div class="col-sm-10">
                                         <input type="text" name='LastName' class="form-control" value="<?= isset($all_data->LastName) ? $all_data->LastName : '' ?>" placeholder="Enter Last Name">
                                     </div>
                                 </div>

                                 <!-- SELECT GENDER -->
                                 <div class="form-group required row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Gender</label>
                                     <div class="col-sm-10">
                                         <select class="form-control" name="Gender">
                                             <option value="<?= isset($all_data->Gender) ? $all_data->Gender : '' ?>"><?= isset($all_data->Gender) ? $all_data->Gender : 'Select Gender' ?></option>
                                             <?php foreach ($gender as $row) : ?>
                                                 <option value="<?php echo $row->ParamID ?>"><?php echo $row->ParamValue ?></option>
                                             <?php endforeach; ?>
                                         </select>
                                     </div>
                                 </div>

                                 <div class="form-group required row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Birth Place</label>
                                     <div class="col-sm-10">
                                         <input type="text" name='BirthPlace' class="form-control" value="<?= isset($all_data->Address1) ? $all_data->Address1 : '' ?>" placeholder="Enter Birth Place">
                                     </div>
                                 </div>

                                 <div class="form-group required row">
                                     <label class="col-sm-2 col-form-label">Birth Date</label>
                                     <div class="col-sm-10">
                                         <input type="date" name='BirthDate' class="form-control" value="<?php echo isset($all_data->BirthDate) ? date_format(date_create($all_data->BirthDate), "Y-m-d") : ""?>" placeholder="Enter Birth Date">
                                     </div>
                                 </div>

                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Address 1</label>
                                     <div class="col-sm-10">
                                         <input type="text" name='Address1' class="form-control" value="<?= isset($all_data->Address1) ? $all_data->Address1 : '' ?>" placeholder="Enter Address 1">
                                     </div>
                                 </div>

                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Address 2</label>
                                     <div class="col-sm-10">
                                         <input type="text" name='Address2' class="form-control" value="<?= isset($all_data->Address2) ? $all_data->Address2 : '' ?>" placeholder="Enter Address 2">
                                     </div>
                                 </div>

                                 <!-- SELECT RELIGION -->
                                 <div class="form-group  row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Religion</label>
                                     <div class="col-sm-10">
                                         <select class="form-control" name="ReligionID">
                                             <option value="<?= isset($all_data->ReligionID) ? $all_data->ReligionID : '' ?>"><?= isset($all_data->ReligionID) ? $all_data->ReligionID : 'Select Religion' ?></option>
                                             <?php foreach ($religion as $row) : ?>
                                                 <option value="<?php echo $row->ParamID ?>"><?php echo $row->ParamValue ?></option>
                                             <?php endforeach; ?>
                                         </select>
                                     </div>
                                 </div>

                                  <!-- SELECT WORKFIELD -->
                                 <div class="form-group  row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Work Field</label>
                                     <div class="col-sm-10">
                                         <select class="form-control" name="WorkFieldID">
                                             <option value="<?= isset($all_data->WorkFieldID) ? $all_data->WorkFieldID : '' ?>"><?= isset($all_data->WorkFieldID) ? $all_data->WorkFieldID : 'Select Work Field' ?></option>
                                             <?php foreach ($workfield as $row) : ?>
                                                 <option value="<?php echo $row->ParamID ?>"><?php echo $row->ParamValue ?></option>
                                             <?php endforeach; ?>
                                         </select>
                                     </div>
                                 </div>

                                 <!-- SELECT WORKFIELD -->
                                 <div class="form-group  row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Province</label>
                                     <div class="col-sm-10">
                                         <select class="form-control" name="Province" id="Province">
                                            <option value= "<?= isset($all_data->ProvinceID) ? $all_data->ProvinceID : '' ?>" selected>
                                            <?= isset($all_data->ProvinceID) ? $all_data->ProvinceID : 'Select Province' ?>
                                            </option>
                                            <?php 
                                            foreach ($province as $row) 
                                            {
                                                echo "<option value='$row[DID]'>$row[Province]</option>";
                                            }
                                            ?>
                                         </select>
                                     </div>
                                 </div>

                                 <!-- SELECT WORKFIELD -->
                                 <div class="form-group  row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">State</label>
                                     <div class="col-sm-10">
                                         <select class="form-control" name="State" id="State">
                                            <option value= "<?= isset($all_data->StateID) ? $all_data->StateID : '' ?>" selected>
                                            <?= isset($all_data->StateID) ? $all_data->StateID : 'Select State' ?>
                                            </option>
                                         </select>
                                     </div>
                                 </div>


                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">District/Kelurahan</label>
                                     <div class="col-sm-10">
                                         <input type="text" name='District' class="form-control" value="<?= isset($all_data->District) ? $all_data->District : '' ?>" placeholder="District/Kelurahan">
                                     </div>
                                 </div>

                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">SubDistrict/Kecamatan</label>
                                     <div class="col-sm-10">
                                         <input type="text" name='SubDistrict' class="form-control" value="<?= isset($all_data->SubDistrict) ? $all_data->SubDistrict : '' ?>" placeholder="SubDistrict/Kecamatan">
                                     </div>
                                 </div>

                                <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-2 col-form-label">Zip Code</label>
                                     <div class="col-sm-10">
                                         <input type="text" name='ZipCode' class="form-control" value="<?= isset($all_data->ZipCode) ? $all_data->ZipCode : '' ?>" placeholder="Zip Code">
                                     </div>
                                 </div>


                                 <div class="form-group required row">
                                     <label for="inputPassword3" class="col-sm-2 col-form-label">Active</label>
                                     <div class="col-sm-10">
                                         <?php foreach ($active as $keys => $val) : ?>
                                             <?php if (isset($all_data->Active) ? true : false) : ?>
                                                 <div class="form-check form-check-inline">
                                                     <input class="form-check-input" type="radio" name="Active" id="inlineRadio1" value="<?php echo $val->ParamID ?>" <?php echo $all_data->Active == $val->ParamID ? "checked" : "" ?>>
                                                     <label class="form-check-label" for="inlineRadio1"><?php echo $val->ParamValue ?></label>
                                                 </div>
                                             <?php else : ?>
                                                 <div class="form-check form-check-inline">
                                                     <input class="form-check-input" type="radio" name="Active" id="inlineRadio1" value="<?php echo $val->ParamID ?>">
                                                     <label class="form-check-label" for="inlineRadio1"><?php echo $val->ParamValue ?></label>
                                                 </div>
                                             <?php endif; ?>
                                         <?php endforeach; ?>
                                     </div>
                                 </div>

                                 <!-- /.card-body -->
                                 <div class="card-footer">
                                     <div class="form-actions form-group">
                                         <a href="<?= base_url("/backend/member") ?>" class="btn btn-danger float-right">Cancel</a>
                                         <button type="button" style=" margin-right : 6px;" id="save" name="save" value="savereturn" class="btn btn-primary float-right save"><i class="fa fa-check"></i> Save </button>
                                     </div>
                                 </div>
                                 <!-- /.card-footer -->
                             </form>
                 </div>
             </div>
         </div>
     </div>
 </section>
 <?php 
 /*
 <script src="<?= base_url('assets') ?>/plugins/jquery/jquery.min.js"></script>
 <script src="<?= base_url('assets') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
 <script src="<?= base_url('assets') ?>/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
 <script src="<?= base_url('assets') ?>/dist/js/adminlte.min.js"></script>
 <script src="<?= base_url('assets') ?>/dist/js/demo.js"></script>
 */ ?>

 <script>
     $(document).ready(function() {
         //min date
         var today = new Date();
         var dd = today.getDate();
         var mm = today.getMonth() + 1; //January is 0!
         var yyyy = today.getFullYear();
         if (dd < 10) {
             dd = '0' + dd
         }
         if (mm < 10) {
             mm = '0' + mm
         }
         today = yyyy + '-' + mm + '-' + dd;
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


     //SELECT STATE

    $(function(){

        $.ajaxSetup({
        type:"POST",
        url: "<?php echo base_url('backend/Member/selectState') ?>",
        cache: false,
        });

        $("#Province").change(function(){

        var value=$(this).val();
        if(value>0){
        $.ajax({
        data:{id:value},
        success: function(respond){
        $("#State").html(respond);
        }
        })
        }

        });

    })

     //ajax add
     $('#save').on('click', function() {
         <?php if ($status_edit == 0) : ?>
             var link = '<?php echo base_url('backend/Member/add') ?>';
         <?php else : ?>
             var link = '<?php echo base_url() ?>backend/Member/edit/<?php echo $all_data->DID ?>';
             var form =
             <?php endif; ?>
             $.ajax({
                 type: "post",
                 url: link,
                 data: new FormData($('#formadd')[0]),
                 processData: false,
                 contentType: false,
                 cache: false,
                 dataType: "json",
                 beforeSend: function() {
                     $('.save').attr('disable', 'disabled');
                     $('.save').html('<i class="fa fa-spin fa-spinner"></i>');
                 },
                 complete: function() {
                     $('.save').removeAttr('disable');
                     $('.save').html('<i class="ti-save"> </i> Save');
                 },
                 success: function(data) {
                     if ($.isEmptyObject(data.error)) {
                          swal.fire({
                                title: "Success",
                                text: "Success",
                                type: "success"
                                }).then(function() {
                                 window.location.href = "<?php echo base_url('backend/Member/index')?>";
                            });
                     } else {
                         $(".error-msg").css('display', 'block');
                         $(".error-msg").html(data.error);
                         window.scrollTo(0, 0)
                     }
                 },

             });
     });
 </script>