<?php require(__DIR__.'/header.php') ?>
<?php require(__DIR__.'/sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Resumen Global</small>
      </h1>
      <ol class="breadcrumb">
        <li class="active"><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- alert message if defined -->
      <?php if ($this->session->flashdata('message')): ?>
        <div class="alert <?= $this->session->flashdata('message')->alert_class ?>">
          <h4 class="no-margin"><?= $this->session->flashdata('message')->message ?></h4>
        </div>
      <?php endif ?>

      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <!-- <div class="box-header with-border">
              <h3 class="box-title">Lista de Gastos</h3>
            </div> -->

            <div class="box-body">

              <div class="row">
                <?php if ($this->auth->is_admin()): ?>
                  <div class="col-xs-12 col-md-3">
                    <div class="form-group">
                      <label for="">Distribución mensual de Pedidos</label>
                      <input type="text" class="form-control" id="picker1">
                    </div>
                  </div>

                  <div class="col-xs-12 col-md-3">
                    <div class="form-group">
                      <label for="">Gráfica de ventas</label>
                      <input type="text" class="form-control" id="picker2">
                    </div>
                  </div>
                <?php endif ?>

                <div class="col-xs-12 col-md-3">
                  <div class="form-group">
                    <label for="">Estatus de pedidos</label>
                    <input type="text" class="form-control" id="picker3">
                  </div>
                </div>

                <div class="clearfix"></div>

                <?php if ($this->auth->is_admin()): ?>
                  <div class="col-xs-12">
                    <div id="distribucion_pedidos" data-tipo="1" style="height: 200px; width: 100%;"></div>
                  </div>

                  <div class="col-xs-12 col-md-6">
                    <div id="reporte_ventas" data-tipo="2" style="height: 200px; width: 100%;"></div>
                  </div>
                <?php endif ?>

                <div class="col-xs-12 col-md-<?= ($this->auth->is_admin()) ? '6' : '12' ?>">
                  <div id="estatus_pedidos" data-tipo="3" style="height: 200px; width: 100%;"></div>
                </div>
              </div>

              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>

    </section>
    <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->

<?php require(__DIR__.'/footer.php') ?>

<script>

  var start = moment().startOf('month');
  var end = moment().endOf('month');

  <?php if ($this->auth->is_admin()): ?>
    function grafica1(start = moment().startOf('year'), end = moment().endOf('year')) {
      $('#reportrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
      start = start.startOf('day').format('YYYY-MM-DD HH:mm:ss');
      end = end.endOf('day').format('YYYY-MM-DD HH:mm:ss');

      ajax_dashboard(start, end, 'r1', '#grafica1');
    }

    function grafica2(start = moment().startOf('month'), end = moment().endOf('month')) {
        $('#reportrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
        start = start.startOf('day').format('YYYY-MM-DD HH:mm:ss');
        end = end.endOf('day').format('YYYY-MM-DD HH:mm:ss');

        ajax_dashboard(start, end, 'r2', '#grafica2');
    }

    $('#picker1').daterangepicker({
       "autoApply": true,
       showCustomRangeLabel: false,
        locale: {
          format: 'DD/MM/YYYY'
        },
        startDate: moment().startOf('year'),
        endDate: moment().endOf('year'),
        ranges: {
           'Este año': [moment().startOf('year'), moment().endOf('year')],
           'Año pasado': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
        }
    }, grafica1);

    $('#picker2').daterangepicker({
       "autoApply": true,
        locale: {
          format: 'DD/MM/YYYY'
        },
        startDate: moment().startOf('month'),
        endDate: moment().endOf('month'),
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
    }, grafica2);

    var chart = new CanvasJS.Chart("distribucion_pedidos", {
      title:{
        text: "Distribución mensual de pedidos"              
      },
      animationEnabled: true,
      data: [              
        {
          // Change type to "doughnut", "line", "splineArea", etc.
          type: "line",
          dataPoints: [
            { label: "ENE",  y: 0  },
            { label: "FEB",  y: 0  },
            { label: "MAR",  y: 0  },
            { label: "ABR",  y: 0  },
            { label: "MAY",  y: 0  },
            { label: "JUN",  y: 0  },
            { label: "JUL",  y: 0  },
            { label: "AGO",  y: 0  },
            { label: "SEP",  y: 0  },
            { label: "OCT",  y: 0  },
            { label: "NOV",  y: 0  },
            { label: "DIC",  y: 0  }
          ]
        }
      ]
    });

    var data2 = [{type: "splineArea", showInLegend: true, legendText: "Ingresos", dataPoints: []}, {type: "splineArea", showInLegend: true, legendText: "Costos", dataPoints: []}];

    var chart2 = new CanvasJS.Chart("reporte_ventas", {
      title:{
        text: "Representación gráfica de ventas"              
      },
      showCustomRangeLabel: false,
      animationEnabled: true,
      data: data2
    });

    chart.render();
    chart2.render();
  <?php endif ?>

  

  function grafica3(start = moment().startOf('month'), end = moment().endOf('month')) {
      $('#reportrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
      start = start.startOf('day').format('YYYY-MM-DD HH:mm:ss');
      end = end.endOf('day').format('YYYY-MM-DD HH:mm:ss');

      ajax_dashboard(start, end, 'r3', '#grafica3');
  }

  $('#picker3').daterangepicker({
     "autoApply": true,
      locale: {
        format: 'DD/MM/YYYY'
      },
      startDate: moment().startOf('month'),
      endDate: moment().endOf('month'),
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
  }, grafica3);  


  var chart3 = new CanvasJS.Chart("estatus_pedidos", {
    title:{
      text: "Distribución estatus de pedidos"              
    },
    animationEnabled: true,
    data: [              
      {
        // Change type to "doughnut", "line", "splineArea", etc.
        type: "doughnut",
        dataPoints: [
          
        ]
      }
    ]
  });
  chart3.render();

  function ajax_dashboard(start, end, tipo, target)
  {
    $.ajax({
      url: '/actions/dashboard_report/' + tipo,
      type: 'POST',
      dataType: 'JSON',
      data: {start: start, end: end, },
    })
    .done(function(data) {

      if (data) {

        if (tipo == "r1") {
          $.each(data, function(index, b) {
             chart.options.data[0].dataPoints[index].y = parseInt(b.total);
          });

          chart.render();  
        } else if (tipo == "r2") {
          // reset array
          data2[0].dataPoints = [];
          data2[1].dataPoints = [];
          $.each(data, function(index, b) {
            data2[0].dataPoints[index] = {label: b.fecha,y: parseFloat(b.ingresos)};
            data2[1].dataPoints[index] = {label: b.fecha,y: parseFloat(b.costos)};
          });

          chart2.options.data = data2;

          chart2.render();
        } else if (tipo == 'r3') {
          chart3.options.data[0].dataPoints = [];

          $.each(data, function(index, b) {
            chart3.options.data[0].dataPoints[index] = {label: b.estado, y: parseInt(b.total)};
          });

          chart3.render(); 
        }

      } else {
        iziToast.error({
            title: 'Error',
            message: 'No se encontraron resultados, intente con otro rango de fechas.',
        });
      }           
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    }); 
  }

  $(document).ready(function($) {
    <?php if ($this->auth->is_admin()): ?>
      grafica1();
      grafica2();
    <?php endif ?>

    grafica3();
  });
</script>