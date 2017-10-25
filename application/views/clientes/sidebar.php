<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- Sidebar Menu -->
    <ul class="sidebar-menu">
      <li class="header">MAIN MENU</li>

      <!-- data management -->
      <?php if ($this->session->userdata('tipo') !== 'medico'): ?>
        <li class="treeview">
          <a href="#"><i class="fa fa-bar-chart"></i> <span>Operaciones</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li data-url="clientes/dashboard">
              <a href="/clientes/dashboard">
                <i class="fa fa-user-circle-o"></i> <span> 
                Dashboard</span>
              </a>
            </li>

            <?php if ($tipo != 'cliente'): ?>
              <li data-url="clientes/pedido">
                <a href="/clientes/pedido">
                  <i class="fa fa-dropbox"></i> <span> 
                  Nuevo pedido</span>
                </a>
              </li>
            <?php endif ?>
              
            <li data-url="clientes/busqueda">
              <a href="/clientes/busqueda">
                <i class="fa fa-search"></i> <span> 
                Busqueda de pedidos</span>
              </a>
            </li>

            <li data-url="clientes/facturas">
              <a href="/clientes/facturas">
                <i class="fa fa-dollar"></i> <span> 
                Facturas</span>
              </a>
            </li>

          </ul>
        </li>
      <?php else: ?>
        <li class="treeview">
          <a href="#"><i class="fa fa-bar-chart"></i> <span>Operaciones</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
         

            <li data-url="clientes/medicos">
              <a href="/clientes/medicos">
                <i class="fa fa-stethoscope"></i> <span> 
                Resumen</span>
              </a>
            </li>

          </ul>
        </li>
      <?php endif ?>


      <!-- <li><a href="#"><i class="fa fa-cogs"></i> <span>Configuración</span></a></li> -->
    </ul>
    <!-- /.sidebar-menu -->
  </section>
  <!-- /.sidebar -->
</aside>