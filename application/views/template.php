<!DOCTYPE html>
<html lang="en">
<head>

<?php echo $this->load->view('template/head', '', TRUE);?>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">

     <?php echo $this->load->view('template/navbar', '', TRUE);?>
  
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
     <?php echo $this->load->view("template/sidebar" ,'' , TRUE)?>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  
      <?php echo $this->load->view($content_file, $content_data , TRUE);?> 

  </div>
  <!-- /.content-wrapper -->


  <footer class="main-footer">
  <?php echo $this->load->view('template/footer', '', TRUE);?>
  </footer>

</body>
</html>
