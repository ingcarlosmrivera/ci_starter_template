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
  <style>
    body, html {
      height: 100%!important;
    }
  </style>
</head>

<body class="hold-transition login-page" style="overflow:hidden;height: 100%!important;min-height: 100%!important;background-position: center;background-repeat: no-repeat;background-size: cover;clear: both">
  
  <div class="login-box" style="min-height: calc(100% - 130px)">
    <div class="login-logo">
      <img src="/_assets/img/logo.png" alt="Consultar H&S" class="img img-responsive" style="width: 70%;margin:0 auto">
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

        <div class="form-group has-feedback">
          <label for="">Soy un:</label>
          <select name="tipo" id="tipo" class="form-control">
            <option value="cliente">Cliente</option>
            <option value="subcliente">Solicitante</option>
            <option value="medico">Médico</option>
          </select>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-xs-12">
            <button type="submit" class="btn bg-orange btn-block btn-flat">Sign In</button>

            <a href="/backend/login" class="text-center block" style="margin-top: 10px">Soy un administrador</a>
          </div>
          <!-- /.col -->
        </div>
      </form>

    </div>
    <!-- /.login-box-body -->
  </div>
<!-- /.login-box -->

  <footer style="position: absolute;text-align: center;clear: both;padding: 0 15px">
    <div class="row">
      <!-- /.col -->
      <div class="col-xs-12">
        <p>
          ::: <b>CONSULTAR H&S SA</b> – CUIT.: 30 71244688 5 – Perú 345 12 C CABA CP:1067 – TE.: <a href="tel:0054 11 5238 2404">0054 11 5238 2404</a> - <a href="mailto:consultar@consultar-rrhh.com">consultar@consultar-rrhh.com</a> – <a href="mailto:comercial@consultar-rrhh.com">comercial@consultar-rrhh.com</a> – <a href="mailto:soporte@consultar-rrhh.com">soporte@consultar-rrhh.com</a> :::
        </p>
      </div>
      <!-- /.col -->
    </div>
  </footer>

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