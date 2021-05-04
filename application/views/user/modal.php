<!-- MODAL -->
 <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Employee</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

            <div class="card">
            <div class="card-header">
              <h3 class="card-title">Table Employee</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $no=1; foreach ($emp as $row) { 
                 ?>
                <tr>
                  <td><?php echo $no++; ?></td>
                  <td><?php echo $row->EmpID; ?></td>
                  <td><?php echo $row->FullName; ?></td>
                  <td><a href="javascript:void(0)" onclick="SelectEmp(<?php echo $row->EmpID; ?>)"  class="btn btn-success" >Select</button></td>
                </tr>
                <?php }
                 ?>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

