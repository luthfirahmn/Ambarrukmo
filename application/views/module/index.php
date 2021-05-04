<!-- Content Header (Page header) -->
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><?php echo $title;?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#"><?php echo $breadcrumb;?></a></li>
              <li class="breadcrumb-item active"><?php echo $breadcrumb1;?></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
    <?php echo notify_message($success_msg , $error_msg , $info_msg);?>
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><?php echo $data_tabel?></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="pull-left">
      			    
      			</div>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Nomor</th>
                    <th>Name</th>
                    <th>File</th>
                    <th>Controllers</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>

                  <?php 
                  $nomor = 1;
                  foreach($all_data as $key => $values):?>
                    <tr>
                      <td><?php echo $nomor ++ ?></td>
                      <td><?php echo $values->name?></td>
                      <td><?php echo $values->file?></td>
                      <td><?php echo $values->controllers?></td>
                      <td><center>
                      <!-- <a href="#" class="btn btn-info btn-sm"><i class="fas fa-info-circle"></i></a> -->
                      <?php /* if(is_allowed($this->session->userdata('user'), 'module', 'edit')):?>
                        <button id="edit_button" value="<?= base_url('backend/module/form/'.$values->id);?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>
                      <?php endif; */?>
                      <?php if(is_allowed($this->session->userdata('user'), 'module', 'edit')):?>
                        <a href="<?= base_url('backend/module/form/'.$values->id);?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                      <?php endif;?>
                      <?php if(is_allowed($this->session->userdata('user'), 'module', 'delete')):?>
                        <a href="<?= base_url('backend/module/delete/'.$values->id);?>" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                      <?php endif;?>
                      </center></td>
                    </tr>
                  <?php endforeach;?>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Nomor</th>
                    <th>Name</th>
                    <th>File</th>
                    <th>Conttrollers</th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
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

<script src="<?= base_url('assets')?>/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('assets')?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="<?= base_url('assets')?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets')?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url('assets')?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('assets')?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url('assets')?>/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('assets')?>/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url('assets')?>/plugins/jszip/jszip.min.js"></script>
<script src="<?= base_url('assets')?>/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?= base_url('assets')?>/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?= base_url('assets')?>/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= base_url('assets')?>/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= base_url('assets')?>/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('assets')?>/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url('assets')?>/dist/js/demo.js"></script>

<!-- baru -->
<script>
  $( document ).ready(function() {
    $("#edit_button").click(function(){
      console.log("rsdfsdf..");
    })
  
  });

</script>

<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": [
            {
                text: 'Add',
                action: function ( e, dt, node, config ) {
                  location.href = "<?= base_url();?>backend/module/form/add"
                }
            }
        ]
      //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
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
</script>
