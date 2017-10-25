  <div class="modal fade" id="modal_result" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="modal_result_title"></h4>
        </div>
        <div class="modal-body">
          <span class="" id="modal_result_icon"></span>
          <h5 class="text-center" id="modal_result_message"></h5>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
<!-- Main Footer -->
      <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
          Developed by Carlos Rivera
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; 2017 <a href="#"><?= get_site_name() ?>, INC</a>.</strong> All rights reserved.
      </footer>

      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">

        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
          <li class="active"><a href="#personalizado-tab" data-toggle="tab">Resumen de ventas</a></li>
        </ul>

        
        <!-- Tab panes -->
        <div class="tab-content">

          <div class="tab-pane active" id="personalizado-tab">
            <!-- <h3 class="control-sidebar-heading">Actividad del Día</h3> -->

              <input type="text" class="form-control" id="daterangepicker">
            <!-- /.control-sidebar-menu -->

            <ul class="control-sidebar-menu">
              <li>
                <a href="javascript:;">
                  <i class="menu-icon bg-red" id="cantidad_pedidos_rango">0</i>

                  <div class="menu-info">
                    <h4 class="control-sidebar-subheading">Pedidos realizados en el rango seleccionado</h4>

                  </div>
                </a>
              </li>
            </ul>

            <h3 class="control-sidebar-heading">Relación Costo/Precio</h3>
            <ul class="control-sidebar-menu">
              <li>
                <a href="javascript:;">
                  <h4 class="control-sidebar-subheading">
                    Ingresos
                    <span class="pull-right-container">
                      <span class="label label-info pull-right" id="ingresos_rango">0</span>
                    </span>
                  </h4>
                </a>
              </li>

              <li>
                <a href="javascript:;">
                  <h4 class="control-sidebar-subheading">
                    Costo Variable
                    <span class="pull-right-container">
                      <span class="label label-danger pull-right" id="costos_rango">0</span>
                    </span>
                  </h4>
                </a>
              </li>

              <li>
                <a href="javascript:;">
                  <h4 class="control-sidebar-subheading">
                    Gastos Fijos
                    <span class="pull-right-container">
                      <span class="label label-danger pull-right" id="gastos_rango">0</span>
                    </span>
                  </h4>
                </a>
              </li>

              <li>
                <a href="javascript:;">
                  <h4 class="control-sidebar-subheading">
                    Beneficios
                    <span class="pull-right-container">
                      <span class="label label-success pull-right" id="beneficios_rango">0</span>
                    </span>
                  </h4>
                </a>
              </li>
            </ul>
            <!-- /.control-sidebar-menu -->

          </div>
          <!-- /.tab-pane -->
        </div>
      </aside>
      <!-- /.control-sidebar -->
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->

    <?= get_js($js) ?>

    <!-- file script.js is mandatory, but must be loaded after all plugins to work properly -->
    <script src="/_assets/js/script.js"></script>
    <script>
      <?php if (isset($sidebar_active)): ?>
        var current = $('li[data-url="<?= $sidebar_active ?>"]');
        current.addClass('active');
        if (current.parent().hasClass('treeview-menu')) {
          current.parent().addClass('menu-open')
        }

        if (current.parent().parent().hasClass('treeview')) {
          current.parent().parent().addClass('active')
        }
      <?php endif ?>

        function ventas_personalizadas(start, end)
        {
          $.ajax({
            url: '/actions/get_resumen_ventas/',
            type: 'post',
            data: {start: start, end: end},
            dataType: 'JSON',
          })
          .done(function(data) {
            $('#cantidad_pedidos_rango').html(data.cantidad_pedidos);
            $('#ingresos_rango').html('ARS ' + parseFloat(data.ingresos).toFixed(2));
            $('#costos_rango').html('ARS ' + parseFloat(data.costos).toFixed(2));
            $('#gastos_rango').html('ARS ' + parseFloat(data.gastos).toFixed(2));
            $('#beneficios_rango').html('ARS ' + parseFloat((data.beneficios - data.gastos)).toFixed(2));
          })
          .fail(function() {
            console.log("error");
          })
          .always(function() {
            console.log("complete");
          });
          
        }

        var start = moment().startOf('day');
        var end = moment().endOf('day');

        function cb(start, end, label) {
            $('#reportrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            start = start.startOf('day').format('YYYY-MM-DD HH:mm:ss');
            end = end.endOf('day').format('YYYY-MM-DD HH:mm:ss');

            ventas_personalizadas(start, end);
        }

        $('#daterangepicker').daterangepicker({
           "autoApply": true,
            locale: {
              format: 'DD/MM/YYYY'
            },
            startDate: start,
            endDate: end,
            ranges: {
               'Hoy': [moment().startOf('day'), moment().endOf('day')],
               'Ayer': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
               'Últimos 7 días': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
               'Últimos 30 días': [moment().subtract(29, 'days').startOf('day'), moment().endOf('day')],
               'Este mes': [moment().startOf('month'), moment().endOf('month')],
               'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
               'Este año': [moment().startOf('year'), moment().endOf('year')],
               'Año pasado': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]

            }
        }, cb);

        cb(start, end);
    </script>
    <!-- Optionally, you can add Slimscroll and FastClick plugins.
         Both of these plugins are recommended to enhance the
         user experience. Slimscroll is required when using the
         fixed layout. -->
  </body>
</html>