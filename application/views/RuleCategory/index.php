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
             <div id="accordion" class="col-12">
                  <!-- we are adding the .class so bootstrap.js collapse plugin detects it -->

              <div class="card card-default">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                    <div class="card-header">
                      <h4 class="card-title">
                          <?php echo $data_tabel ?>
                      </h4>
                    </div>
                </a>
                <div id="collapseOne" class="panel-collapse collapse in show">
                  <div class="card-body">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                        <th>Nomor</th>
                                        <th>Rule Tittle</th>
                                        <th>Start Date</th>
                                        <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->

                  </div>
                </div>
              </div>
              <div class="card card-danger">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                <div class="card-header">
                  <h4 class="card-title">
                      <?php echo $data_tabel2 ?>
                  </h4>
                </div>
                </a>
                <div id="collapseTwo" class="panel-collapse collapse">
                  <div class="card-body">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nomor</th>
                                            <th>Rule Tittle</th>
                                            <th>Start Date</th>
                                            <th>Rule Category</th>
                                            <th>Point Ratio</th>
                                            <th>Event Multiply</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                  </div>
                </div>
              </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

<!-- baru -->

<script>
    var urls = '<?= base_url(); ?>backend/RuleCategory/get_list';
    var add_urls = '<?= base_url(); ?>backend/RuleCategory/form/add';
    var cekadd = <?= $bt_add?>;

    if(cekadd == 1){
        btnAdd = [{
                    text: "Add",
                    action: function(e, dt, node, config) {
                        location.href = add_urls;
                    },
                }, ]
    }else{
        btnAdd = []
    }

    $(document).ready(function () {
        $("#example1")
            .DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                serverSide: true,
                processing: true,
                // "order": [[ 3, "desc" ]],
                paging: true,
                columnDefs: [
                    { "width": "10px", "targets": 0 }
                  ],
                searching: { regex: true },
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"],
                ],
                pageLength: 10,
                dom:
                    "<'row'<'col-sm-6'B><'col-sm-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-4'i><'col-sm-4 text-center'l><'col-sm-4'p>>",
                buttons: btnAdd,
                ajax: {
                    url: urls,
                    type: "POST",
                },
            })
            .buttons()
            .container()
            .appendTo("#example1_wrapper .col-md-6:eq(0)");
    });


    function myActive(id, param, urls) {
        $.ajax({
            url: urls,
            type: "POST",
            data: {
                DID: id,
                active: param,
            },

            success: function (result) {
                var response = $.parseJSON(result);
                if (response) {
                    reload_table();
                    swal({
                        title: "Success",
                        text: "success updated..",
                        type: "success",
                        showConfirmButton: false,
                        confirmButtonText: false,
                        timer: 2000,
                    });
                } else {
                    swal({
                        title: "Error",
                        text: "error updated..",
                        type: "error",
                        showConfirmButton: false,
                        confirmButtonText: false,
                        timer: 2000,
                    });
                }
            },
            error: function (xhr, Status, err) {
                $("Terjadi error : " + Status);
            },
        });
    }

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




    function reload_table() {
        $("#example1").DataTable().ajax.reload(); //reload datatable ajax
    }



    var add_urls2 = '<?= base_url();?>backend/RuleCategory/form2/add2';
    var urls2 = '<?= base_url();?>backend/RuleCategory/get_list2';
    $( document ).ready(function()
    {    
        $("#example2").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "serverSide":true,
            "processing":true,
            "order": [],
            "paging" :true,
            "searching" : {"regex" :true},
            "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
            "pageLength": 10,
            "dom" : "<'row'<'col-sm-6'B><'col-sm-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-4'i><'col-sm-4 text-center'l><'col-sm-4'p>>",
            "buttons": [{
            text: 'Add',
                action: function(e, dt, node, config) {
                    location.href = add_urls2
                }
            }],
            "ajax" : {
                "url": urls2,
                "type" :'POST'
            },
            "columnDefs": [
                { 
                    "targets": [ -1 ], 
                    "orderable": false, 

                },
                ],
        }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');

    });

    function reload_table2() 
    {
        $('#example2').DataTable().ajax.reload(); //reload datatable ajax 
    }

    function myDelete2(id, urls2) {
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
                        url: urls2,
                        type: "POST",
                        data: {
                            DID: id,
                        },
                        success: function (result) {
                            reload_table2();
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


