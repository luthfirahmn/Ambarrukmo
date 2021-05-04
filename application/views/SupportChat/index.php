  <!-- SWEETALERT -->
  <link href="<?= base_url('assets') ?>/plugins/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">


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
      <div class="row">
        <div class="col-md-3">
          <!-- <a href="compose.html" class="btn btn-primary btn-block mb-3">Compose</a> -->

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Menu</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body p-0">
              <ul class="nav nav-pills flex-column">
                <li class="nav-item active">
                  <a href="#" onclick="location.reload();" class="nav-link">
                    <i class="fas fa-inbox"></i> Inbox
                    <!-- <span class="badge bg-primary float-right">12</span> -->
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" data-id="1" Label="Deleted" class="nav-link trashtable">
                    <i class="far fa-trash-alt"></i> Trash
                  </a>
                </li>
              </ul>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Status</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body p-0">
              <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                  <a href="#" data-id="1" class="nav-link readtable" label="Readed">
                    <i class="fa fa-envelope-open fa-sm"></i>
                    Read
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" data-id="0" class="nav-link readtable" label="Unread">
                    <i class="fa fa-envelope fa-sm"></i>
                    Unread
                  </a>
                </li>
              </ul>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title table">Inbox</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle checkbox-delete"><i class="far fa-square"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm delete_chat"><i class="far fa-trash-alt"></i></button>
                  <button type="button" onclick="reload_chat();" class="btn btn-default btn-sm reload_chat"><i class="fas fa-sync-alt"></i></button>
                </div>
                <!-- /.btn-group -->
                
                <div class="float-right">
                  <div class="card-tools">
                    <!-- Search -->
                    <div class="input-group input-group-sm">
                      <input type="text" class="form-control" placeholder="Search Chat" id="search_chat">
                    </div>
                    <!-- /.Search -->
                  </div>
                  <!-- /.card-tools -->
                </div>
                <!-- /.float-right -->
              </div>
              <div class="table-responsive mailbox-messages">
                <table id="chat" class="table table-hover table-striped chat">
                    <thead>
                      <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.card-body -->
            </div>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>



  <!--sweetalert -->
  <script src="<?= base_url('assets') ?>/plugins/sweetalert2/dist/sweetalert2.min.js"></script>
  <script src="<?= base_url('assets') ?>/plugins/sweetalert2/sweet-alert.init.js"></script>

  <script>

  //DATATABLE
  $(document).ready(function() {
        show_button();
        //INBOX
         
         chat = $('.chat').DataTable({  
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": false,
            "bAutoWidth": false,
            "ordering": false,
            "processing": true, 
            "serverSide": true,
            "order": [], 
           
            "ajax": {
                "url": "<?php echo base_url('backend/SupportChat/get_list')?>",
                "type": "POST"
            },
             "columnDefs": [
            { 
                "targets": [ -1 ], 
                "orderable": false,

            },
            {
                  "targets": [ 1 ],
                  "visible": false,
                  "searchable": false
            },
            {
                  className: "check", 
                  "targets": [ 1,2,3,4]
            },
            { 
                  'targets': 0,
                  'createdCell':  function (td, cellData, rowData, row, col) {
                     $(td).attr('onclick', ''); 
                   }
            },

            ],
            
            "oLanguage": {
            "sZeroRecords": "No Data"
            },
            "fnInitComplete": function ( oSettings ) {
            oSettings.oLanguage.sZeroRecords = "Data Not Found"
            }

         });

        //SEARCH 
        $("#chat_filter").detach()

        $('#search_chat').keyup(function(){
            chat.search($(this).val()).draw();

        })
       //========================================================================
      
      //READTABLE
      $('.readtable').on('click', function () {
      $(".chat").dataTable().fnDestroy();
      show_button();
      var namepage = $(this).attr("label");
      var status_selection = $(this).attr("data-id");
      $("h3.table").html(namepage+' Massage');

       read = $('.chat').DataTable({

          "bLengthChange": false,
          "bFilter": true,
          "bInfo": false,
          "bAutoWidth": false,
          "ordering": false,
          "processing": true, 
          "serverSide": true,
          "order": [], 
         
          "ajax": {
              "url": "<?php echo base_url('backend/SupportChat/get_list_read')?>",
              "type": "POST",
              "data": {status_selection:status_selection}
          },
           "columnDefs": [
          { 
              "targets": [ -1 ], 
              "orderable": false, 

          },
          {
                "targets": [ 1 ],
                "visible": false,
                "searchable": false
          },
          ],
          
          "oLanguage": {
          "sZeroRecords": "No Data"
          },
          "fnInitComplete": function ( oSettings ) {
          oSettings.oLanguage.sZeroRecords = "Data Not Found"
          }

       });

      //SEARCH 
      $("#chat_filter").detach()

      $('#search_chat').keyup(function(){
          read.search($(this).val()).draw();

       })
      })
     //========================================================================

       //TRASHTABLE
      $('.trashtable').on('click', function () {
      $(".chat").dataTable().fnDestroy();
      hide_button();
      var namepage = $(this).attr("label");
      var status_deleted = $(this).attr("data-id");
      $("h3.table").html(namepage+' Massage');

       trash = $('.chat').DataTable({

          "bLengthChange": false,
          "bFilter": true,
          "bInfo": false,
          "bAutoWidth": false,
          "ordering": false,
          "processing": true, 
          "serverSide": true,
          "order": [], 
         
          "ajax": {
              "url": "<?php echo base_url('backend/SupportChat/get_list_deleted')?>",
              "type": "POST",
              "data": {status_deleted:status_deleted}
          },
           "columnDefs": [
          { 
              "targets": [ -1 ], 
              "orderable": false, 

          },
          {
                "targets": [ 0,1 ],
                "visible": false,
                "searchable": false
          },
          ],
          
          "oLanguage": {
          "sZeroRecords": "No Data"
          },
          "fnInitComplete": function ( oSettings ) {
          oSettings.oLanguage.sZeroRecords = "Data Not Found"
          }

       });

      //SEARCH 
      $("#chat_filter").detach()

      $('#search_chat').keyup(function(){
          trash.search($(this).val()).draw();

       })
      })
     //========================================================================
          
    });
  //END DATATABLE

  //RELOAD DATATABLE INBOX
  function reload_chat() {

      chat.ajax.reload(null,false);
      read.ajax.reload(null,false);  
  }
  //END RELOAD

  $(document).ready(function() {
    $('tbody').on('click','tr', function(evt){
        var $cell=$(evt.target).closest('td');
    if( $cell.index()<=0){
       return true;
   }
        var data = chat.row( this.rowIndex-1 ).data();
        var id = data[1];
        $.ajax({
        url:"<?php echo base_url(); ?>backend/SupportChat/update_read_message/"+id,
        method:"POST",
        beforeSend :function ()
        {
          swal.fire({
              title: 'Loading',
              html: 'Redirecting Data',
              onOpen: () => 
              {
                swal.showLoading()
              }
          })      
        },
        success:function(data)
        {
          console.log('Success');
          window.location.href = "<?php echo base_url('backend/SupportChat/chat/')?>"+id;
        }


            })
            //END AJAX
        
    });
  });


  //==========================================================================================================

  //CHECKBOX SELECTED
  $(function () {
    //Enable check and uncheck all functionality
    $('.checkbox-toggle').click(function () {
      var clicks = $(this).data('clicks')
      if (clicks) {
        //Uncheck all checkboxes
        $('.mailbox-messages input[type=\'checkbox\']').prop('checked', false)
        $('.checkbox-toggle .far.fa-check-square').removeClass('fa-check-square').addClass('fa-square')
      } else {
        //Check all checkboxes
        $('.mailbox-messages input[type=\'checkbox\']').prop('checked', true)
        $('.checkbox-toggle .far.fa-square').removeClass('fa-square').addClass('fa-check-square')
      }
      $(this).data('clicks', !clicks)
    })

    //Handle starring for glyphicon and font awesome
    $('.mailbox-star').click(function (e) {
      e.preventDefault()
      //detect type
      var $this = $(this).find('a > i')
      var glyph = $this.hasClass('glyphicon')
      var fa    = $this.hasClass('fa')

      //Switch states
      if (glyph) {
        $this.toggleClass('glyphicon-star')
        $this.toggleClass('glyphicon-star-empty')
      }

      if (fa) {
        $this.toggleClass('fa-star')
        $this.toggleClass('fa-star-o')
      }
    })
  })
  //END CHECKBOX SELECTED

  //==========================================================================================================

  //DELETE CHAT
  $('.delete_chat').click(function(){
    var checkbox = $('.checkbox_delete_chat:checked');
    if(checkbox.length > 0)
    {
      var checkbox_value = [];
      $(checkbox).each(function(){
        checkbox_value.push($(this).val());
      });

      swal.fire({
      title: 'Confirm',
      text: "Delete Selected Data?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Delete',
      confirmButtonColor: '#FF0000',
      cancelButtonText: 'Cancel',
      }).then((result) => 
      {

      if (result.value) 
      {
       $.ajax({
        url:"<?php echo base_url(); ?>backend/SupportChat/delete_chat",
        method:"POST",
        beforeSend :function ()
        {
          swal.fire({
              title: 'Menunggu',
              html: 'Memproses data',
              onOpen: () => 
              {
                swal.showLoading()
              }
          })      
        },
        data:{checkbox_value:checkbox_value},
        success:function(data)
        {
          swal.fire(
            'Success',
            'Data Deleted',
            'success'
          ).then((result) => 
          {
           reload_chat();
          })
        }


            })
            //END AJAX
        }
        //END IF
                            
        });

      }else{
        swal.fire(
            'Warning',
            'Select Data ',
            'warning'
            )
          }
      })


      function hide_button()
      {
        $(".delete_chat").css({"display":"none"});
        $(".checkbox-delete").css({"display":"none"});
        $(".reload_chat").css({"display":"none"});
      }


      function show_button()
      {
      $(".delete_chat").css({"display":"inline"});
      $(".checkbox-delete").css({"display":"inline"});
      $(".reload_chat").css({"display":"inline"});
      }



</script>
</body>
</html>
