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
      <!-- Conten Table -->
      <div class="col-6">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title"><?php echo $data_tabel ?></h3>
          </div>
          <div class="card-body">
            <div class="pull-left">
            </div>
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Nomor</th>
                  <th>Name</th>
                  <th>ACL Group</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- Conten table -->


      <!-- Conten Form -->
      <div class="col-6">
        <div class="card card-secondary">
          <div class="card-header">
            <h3 class="card-title"><?= $data_tabel ?></h3>
          </div>

          <div style="width: 95%; margin-top: 3px; margin-left: 17px;" class="message"></div>

          <form class="form-horizontal" method="post" id="FormData">
            <div class="card-body">
              <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                  <input type="hidden" name='DID' id="did" value="">
                  <input type="text" name='ParamID' class="form-control" id="par" value="" placeholder="Enter Param" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="inputPassword3" class="col-sm-2 col-form-label">ACL Group</label>
                <div class="col-sm-10">
                  <input type="text" name='ParamValue' class="form-control" id="parVal" value="" placeholder="Enter Param Value">
                </div>
              </div>
              <div class="buttons"></div>
              <?php if($bt_add == 1):?>
              <button type="button" onclick="acl_save()" style=" margin-right : 6px;" name="save" value="savereturn" class="btn btn-primary float-right"><i class="fa fa-check"></i> Save </button>
              <?php endif;?>
          </form>
        </div>
      </div>
      <!-- Conten Form -->

      <?php //echo get_menu(1);
      ?>

      <div class="card card-secondary">
        <div class="card-header">
          <h3 class="card-title">
            Menu Form Update
          </h3>
        </div>
        <div style="width: 95%; margin-top: 3px; margin-left: 17px;" class="message2"></div>
        <!-- <form class="form-horizontal" action=" <?= base_url("backend/aclGroup/set_access") ?>" method="post" id="formaccess"> -->
        <form class="form-horizontal" method="post" id="formaccess">
          <div class="card-body">
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-2 col-form-label">ACL Group</label>
              <div class="col-sm-10">

                <div class="selected"></div>

              </div>
            </div>

            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-2 col-form-label"> Auto</label>
              <div class="col-sm-10">
                <label class="checkbox"><input type="radio" id="check_all" name="check_all" value="" /> Check All</label>&nbsp; &nbsp; &nbsp;
                <label class="checkbox"><input type="radio" id="uncheck_all" name="check_all" value="" /> Uncheck All</label>
              </div>
            </div>

            <div class="access"></div>

          </div>
        </form>
      </div>

    </div>

  </div>
  </div>
</section>
<!-- /.content -->

<!-- baru -->

<script>
  var urls = '<?= base_url(); ?>backend/aclGroup/get_list';
  var add_urls = '<?= base_url(); ?>backend/aclGroup/form/add';
  var urls_msg = '<?= base_url(); ?>backend/aclGroup/alert';
</script>

<!-- baruuu... -->
<script>
  $(document).ready(function() {

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
        ajax: {
          url: urls,
          type: "POST",
        },
      })
      .buttons()
      .container()
      .appendTo("#example1_wrapper .col-md-6:eq(0)");
    $("#par").val("")
    $("#parVal").val("")
    $(".flex-wrap").hide();
    getdataselect();

  })

  function acl_save() {
    var param = $("#par").val();
    var param_value = $("#parVal").val();
    var urls = '<?= base_url("backend/aclGroup/add") ?>';

    if (param == "" || param_value == "") {
      swal({
        title: 'Error..',
        text: 'Please complete the data first ..',
        type: 'error',
        showConfirmButton: false,
        confirmButtonText: false,
        timer: 2000
      })
      return false;
    }
    $.ajax({
      url: urls,
      type: "POST",
      data: {
        ParamID: param,
        ParamValue: param_value
      },
      dataType: "json",
      beforeSend: function() {
        swal.fire({
          title: 'Loading',
          html: 'Redirecting Data',
          onOpen: () => {
            swal.showLoading()
          }
        })
      },
      success: function(response) {
        swal.close();
        if (response.status) {
          getdataselect();
          $("input:text").val("");
          $('#example1').DataTable().ajax.reload();
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
  }


  function formedit(id) {
    var urle = '<?= base_url("backend/aclGroup/form_edit") ?>'

    $.ajax({
      url: urle,
      type: "POST",
      data: {
        ParamID: id,
      },
      dataType: "json",
      beforeSend: function() {
        swal.fire({
          title: 'Loading',
          html: 'Redirecting Data',
          onOpen: () => {
            swal.showLoading()
          }
        })
      },
      success: function(response) {
        swal.close();
        if (response.status) {
          $("#did").val(response.data.DID)
          $("#par").val(response.data.ParamID)
          $("#parVal").val(response.data.ParamValue)
          $(".buttons").html(`<button type="button" onclick="acl_edit()" style=" margin-right : 6px;" name="save" value="savereturn" class="btn btn-success float-right"><i class="fa fa-check"></i> Edit </button>`)

        } else {
          return false;
        }
      }
    })
  }

  function acl_edit() {
    var did = $("#did").val();
    var param = $("#par").val();
    var param_value = $("#parVal").val();
    var urls = '<?= base_url("backend/aclGroup/edit") ?>';

    if (param == "" || param_value == "") {
      swal({
        title: 'Error..',
        text: 'Please complete the data first ..',
        type: 'error',
        showConfirmButton: false,
        confirmButtonText: false,
        timer: 2000
      })
      return false;
    }
    $.ajax({
      url: urls,
      type: "POST",
      data: {
        DID: did,
        ParamID: param,
        ParamValue: param_value
      },
      dataType: "json",
      beforeSend: function() {
        swal.fire({
          title: 'Loading',
          html: 'Redirecting Data',
          onOpen: () => {
            swal.showLoading()
          }
        })
      },
      success: function(response) {
        swal.close();
        if (response.status) {
          getdataselect();
          $("input:text").val("");
          $(".buttons").html("")
          $('#example1').DataTable().ajax.reload();
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
  }

  function getdataselect() {
    var urla = '<?= base_url("backend/aclGroup/access_menu") ?>'
    $.ajax({
      url: urla,
      type: "POST",
      data: "getData",
      dataType: "json",
      success: function(response) {
        if (response.status) {

          var option = '';
          for (var i in response.data) {
            option += `<option value="` + response.data[i].ParamValue + `">` + response.data[i].ParamValue + `</option>`;
          }

          $(".selected").html(`
          <select name="ACLGroup" class="form-control choose" onchange="getaccess()"'>
          <option value="0" >Choose..</option>
           ` + option + `
          </select>
          
          `);

        } else {
          return false;
        }
      }
    })
  }

  function getaccess() {
    var urle = '<?= base_url("backend/aclGroup/access_user") ?>';
    var selected = $(".choose").val();
    if (selected != 0) {
      $(".access").html("");

      $.ajax({
        url: urle,
        type: "POST",
        data: {
          selected: selected,
        },
        dataType: "json",
        beforeSend: function() {
          swal.fire({
            title: 'Loading',
            html: 'Redirecting Data',
            onOpen: () => {
              swal.showLoading()
            }
          })
        },
        success: function(response) {
          swal.close();
          if (response.status) {

            var checkbox = '';
            for (var a in response.data) {
              var checked = 'checked="checked"'

              checkbox += ` <div id="accordion">
                  <div class = "card ">
                  <div class = "card-header">
                  <h4  class = "card-title w-100">
                  <a   class = "d-block w-100" data-toggle = "collapse" href = "#collapseOne">
                          ` + response.data[a].Menu + `
                        </a>
                      </h4>
                    </div>
                    <div id="collapseOne" class="collapse show" data-parent="#accordion">
                      <div class="card-body">
                        <div class="panel-body">
                          <label class = "checkbox"><input type = "checkbox" class = "module_check" access = "MView" did = "` + response.data[a].DID + `" ${response.data[a].MView == 1 ? checked="checked" : ""}  />View</label>
                          <label class = "checkbox"><input type = "checkbox" class = "module_check" access = "MAdd" did = "` + response.data[a].DID + `" ${response.data[a].MAdd == 1 ? checked="checked" : ""} /> Add</label>
                          <label class = "checkbox"><input type = "checkbox" class = "module_check" access = "MEdit" did = "` + response.data[a].DID + `" ${response.data[a].MEdit == 1 ? checked="checked" : ""} />Edit</label>
                          <label class = "checkbox"><input type = "checkbox" class = "module_check" access = "MDelete" did = "` + response.data[a].DID + `" ${response.data[a].MDelete == 1 ? checked="checked" : ""} />Delete</label>
                          <label class = "checkbox"><input type = "checkbox" class = "module_check" access = "MPrint" did = "` + response.data[a].DID + `" ${response.data[a].MPrint == 1 ? checked="checked" : ""} />Print</label>
                          <label class = "checkbox"><input type = "checkbox" class = "module_check" access = "MExport" did = "` + response.data[a].DID + `" ${response.data[a].MExport == 1 ? checked="checked" : ""} />Export</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>`
            }

            $(".access").html(`<div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Access</label>
                <div class="col-sm-10">
                ` + checkbox + `
                </div>
              </div>
              <button type="button" onclick="saveaccess()" value="yes" class="btn btn-primary float-right"><i class="fa fa-check"></i> Save</button>`);


          } else {
            return false;
          }
        }
      })
    } else {
      $(".access").html("")
      return false;
    }
  }

  function myDeletes(id, urls) {

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
          success: function(result) {
            getdataselect();
            $('#example1').DataTable().ajax.reload();
            if (result) {
              swal({
                title: 'Success..',
                text: 'Success deleted',
                type: 'success',
                showConfirmButton: false,
                confirmButtonText: false,
                timer: 2000
              })
            } else {
              swal({
                title: 'Error..',
                text: 'Failed delete',
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


  function saveaccess() {
    var aclgroup = $(".choose").val();
    var url = '<?= base_url("backend/aclGroup/set_access") ?>';
    var checkbox = document.getElementsByClassName('module_check');

    var serialized = {};
    for (var i = 0; i < checkbox.length; i++) {
      if (checkbox[i].checked === true) {
        var chcek = 1;
      } else {
        var chcek = 0;
      }
      serialized[i] = {
        "did": checkbox[i].getAttribute("did"),
        "access": checkbox[i].getAttribute("access"),
        "value": chcek
      }
    }

    $.ajax({
      url: url,
      type: "POST",
      data: {
        "aclgroup": aclgroup,
        "checked": serialized
      },
      dataType: "json",
      beforeSend: function() {
        swal.fire({
          title: 'Loading',
          html: 'Redirecting Data',
          onOpen: () => {
            swal.showLoading()
          }
        })
      },
      success: function(response) {
        swal.close();
        console.log(response)
        if (response.status) {
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
      },
      error: function(xhr, Status, err) {
        $("Terjadi error : " + Status);
      }
    });


  }


  $("#uncheck_all").change(function() {
    $(".module_check").prop('checked', false);
  })

  $("#check_all").change(function() {
    $(".module_check").prop('checked', true);
  })
</script>

<!-- baru -->