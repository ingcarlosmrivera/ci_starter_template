<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- Sidebar Menu -->
    <ul class="sidebar-menu">
      <li class="header">MAIN MENU</li>
      <!-- Optionally, you can add icons to the links -->

      <li data-url="dashboard"><a href="/backend/dashboard"><i class="fa fa-dashboard"></i> <span>Inicio</span></a></li>

      <!-- pedidos -->
      <li class="treeview">
        <a href="#"><i class="fa fa-dropbox"></i> <span>Pedidos</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <?php if ($this->auth->is_admin()): ?>
            <li data-url="pedidos/new">
              <a href="/backend/pedidos/new">
                <i class="fa fa-plus-square"></i> <span> 
                Nuevo Pedido</span>
              </a>
            </li>

            <li data-url="pedidos/prepedido">
              <a href="/backend/pedidos/prepedido">
                <i class="fa fa-star-half"></i> <span> 
                Prepedidos</span>
              </a>
            </li>

            <li data-url="pedidos/porconfirmar">
              <a href="/backend/pedidos/porconfirmar">
                <i class="fa fa-clock-o"></i> <span> 
                Por Confirmar</span>
              </a>
            </li>
          <?php endif ?>

          <li data-url="pedidos/activado">
            <a href="/backend/pedidos/activado">
              <i class="fa fa-check-square-o"></i> <span> 
              Activados</span>
            </a>
          </li>

          <li data-url="pedidos/analisis">
            <a href="/backend/pedidos/analisis">
              <i class="fa fa-hourglass-2"></i> <span> 
              Análisis</span>
            </a>
          </li>

          <li data-url="pedidos/finalizado">
            <a href="/backend/pedidos/finalizado">
              <i class="fa fa-star"></i> <span> 
              Finalizados</span>
            </a>
          </li>

          <li data-url="pedidos/all">
            <a href="/backend/pedidos/all">
              <i class="fa fa-bullseye"></i> <span> 
              Todos</span>
            </a>
          </li>

          <!-- <li data-url="pedidos/facturado">
            <a href="/backend/pedidos/facturado">
              <i class="fa fa-dollar"></i> <span> 
              Facturados</span>
            </a>
          </li> -->
        </ul>
      </li>
    

      <?php if ($this->auth->is_admin()): ?>

        <!-- Facturas -->
        <li class="treeview">
          <a href="#"><i class="fa fa-line-chart"></i> <span>Facturación</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li data-url="facturacion/add">
              <a href="/backend/facturacion/add">
                <i class="fa fa-plus"></i> <span> 
                Nueva factura</span>
              </a>
            </li>

            <li data-url="facturacion/list">
              <a href="/backend/facturacion/list">
                <i class="fa fa-search"></i> <span> 
                Buscar factura</span>
              </a>
            </li>

            <!-- <li data-url="facturacion/consultar">
              <a href="/backend/facturacion/consultar">
                <i class="fa fa-user-o"></i> <span> 
                Consultar proveedor</span>
              </a>
            </li> -->

            <li data-url="facturacion/gastos">
              <a href="/backend/facturacion/gastos">
                <i class="fa fa-dollar"></i> <span> 
                Gastos</span>
              </a>
            </li>
          </ul>
        </li>

        <!-- Ordenes de compra -->
        <li class="treeview">
          <a href="#"><i class="fa fa-bar-chart"></i> <span>Ordenes de compra</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li data-url="ordenes/add">
              <a href="/backend/ordenes/add">
                <i class="fa fa-plus"></i> <span> 
                Nueva orden</span>
              </a>
            </li>

            <li data-url="ordenes/list">
              <a href="/backend/ordenes/list">
                <i class="fa fa-search"></i> <span> 
                Buscar orden</span>
              </a>
            </li>

            <li data-url="ordenes/detalles">
              <a href="/backend/ordenes/detalles">
                <i class="fa fa-dollar"></i> <span> 
                Detalle Deuda</span>
              </a>
            </li>

            <li data-url="ordenes/consultar">
              <a href="/backend/ordenes/consultar">
                <i class="fa fa-user-o"></i> <span> 
                Consultar proveedor</span>
              </a>
            </li>
          </ul>
        </li>

        <!-- data management -->
        <li class="treeview">
          <a href="#"><i class="fa fa-bar-chart"></i> <span>Datos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li data-url="proveedores/">
              <a href="/backend/proveedores/list">
                <i class="fa fa-user-circle-o"></i> <span> 
                Proveedores</span>
              </a>
            </li>
            <li data-url="clientes/">
              <a href="/backend/clientes/list">
                <i class="fa fa-truck"></i> <span> 
                Clientes</span>
              </a>
            </li>
            <li data-url="servicios/">
              <a href="/backend/servicios/list">
                <i class="fa fa-bookmark"></i> <span> 
                Servicios</span>
              </a>
            </li>

            <li data-url="medicos/">
              <a href="/backend/medicos/list">
                <i class="fa fa-stethoscope"></i> <span> 
                Médicos</span>
              </a>
            </li>
          </ul>
        </li>


        <!-- groups and users management -->
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

        
        <li><a href="#" data-toggle="control-sidebar"><i class="fa fa-dollar"></i> <span>Flujo de caja</span></a></li>
      <?php endif ?>
    </ul>
    <!-- /.sidebar-menu -->
  </section>
  <!-- /.sidebar -->
</aside>