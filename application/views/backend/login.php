<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= get_site_title() ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <?= get_css($css) ?>

  <!-- Own Styles -->
  <link rel='stylesheet' href='/_assets/css/styles.css'>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body class="hold-transition login-page">
  
  <div class="login-box">
    <div class="login-logo">
      <a href="#"><b><?= get_site_title() ?></b></a>
    </div>

    <?php if ($this->session->flashdata('message')): ?>
      <div class="row">
        <div class="col-xs-12">
          <div class="alert <?= $this->session->flashdata('message')->alert_class ?>">
            <h4 class="no-margin text-center"><?= $this->session->flashdata('message')->message ?></h4>
          </div>
        </div>
      </div>
    <?php endif ?>
      
    <!-- /.login-logo -->
    <div class="login-box-body">
      <?php if (validation_errors()): ?>
        <ul class="list list-unstyled margin-bottom-10">
          <li><h4 class="text-red">Some errors were found:</h4></li>
          <?= validation_errors("<li class='error'>", '</li>') ?>
        </ul>
      <?php endif ?>

      <p class="no-margin text-center padding-bottom-10">Sign in to start your session</p>      

      <form method="post">
        <div class="form-group has-feedback">
          <input type="email" name="email" class="form-control" placeholder="Email" value="<?= set_value('email') ?>">
          <span class="fa fa-envelope-o form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <span class="fa fa-slack form-control-feedback"></span>
        </div>
        <div class="row">
          <div class="col-xs-8">
            <div class="checkbox icheck">
              <label>
                <input type="checkbox" name="remember" checked="checked"> Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-xs-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <a href="/backend/actions/restore_password">Olvidé mi contraseña</a><br>
      <a href="/clientes/login" class="text-center">Entrar como cliente</a>

    </div>
    <!-- /.login-box-body -->
  </div>
<!-- /.login-box -->

<?= get_js($js) ?>

<!-- file script.js is mandatory, but must be loaded after all plugins to work properly -->
<script src="/_assets/js/script.js"></script>

<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>