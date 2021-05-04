<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminAmbar | Log in</title>
  <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> -->
  <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url('assets') ?>/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="<?= base_url('assets') ?>index2.html"><b>Ambarrukmo</b></a>
    </div>
    <div class="card">
      <div class="card-body login-card-body" style="border-radius: 10px;">
        <?php echo notify_message($success_msg, $error_msg, $info_msg); ?>
        <p class="login-box-msg">Sign in to start your session</p>
        <form action=" <?= base_url() ?>backend/login/sign_in" method="post" id="login">
          <div class="input-group mb-3">
            <input type="email" name="data[email]" class="form-control" placeholder="Email" id="email" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="data[password]" class="form-control" placeholder="Password" id="password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" name="remember_me" id="remember">
                <label for="remember">
                  Remember Me
                </label>
              </div>
            </div>
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="<?= base_url('assets') ?>/plugins/jquery/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      $.ajax({
        url: '<?= base_url("backend/login/remember_me") ?>',
        type: "POST",
        data: {
          remember_me: true
        },
        dataType: "json",
        success: function(response) {
          $("#email").val(response.email);
          $("#password").val(response.password);
          $("#remember").prop('checked', response.checked);
        }
      })
    })

    setTimeout(function() {
      $('.alert-danger').hide();
    }, 4000);

    $(".close").click(function() {
      $(".alert-danger").hide();
    })
  </script>
</body>
</html>