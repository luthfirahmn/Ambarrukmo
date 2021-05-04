<!-- Content Header (Page header) -->
<style>
    td.details-control {
        background: url('<?= base_url('assets') ?>/img/plus.jpeg') no-repeat center center;
        cursor: pointer;
    }

    tr.shown td.details-control {
        background: url('<?= base_url('assets') ?>/img/minus.jpeg') no-repeat center center;
    }

    table.detail td {
        border: none;
    }

    table.detail {
        width: 100%;
        border-collapse: collapse;
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
                    <li class="breadcrumb-item"><a href="<?= base_url('/backend/dashboard') ?>"><?php echo $breadcrumb; ?></a></li>
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
                                    <th></th>
                                    <th>Member ID</th>
                                    <!-- <th>KTP</th> -->
                                    <!-- <th>Member Level</th> -->
                                    <th>Mobile Phone</th>
                                    <th>Email</th>
                                    <!-- <th>Join Date</th>
                                    <th>Last Login</th>
                                    <th>OTP</th>
                                    <th>OTP Expired</th> -->
                                    <th>Total Point</th>
                                    <!-- <th>Full Name</th>
                                    <th>Gender</th>
                                    <th>Address 1</th>
                                    <th>Address 2</th> -->
                                    <th>Active</th>
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
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

<!-- baru -->

<script>
    var urls = '<?= base_url(); ?>backend/member/get_list';
    var urldetail = '<?= base_url(); ?>backend/member/detail';
    var add_urls = '<?= base_url(); ?>backend/member/form/add';
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

    $(document).ready(function() {
        table = $("#example1")
            .DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                serverSide: true,
                processing: true,
                // "order": [[ 3, "desc" ]],
                paging: true,
                columnDefs: [{
                        "class": "details-control",
                        "orderable": false,
                        "data": null,
                        "defaultContent": "",
                        "targets": [1],
                    },
                    {
                        "width": "10px",
                        "targets": 0
                    }
                ],
                searching: {
                    regex: true
                },
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"],
                ],
                pageLength: 10,
                dom: "<'row'<'col-sm-6'B><'col-sm-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-4'i><'col-sm-4 text-center'l><'col-sm-4'p>>",
                buttons: btnAdd,
                ajax: {
                    url: urls,
                    type: "POST",
                },
            })

        var detailRows = [];

        $('#example1 tbody').on('click', 'tr td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = table.row(tr);
            var idx = $.inArray(tr.attr('id'), detailRows);

            if (row.child.isShown()) {
                tr.removeClass('shown');
                row.child.hide();
            } else {
                tr.addClass('shown');
                row.child(format(row.data())).show();
            }
        });
    });

    function format(d) {
        var div = $('<div/>')
            .addClass('loading')
            .text('Loading...');

        $.ajax({
            url: urldetail,
            type: "POST",
            dataType: "json",
            data: {
                did: d[1]
            },
            success: function(response) {
                div
                    .html(response.data)
                    .removeClass('loading');
            }
        })
        return div;
    }

    function myActive(id, param, urls) {
        $.ajax({
            url: urls,
            type: "POST",
            data: {
                DID: id,
                active: param,
            },

            success: function(result) {
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
            error: function(xhr, Status, err) {
                $("Terjadi error : " + Status);
            },
        });
    }

    function myDelete(id, urls) {

        swal.fire({
            title: 'Are you sure?',
            text: "Are you sure you want to delete this?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: urls,
                    type: "POST",
                    data: {
                        memberid: id,
                    },
                    success: function(result) {
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
                    error: function(xhr, Status, err) {
                        $("Terjadi error : " + Status);
                    },
                });

            } else {
                return false;
            }
        })
    }

    function reload_table() {
        $("#example1").DataTable().ajax.reload(); //reload datatable ajax
    }
</script>