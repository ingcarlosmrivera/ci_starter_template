<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- Sidebar user panel (optional) -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="/_assets/img/avatar.png" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p><?= sprintf("%s %s", get_user()->first_name, get_user()->last_name) ?></p>
        <!-- Status -->
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>

    <!-- search form (Optional) -->
    <!-- <form action="#" method="get" class="sidebar-form">
      <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search...">
            <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
      </div>
    </form> -->
    <!-- /.search form -->

    <!-- Sidebar Menu -->
    <ul class="sidebar-menu">
      <li class="header">MAIN MENU</li>
      <!-- Optionally, you can add icons to the links -->
      <li>
        <a href="#">
          <i class="fa fa-bar-chart"></i> <span>Transacciones</span>
        </a>
      </li>

      <li class="treeview">
        <a href="#"><i class="fa fa-phone"></i> <span>Recargas</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="#">Administrar Recargas</a></li>
          <li><a href="#">Enviar Recarga</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#"><i class="fa fa-users"></i> <span>Grupos y Usuarios</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li data-url="users/">
            <a href="/backend/users/list">
              <i class="fa fa-circle-o"></i> 
              Gestionar Usuarios
            </a>
          </li>
          <li data-url="groups/">
            <a href="/backend/groups/list">
              <i class="fa fa-circle-o"></i> 
              Gestionar Grupos
            </a>
          </li>
        </ul>
      </li>

      <li><a href="#"><i class="fa fa-cogs"></i> <span>Configuración</span></a></li>
    </ul>
    <!-- /.sidebar-menu -->
  </section>
  <!-- /.sidebar -->
</aside>