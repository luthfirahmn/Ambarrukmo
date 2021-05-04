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
<div class="alert_msg"></div>
  <?php echo notify_message($success_msg, $error_msg, $info_msg); ?>
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
                  <th>Parent</th>
                  <th>Menu</th>
                  <th>MenuFile</th>
                  <th>OrderNo</th>
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
  var urls = '<?= base_url();?>backend/menu/get_list';
  var add_urls = '<?= base_url();?>backend/menu/form/add';
  var urls_msg = '<?= base_url();?>backend/menu/alert';
</script>

<!-- baru -->
