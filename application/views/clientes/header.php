<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= get_site_title() ?> | Administration Panel</title>
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

<body class="hold-transition skin-purple sidebar-mini">
  <div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

      <!-- Logo -->
      <a href="#" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>H&S</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><img src="/_assets/img/minilogo.png" alt="" class="img img-responsive" height="50px" style="height: 47px;margin: 0 auto"></span>
      </a>

      <!-- Header Navbar -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- The user image in the navbar-->
                <img src="/_assets/img/avatar.png" class="user-image" alt="User Image">
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs">
                  <?php if ($this->session->userdata('tipo') !== 'medico'): ?>
                      <?= sprintf("%s", $cliente->razon) ?>
                    <?php else: ?>
                      <?= sprintf("%s", $medico->nombre) ?>
                    <?php endif ?>
                </span>
              </a>
              <ul class="dropdown-menu">
                <!-- The user image in the menu -->
                <li class="user-header">
                  <img src="/_assets/img/avatar.png" class="img-circle" alt="User Image">

                  <p>
                    <?php if ($this->session->userdata('tipo') !== 'medico'): ?>
                      <?= sprintf("%s", $cliente->razon) ?>
                      <small><?= $cliente->email ?></small>
                    <?php else: ?>
                      <?= sprintf("%s", $medico->nombre) ?>
                      <small><?= $medico->email ?></small>
                    <?php endif ?>
                    
                  </p>
                </li>
                <!-- Menu Body -->

                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-right">
                    <a href="/clientes/logout" class="btn btn-block btn-danger btn-flat">Sign out</a>
                  </div>
                </li>
              </ul>
            </li>
            <!-- Control Sidebar Toggle Button -->
            <!-- <li>
              <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
            </li> -->
          </ul>
        </div>
      </nav>
    </header>