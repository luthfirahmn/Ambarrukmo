<ul class="navbar-nav">
  <li class="nav-item">
    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
  </li>
  <li class="nav-item d-none d-sm-inline-block">
    <a href="dashboard" class="nav-link">Dashboard</a>
  </li>
</ul>

<?php /*
    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>
*/ ?>

<!-- Right navbar links -->
<ul class="navbar-nav ml-auto">
 
  <li class="nav-item">
    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
      <i class="fas fa-expand-arrows-alt"></i> Full Screen
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="<?= base_url('backend/login/logout') ?>" role="button">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
  </li>

</ul>