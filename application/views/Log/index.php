<!-- Content Header (Page header) -->
<style>

    button.btn-secondary {
        display: none;
    }
</style>

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
                    <div class="card-header">
                        <h3 class="card-title"><?php echo $data_tabel ?></h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="pull-left">

                        </div>
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nomor</th>
                                    <th>LogQuery</th>
                                    <th>LogPage</th>
                                    <th>RBU</th>
                                    <th>RBT</th>
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
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

<!-- baru -->

<script> 
  var urls = '<?= base_url();?>backend/log/get_list';

$( document ).ready(function()
{    
    $("#example1").DataTable({
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
                location.href = add_urls
            }
        }],
        "ajax" : {
            "url": urls,
            "type" :'POST'
        },
        "columnDefs": [
            { 
                "targets": [ -1 ], 
                "orderable": false, 

            },
            ],
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

});

function reload_table() {
    $("#example1").DataTable().ajax.reload(); //reload datatable ajax
}

</script>
