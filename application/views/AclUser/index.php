
<link rel="stylesheet" type="text/css" href="<?= base_url('assets') ?>/plugins/bootstrap-duallistbox/bootstrap-duallistbox.min.css">
<style type="text/css">
        .dual-list{
            box-shadow: 1px 1px 1px 2px #4444;
            padding-top: 10px;
        }

        .bg-muted{
            background-color: #EEEEEE;
        }
    </style>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?php echo $title; ?></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#"><?php echo $breadcrumb; ?></a></li>
                    <li class="breadcrumb-item active"><?php echo $breadcrumb1; ?></li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
        <!-- Main content -->
<section class="content">
    <?php echo notify_message($success_msg, $error_msg, $info_msg); ?>
    <div class="alert alert-danger notif-msg" style="display:none"></div>
    <div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-body">
                            <div class="d-flex">
                                <h4 class="card-title"><?php echo $data_tabel ?></h4>
                            </div>
                            <hr>
                            <div class="col-md-7 mb-4">
                                <select class="form-control" id="SelectGroup" name="SelectGroup">
                                    <option value="" selected disabled>Select ACL Group</option>
                                    <?php foreach ($all_data as $row) { ?>
                                         <option value="<?php echo $row->ParamID ?>"><?php echo $row->ParamID ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                                <div class="container">
                                    <br />
                                    <div class="row">

                                        <div class="dual-list list-left col-md-5" >
                                            <span class="font-medium">Master Data</span>
                                            <hr>
                                            <div class="well">
                                                <div class="input-group">
                                                    <input type="text" name="SearchDualList" class="form-control" placeholder="Search" />
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <div class="custom-control custom-checkbox mr-sm-2">
                                                                <input type="checkbox" class="custom-control-input selector" id="checkbox4" title="select all" value="check">
                                                                <label class="custom-control-label" for="checkbox4"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                 <!-- <ul class="list-group" id="NotSelected" style="margin-bottom: 10px; overflow:scroll; -webkit-overflow-scrolling: touch; max-height: 200px;">
                                                    <?php foreach ($user as $row) { ?>
                                                    <li class="list-group-item" value="<?php echo $row->EmpID  ?>"><?php echo $row->EmpID ?> | <?php echo $row->FullName ?> | <?php echo $row->Email ?></li>
                                                   <?php }  ?>
                                                </ul>  -->
                                                <ul class="list-group" id="NotSelected" style="margin-bottom: 10px; overflow:scroll; -webkit-overflow-scrolling: touch; max-height: 200px;">
                                                    <?php foreach ($user as $row) { ?>
                                                    <li class="list-group-item" value="<?php echo $row->DID  ?>"><?php echo $row->Email ?></li>
                                                   <?php }  ?>
                                                </ul> 
                                            </div>
                                        </div>

                                        <div class="list-arrows col-md-1 text-center" style="margin-top: 10%;">

                                            <button class="btn btn-primary btn-sm mt-2 move-right">
                                                <span class="fas fa-arrow-right"></span>
                                            </button>
                                            <button class="btn btn-danger btn-sm mt-2 move-left">
                                                <span class="fas fa-arrow-left"></span>
                                            </button>

                                        </div>

                                        <div class="dual-list list-right col-md-5" >
                                            <span class="font-medium" id="TitleSelected"></span>
                                            <hr>
                                            <div class="well">
                                                <div class="input-group">
                                                    <input type="text" name="SearchDualList" class="form-control" placeholder="Search" />
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <div class="custom-control custom-checkbox mr-sm-2">
                                                                <input type="checkbox" class="custom-control-input selector" id="checkbox1" title="select all" value="check">
                                                                <label class="custom-control-label" for="checkbox1"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <ul class="list-group" id="get_mhs_selected" style="margin-bottom: 10px; overflow:scroll; -webkit-overflow-scrolling: touch; max-height: 200px;">
                                                    <?php foreach ($college_selected as $user) { ?>
                                                    <li class="list-group-item" value="<?php echo $user->nim; ?>"><?php echo $user->nim; ?> | <?php echo $user->name; ?></li>
                                                    <?php } ?>
                                                </ul> -->
                                                <ul class="list-group" id="Selected" style="margin-bottom: 10px; overflow:scroll; -webkit-overflow-scrolling: touch; max-height: 200px;">
                                                    <!-- <?php foreach ($selected as $row) { ?>
                                                    <li class="list-group-item" value="<?php echo $row->DID ?>"><?php echo $row->Email ?></li>
                                                    <?php }?> -->
                                                </ul>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            <div class="d-flex align-items-center mt-3">
                                <div class="ml-auto ">
                                    <button type="button" id="BtnSubmit" class="btn btn-primary  "><i class="fa fa-save"></i> Save</button>
                                    <!-- <button type="button" onclick="location.reload();" id="btn-reset" class="btn btn-warning  "><i class="fa fa-sync-alt"></i> Reset</button> -->
                                </div>
                            </div>

                        </div>
                     </div>
                </div>
            </div>
       </div>
   </div>
</section>


<!-- dual list box -->
<script src="<?= base_url('assets') ?>/plugins/bootstrap-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/bootstrap-duallistbox/dual-listbox.js"></script>

<script type="text/javascript">

    $(function () {

            $('body').on('click', '.list-group .list-group-item', function () {
                $(this).toggleClass('active');
            });
            $('.list-arrows button').click(function () {
                var $button = $(this), actives = '';
                if ($button.hasClass('move-left')) {
                    actives = $('.list-right ul li.active');
                    actives.clone().appendTo('.list-left ul');
                    actives.remove();
                } else if ($button.hasClass('move-right')) {
                    actives = $('.list-left ul li.active');
                    actives.clone().appendTo('.list-right ul');
                    actives.remove();
                }
            });
            $('.dual-list .selector').click(function () {
                var $checkBox = $(this);
                if (!$checkBox.hasClass('selected')) {
                    $checkBox.addClass('selected').closest('.well').find('ul li:not(.active)').addClass('active');
                } else {
                    $checkBox.removeClass('selected').closest('.well').find('ul li.active').removeClass('active');
                }
            });
            $('[name="SearchDualList"]').keyup(function (e) {
                var code = e.keyCode || e.which;
                if (code == '9') return;
                if (code == '27') $(this).val(null);
                var $rows = $(this).closest('.dual-list').find('.list-group li');
                var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
                $rows.show().filter(function () {
                    var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                    return !~text.indexOf(val);
                }).hide();
            });

        });



    // SELECTED SUBMIT
     $('#BtnSubmit').on('click',function(){
            var Selected = $('#Selected li');
            var SelectGroup =  $("select[name=SelectGroup]").val();
            if(SelectGroup != null){
           var LogDID = [];
              $(Selected).each(function(){
                    LogDID.push($(this).val());
                   });
            var NotSelected = $('#NotSelected li');
            var DID = [];
                $(NotSelected).each(function(){
                    DID.push($(this).val());
                   });
                
                 $.ajax({
                url: "<?php echo base_url('backend/AclUser/SelectedProcess');?>",
                method: "POST",
                data :{LogDID:LogDID,DID:DID,SelectGroup:SelectGroup},
                success : function(data){
                 swal.fire({
                                title: "Success",
                                text: "Success",
                                type: "success"
                                }).then(function() {
                                    location.reload();
                            });
                },
                error : function(data){
                    alert('error');
                },
                
            });
            return false;
        }else{
            swal({
                                title: "Select User",
                                text: "Select ACL Group First",
                                type: "warning"
                                }).then(function() {
                                    location.reload();
                            });
        }
    });

              //get identity
    $("#SelectGroup").change(function(){
        var Group = $(this).val();
         $('#TitleSelected').html('Data For '+Group);
            
                $.ajax({
                url : "<?php echo base_url('backend/AclUser/GetAclUser');?>",
                method : "POST",
                data : {Group:Group},
                async : false,
                dataType : "JSON",
                success: function(data){
                    var html = '';
                    var i;
                    for(i=0; i<data.length; i++){
                        html += '<li class="list-group-item" value='+data[i].DID+'>'+data[i].Email+'</li>';
                    }
                    $('#Selected').html(html);

                }
            });
        });
    

</script>