 <!-- Brand Logo -->
 <a href="#" class="brand-link">
   <img src="<?= base_url('assets') ?>/dist/img/logo.jpg" class="brand-image img-circle elevation-3" style="opacity: .8">
   <span class="brand-text font-weight-light">AMBARRUKMO</span>
 </a>

 <!-- Sidebar -->
 <div class="sidebar">
   <!-- Sidebar user panel (optional) -->
   <div class="user-panel mt-3 pb-3 mb-3 d-flex">
     <div class="image">
       <img src="<?= base_url('assets') ?>/img/profile.jpg" class="img-circle elevation-2" alt="User Image">
     </div>
     <div class="info">
       <a href="#" style=" margin-top: -11px; margin-bottom: -2px;" class="d-block">Welcome,<br><?= get_user($this->session->userdata('user')) ?></a>
     </div>
   </div>

   <!-- SidebarSearch Form -->
   <div class="form-inline">
     <div class="input-group" data-widget="sidebar-search">
       <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
       <div class="input-group-append">
         <button class="btn btn-sidebar">
           <i class="fas fa-search fa-fw"></i>
         </button>
       </div>
     </div>
   </div>

   <!-- Sidebar Menu -->
   <nav class="mt-2">
     <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

       <li class="nav-item">
         <a href="<?= base_url("backend/dashboard") ?>" class="nav-link">
           <i class="nav-icon fas fa-tachometer-alt"></i>
           <p>
             Dashboard
             <span class="right badge badge-danger">New</span>
           </p>
         </a>
       </li>
       <?php echo get_menu($this->session->userdata('ACLGroup')); ?>
     </ul>
   </nav>
 </div>