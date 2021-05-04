<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/select2/css/select2.min.css">


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
  <section class="content">
    <!-- Alert -->
    <?php echo notify_message($success_msg, $error_msg, $info_msg); ?>
    <div class="alert alert-danger notif-msg" style="display:none"></div>

      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="callout callout-info">
              <h5>FILTER</h5>
                <div class="row">
                    <div class="col-sm-3">
                        <label>From Date</label>
                        <input class="form-control" name='fromdate' id="fromdate" type="date">
                    </div>
                    <div class="col-sm-3">
                        <label>To Date</label>
                        <input class="form-control" name='todate'id="todate" type="date">
                    </div>
                </div>
                <div class="row pt-3">
                    <div class="col-sm-6 ">
                       <div class="form-group">
                          <label>Member</label>
                          <select class="form-control select2" style="width: 100%;" id="MemberID">
                            <option selected="selected" disabled>Select Member</option>
                            <?php foreach ($memberlist as $row) { ?> 
                            <option value="<?php echo $row->MemberID; ?>"><?php echo $row->MemberID.' '. $row->FirstName.' '.$row->LastName; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                    </div>
                    <div class="col-sm-6 mt-4">
                        <button class="btn btn-primary" type="button" id="filter">Filter</button>
                    </div>
                </div>
            </div>


            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                   <?= $data_tabel ?>
                    <small class="float-right">
                        <form action="<?php echo base_url('backend/ReportPoint/Export')?>" Method="POST" enctype='multipart/form-data'>
                        <input type="hidden" name="fromdate_fill" class="fromdate_fill" id="fromdate_fill">
                        <input type="hidden" name="todate_fill" class="todate_fill" id="todate_fill">
                        <input type="hidden" name="member_fill" class="member_fill" id="member_fill">
                      <button type="submit" class="btn btn-primary float-right" style="margin-right: 5px;" >
                        <i class="fas fa-file-export"></i> Export Excel
                      </button>
                  </form>
                    </small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  From Date
                  <address>
                    <strong><span class="fromdate_fill"></span></strong><br>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  To Date
                  <address>
                    <strong><span class="todate_fill"></span></strong><br>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    Member
                  <address>
                    <strong><span class="member_fill"></span></strong>
                  </address>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- Table row -->
             <div class="row">
                <div class="col-12 table-responsive">
                 <div id="result"></div>
                </div>
              </div>
              <!-- /.row -->

            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>

<!-- baru -->

<script src="<?= base_url('assets') ?>/main.js"></script>
<script src="<?= base_url('assets') ?>/plugins/select2/js/select2.full.min.js"></script>
<script> 
  /*var urls = '<?= base_url();?>backend/TransLang/get_list';
  var add_urls = '<?= base_url();?>backend/TransLang/form/add';*/

    $('.select2').select2()


</script>


<script>
$(document).ready(function(){
        /**/
      $('#filter').click(function(){  
                    var fromdate = $('#fromdate').val();  
                    var todate = $('#todate').val();  
                    var MemberID = $('#MemberID').val(); 
                    if(fromdate != '' && todate != '')  
                    {  
                         $.ajax({  
                              url:"<?php echo base_url(); ?>backend/ReportPoint/filter",  
                              method:"POST",  
                              data:{fromdate:fromdate, todate:todate, MemberID:MemberID},  
                              success:function(data)  
                              {     
                                $('.fromdate_fill').val(fromdate);
                                $('.todate_fill').val(todate);
                                $('.member_fill').val(MemberID);
                                $('.fromdate_fill').html(fromdate);
                                $('.todate_fill').html(todate);
                                $('.member_fill').html(MemberID);
                                $('#result').html(data);  
                              }  
                         });  
                    }  
                    else  
                    {  
                          swal.fire({
                            title: "Warning",
                            text: "Please select the date",
                            type: "warning",
                            showConfirmButton: false,
                            confirmButtonText: false,
                            timer: 2000,
                        });
                    }  
               });

});

     /*EXPORT*/
      /*$('#export').on('click', function(){
        var fromdate = $('#fromdate_fill').val();  
        var todate = $('#todate_fill').val();  
        var MemberID = $('#member_fill').val(); 
        $.ajax({
            url :"<?php echo base_url('backend/ReportPoint/Export')?>",
            method:"POST",
            data:{fromdate:fromdate, todate:todate, MemberID:MemberID},
            success: function (data){
                 alert('fsefse');
            },
           
        });
    });
*/
</script>


