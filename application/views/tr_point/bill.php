<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        border: 1px solid #ced4da;
        font-weight: 400;
        padding: .375rem .75rem;
        height: calc(2.25rem + 2px);
    }

    .file {
        visibility: hidden;
        position: absolute;
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
                    <li class="breadcrumb-item"><a href="<?= base_url('/backend/dashboard')?>"><?php echo $breadcrumb; ?></a></li>
                    <li class="breadcrumb-item active"><?php echo $breadcrumb1; ?></li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title"><?= $form_title ?></h3>
                    </div>
                    <form enctype="multipart/form-data" id="myform">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="inputEmail4">Member</label>
                                    <select class="form-control js-example-basic-single" name="member" id="member" onchange="get_data_member()">
                                        <option value="0">Choose..</option>
                                        <?php foreach ($member as $val) : ?>
                                            <option value="<?= $val->MemberID ?>"><?php echo $val->MemberID . " / " . $val->Email ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="inputPassword4">Transaksi Amount</label>
                                    <input type="text" class="form-control" id="trx_amount" name="trxamount" placeholder="Transaksi Amount">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="inputPassword4">Bill</label>
                                    <input type="hidden" id="vl_bill" name="titlebill" value="">
                                    <input type="text" class="form-control" id="nm_bill" onfocus="get_data_bill()" value="" placeholder="Rule Bill">
                                </div>
                                <?php /* 
                                <div class="form-group col-md-4">
                                    <label for="inputPassword4">Rule Bill</label>
                                    <select class="form-control js-example-basic-single title_bill" name="titlebill" id="title_bill">
                                        <option value="0">Choose..</option>
                                        <?php foreach ($rule_bill as $val) : ?>
                                            <option value="<?= $val->DID . "," . $val->PointRatio . "," . $val->EventMultiply ?>"><?php echo $val->RuleTitle . " - " . $val->StartDate ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                */ ?>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="inputEmail4">Transaksi Note</label>
                                    <textarea class="form-control" name="trxnote" id="note" rows="3"></textarea>
                                </div>
                                <div class="form-group col-md-8">
                                    <label for="inputEmail4">Transaksi Photo</label>
                                    <div class="ml-2 col-sm-8">
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <img src="<?= base_url() ?>/assets/uploads/200x100.png" id="preview" class="img-thumbnail" style="width: 200px; height : 100px;">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <div id="msg"></div>
                                                <input type="file" name="VoucherIMG" class="file" accept="image/*">
                                                <div class="input-group my-3">
                                                    <input type="text" class="form-control" disabled placeholder="Upload File" id="file">
                                                    <div class="input-group-append">
                                                        <button type="button" class="browse btn btn-primary">Browse...</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="buttons"></div>
                            <?php if($bt_add == 1):?>
                                <button type="sumbit" style=" margin-right : 6px;" class="btn btn-primary btn-sm float-right"><i class="fa fa-check"></i> Submit </button>
                            <?php endif;?>
                            <!-- <button type="button" onclick="submitpoin()" style=" margin-right : 6px;" class="btn btn-primary btn-sm float-right"><i class="fa fa-check"></i> Submit </button> -->
                    </form>
                </div>
            </div>

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
                                    <th>Member Id</th>
                                    <th>Transaksi Amount</th>
                                    <th>Point</th>
                                    <th>Photo</th>
                                    <th>Transaksi Note</th>
                                    <th>Transaksi Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="tr_point">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Choose Bill</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Tabel Bill</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-bill">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Rule Title</th>
                                        <th>Point Ratio</th>
                                        <th>Event Multiply</th>
                                        <!-- <th>Start Date</th> -->
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tbody class="tr_bill">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
</section>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="<?= base_url('assets') ?>/jquery.number.js"></script>
<script>
    $(document).ready(function() {
        var urls = '<?= base_url(); ?>backend/bill/get_list';
        var add_urls = '<?= base_url(); ?>backend/bill/form/add';
        var urls_msg = '<?= base_url(); ?>backend/bill/alert';
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "serverSide": true,
            "processing": true,
            "paging": true,
            "columnDefs": [{
                "width": "10px",
                "targets": 0
            }],
            "searching": {
                "regex": true
            },
            "lengthMenu": [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            "pageLength": 10,
            "dom": "<'row'<'col-sm-6'B><'col-sm-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-4'i><'col-sm-4 text-center'l><'col-sm-4'p>>",
            "buttons": [],
            "ajax": {
                "url": urls,
                "type": 'POST'
            }
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('.js-example-basic-single').select2();
        $("#trx_amount").number(true, 2);
        td_right();
    });

    function get_data_bill() {
        $("#modal-lg").modal("show");
        $("#example2").dataTable().fnDestroy();
        $("#example2").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "serverSide": true,
            "processing": true,
            "paging": true,
            "columnDefs": [{
                "width": "10px",
                "targets": 3
            }],
            "searching": {
                "regex": true
            },
            "lengthMenu": [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            "pageLength": 5,
            "ajax": {
                "url": '<?= base_url("backend/bill/get_bill") ?>',
                "type": 'POST'
            }
        });
    }


    function setbill(param) {
        var res = param.value.split("-");
        $("#nm_bill").val(res[0]);
        $("#vl_bill").val(res[1]);
        $("#modal-lg").modal("hide");

    }


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

    $("#myform").submit(function(e) {
        e.preventDefault();
        var member = $("#member").val();
        var trx_amount = $("#trx_amount").val();
        var nm_bill = $("#nm_bill").val();
        var vl_bill = $("#vl_bill").val();
        if (member == 0 || vl_bill == "" || trx_amount == "" || nm_bill == "") {
            swal.fire(
                'Error',
                'Please complete the data first ..',
                'error'
            )
            return false
        }
        $.ajax({
            url: '<?= base_url("backend/bill/add_point") ?>',
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            beforeSend: function() {
                swal.fire({
                    title: 'Loading',
                    html: 'Redirecting Data',
                    onOpen: () => {
                        swal.showLoading()
                    },
                })
            },
            dataType: "json",
            success: function(response) {
                swal.close();
                if (response.status) {
                    reload_table();
                    td_right();
                    swal({
                        title: 'Success..',
                        text: response.message,
                        type: 'success',
                        showConfirmButton: false,
                        confirmButtonText: false,
                        timer: 2000
                    })
                } else {
                    swal({
                        title: 'Error..',
                        text: response.message,
                        type: 'error',
                        showConfirmButton: false,
                        confirmButtonText: false,
                        timer: 2000
                    })
                }
            }
        })

        return false
    })

    function get_data_member() {
        var memberId = $("#member").val();
        if (memberId == 0) {
            $("#example1").dataTable().fnDestroy();
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "serverSide": true,
                "processing": true,
                "paging": true,
                "columnDefs": [{
                    "width": "10px",
                    "targets": 0
                }],
                "searching": {
                    "regex": true
                },
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                "pageLength": 10,
                "dom": "<'row'<'col-sm-6'B><'col-sm-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-4'i><'col-sm-4 text-center'l><'col-sm-4'p>>",
                "buttons": [],
                "ajax": {
                    "url": "<?= base_url(); ?>backend/bill/get_list",
                    "type": 'POST',
                    "data": {
                        "memberid": ""
                    }
                }
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            reload_table();

        } else {

            $("#example1").dataTable().fnDestroy();
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "serverSide": true,
                "processing": true,
                "paging": true,
                "columnDefs": [{
                    "width": "10px",
                    "targets": 0
                }],
                "searching": {
                    "regex": true
                },
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                "pageLength": 10,
                "dom": "<'row'<'col-sm-6'B><'col-sm-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-4'i><'col-sm-4 text-center'l><'col-sm-4'p>>",
                "buttons": [],
                "ajax": {
                    "url": "<?= base_url(); ?>backend/bill/get_list",
                    "type": 'POST',
                    "data": {
                        "memberid": memberId
                    }
                }
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            reload_table();

        }
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
                        "DID": id,
                    },
                    dataType: "json",
                    success: function(result) {
                        if (result.status) {
                            reload_table();
                            td_right();
                            swal({
                                title: "Success",
                                text: "success deleted..",
                                type: "success",
                                showConfirmButton: false,
                                confirmButtonText: false,
                                timer: 2000,
                            });

                        } else {
                            swal({
                                title: 'Error..',
                                text: "not deleted..",
                                type: 'error',
                                showConfirmButton: false,
                                confirmButtonText: false,
                                timer: 2000
                            })
                        }
                    },
                    error: function(xhr, Status, err) {
                        $("Terjadi error : " + Status);
                    }
                });
            } else {
                return false;
            }
        })
    }

    function reload_table() {
        $("#example1").DataTable().ajax.reload(); //reload datatable ajax
    }

    function td_right() {
        setTimeout(function() {
            $(".tr_point > tr >  td:nth-child(3)").css({
                "text-align": "right"
            });
            $(".tr_point > tr >  td:nth-child(4)").css({
                "text-align": "right"
            });
        }, 500);
    }


    function infophoto(objButton) {
        Swal.fire({
            imageUrl: objButton.value,
            imageHeight: 150,
            imageWidth: 300,
            imageAlt: 'A tall image'
        })

    }
</script>