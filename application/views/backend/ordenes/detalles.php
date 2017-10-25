<?php require(__DIR__.'/../header.php') ?>
<?php require(__DIR__.'/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Detalles de Ordenes de Compra/Gastos
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-bar-chart"></i> Ordenes de Compra</a></li>
        <li class="active">Detalles</li>
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
            <div class="box-header with-border">
              <h3 class="box-title">Detalles de Ordenes de Compra/Gastos</h3>
            </div>
            <div class="box-body">
                <div class="row">
                  <form class="form" name="form_search" id="form_search" method="post">
                    <div class="form-group col-sm-3">
                      <label for="">Rango</label>
                      <input type="text" class="form-control" id="ordenes_daterangepicker">
                      <input type="hidden" name="fecha1" id="fecha1">
                      <input type="hidden" name="fecha2" id="fecha2">
                    </div>

                    <div class="form-group col-sm-3">
                      <label class="block">&nbsp;</label>
                      <button type="submit" class="btn btn-primary btn-flat">
                        <i class="fa fa-search"></i> 
                        Buscar
                      </button>
                    </div>

                  </form>                        
                </div>
                    
            </div>
            <!-- /.box-body -->
            
          </div>
          <!-- /.box -->
        </div>
      </div>

      <div class="row">
        <div class="col-xs-12">
          <h1 class="page-header no-margin text-uppercase text-center">
            Relación de ingresos/egresos
          </h1>
        </div>

        <div class="col-md-6 col-md-offset-3 col-sm-6 col-xs-12">
          <div class="info-box">

            <span class="info-box-icon bg-green">
              <i class="fa fa-money"></i>
            </span>

            <div class="info-box-content">
              <span class="info-box-text">total ingresos - gastos fijos - costos variables</span>
              <span class="info-box-number"><?= number_format($n->ingresos_brutos - $n->gastos_totales - $n->costos_variables_brutos, 2) ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      </div>

      <div class="row">
        <div class="col-xs-12">
          <h1 class="page-header no-margin text-uppercase">
            RESUMEN DE COSTOS VARIABLES (CV)
          </h1>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">

            <span class="info-box-icon bg-green">
              <i class="fa fa-money"></i>
            </span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL CV</span>
              <span class="info-box-number"><?= number_format($n->costos_variables_brutos, 2) ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">

            <span class="info-box-icon bg-aqua">
              <i class="fa fa-file-pdf-o"></i>
            </span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL CV CON OC</span>
              <span class="info-box-number"><?= number_format($n->costos_variables_brutos_con_oc, 2) ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">

            <span class="info-box-icon bg-purple">
              <i class="fa fa-file-excel-o"></i>
            </span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL CV CON OC Y FAC</span>
              <span class="info-box-number"><?= number_format($n->costos_variables_brutos_con_oc_facturada, 2) ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">

            <span class="info-box-icon bg-orange">
              <i class="fa fa-credit-card"></i>
            </span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL CV FAC Y PAG</span>
              <span class="info-box-number"><?= number_format($n->costos_variables_brutos_con_oc_facturada_pagada, 2) ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">

            <span class="info-box-icon bg-purple">
              <i class="fa fa-usd"></i>
            </span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL CV PENDIENTE</span>
              <span class="info-box-number"><?= number_format($n->costos_variables_brutos - $n->costos_variables_brutos_con_oc_facturada_pagada, 2) ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">

            <span class="info-box-icon bg-maroon">
              <i class="fa fa-money"></i>
            </span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL CV SIN OC</span>
              <span class="info-box-number"><?= number_format($n->costos_variables_brutos - $n->costos_variables_brutos_con_oc, 2) ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">

            <span class="info-box-icon bg-teal">
              <i class="fa fa-money"></i>
            </span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL CV SIN FAC</span>
              <span class="info-box-number"><?= number_format($n->costos_variables_brutos - $n->costos_variables_brutos_con_oc_facturada, 2) ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      </div>

      <div class="row">
          <div class="col-xs-12 col-md-9">
            <div class="row">
               <div class="col-xs-12">
              <h1 class="page-header no-margin text-uppercase">
                Resumen de Gastos
              </h1>
            </div>

            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="info-box">

                <span class="info-box-icon bg-red">
                  <i class="fa fa-money"></i>
                </span>

                <div class="info-box-content">
                  <span class="info-box-text">Total gastos</span>
                  <span class="info-box-number"><?= number_format($n->gastos_totales, 2) ?></span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>

            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="info-box">

                <span class="info-box-icon bg-green">
                  <i class="fa fa-usd"></i>
                </span>

                <div class="info-box-content">
                  <span class="info-box-text">Gastos pagados</span>
                  <span class="info-box-number"><?= number_format($n->gastos_totales_pagados, 2) ?></span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>

            <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="info-box">

                <span class="info-box-icon bg-yellow">
                  <i class="fa fa-hourglass-half"></i>
                </span>

                <div class="info-box-content">
                  <span class="info-box-text">Gastos Pendientes</span>
                  <span class="info-box-number"><?= number_format(($n->gastos_totales - $n->gastos_totales_pagados), 2) ?></span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            </div>
          </div>           

      </div>

      <div class="row">
          <div class="col-xs-12 col-md-9">
            <div class="row">
               <div class="col-xs-12">
                <h1 class="page-header no-margin text-uppercase">
                Activos
                </h1>
              </div>

              <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">

                  <span class="info-box-icon bg-green">
                    <i class="fa fa-money"></i>
                  </span>

                  <div class="info-box-content">
                    <span class="info-box-text">INGRESOS TOTALES</span>
                    <span class="info-box-number"><?= number_format($n->ingresos_brutos, 2) ?></span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>

              <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">

                  <span class="info-box-icon bg-navy">
                    <i class="fa fa-money"></i>
                  </span>

                  <div class="info-box-content">
                    <span class="info-box-text">A cobrar facturado</span>
                    <span class="info-box-number"><?= number_format($n->costos_variables_brutos_con_oc_facturada - $n->costos_variables_brutos_con_oc_facturada_pagada, 2) ?></span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>

              <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">

                  <span class="info-box-icon bg-teal">
                    <i class="fa fa-money"></i>
                  </span>

                  <div class="info-box-content">
                    <span class="info-box-text">A cobrar NO facturado</span>
                    <span class="info-box-number"><?= number_format($n->costos_variables_brutos - $n->costos_variables_brutos_con_oc_facturada_pagada, 2) ?></span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>

            </div>
          </div>           

      </div>
    </section>
    <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->

<?php require(__DIR__.'/../footer.php') ?>

  

<script>

  <?php if ($fechas): ?>
    var start = moment('<?= $fechas->fecha1 ?>');
    var end = moment('<?= $fechas->fecha2 ?>');
  <?php else: ?>
    var start = moment();
    var end = moment();
  <?php endif ?>


  
  var end = moment();

  function acb(start, end, label) {
      $('#reportrange span').html(start + ' - ' + end);
      start = start.startOf('day').format('YYYY-MM-DD HH:mm:ss');
      end = end.endOf('day').format('YYYY-MM-DD HH:mm:ss');

      $('#fecha1').val(start)
      $('#fecha2').val(end)
  }

  $('#ordenes_daterangepicker').daterangepicker({
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
  }, acb);

  acb(start, end);
  $('.dropzone').dropzone({
    url: '/actions/upload_file',
    method: 'post',
    thumbnailWidth: 120,
    thumbnailHeigth: null,
    sending:function(file, xhr, formData){
      formData.append('id',  $('.idorden:first').val());
      formData.append('callback',  'adjuntar_orden');
    },
    success:function(file, response){
      // alert(response)
      iziToast.success({
          title: 'Éxito',
          message: 'El archivo ha sido cargado correctamente',
      });
    }
  });

  $("#modal-add").on('hide.bs.modal', function () {
    location.reload();
  });

  $('.adjuntar').on('click', function(event) {
    event.preventDefault();
    var idorden = $(this).data('idorden');

    $('.idorden').val(idorden);
    $('#modal-add').modal();
  });
  

  $('.pagar').on('click', function(event) {
    event.preventDefault();
    var idorden = $(this).data('idorden');
    $('.idorden').val(idorden);

    $('#modal-confirmar').modal()
  });

  $('.facturar').on('click', function(event) {
    event.preventDefault();
    var idorden = $(this).data('idorden');
    $('.idorden').val(idorden);

    $('#modal-facturar').modal()
  });

  var f = moment().startOf('day');

  function fp(start) {
      start = start.startOf('day').format('YYYY-MM-DD HH:mm:ss');

      $('#fechapago, #fechafacturada').val(start);
  }

  $('#fecha_pago, #fecha_facturada').daterangepicker({
     "autoApply": true,
      locale: {
        format: 'DD/MM/YYYY'
      },
      singleDatePicker: true
  }, fp);

  fp(f);

  $('#form-pagar').validate({
    ignore: "",
    rules: {
      fecha_pago: {
        required: true
      },
      idorden: {
        required: true
      }
    },
    submitHandler: function(form) {
      var data = $(form).serialize();
      $.ajax({
        type: 'POST',
        dataType: 'JSON',
        data: data
      })
      .done(function(data) {
        $('#modal_result').removeClass().addClass(data.class);
        $('#modal_result_icon').removeClass().addClass(data.icon);
        $('#modal_result_message').html(data.message);
        $('#modal_result').modal();

        if (data.status == 'success') {
          $('#form_registrar_pedido').trigger("reset");
          $("#modal_result").on('hide.bs.modal', function () {
            location.reload();
          });
        }

        console.log("success");
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
      return false;
    }
  })

  $('#form-facturar').validate({
    ignore: "",
    rules: {
      fecha_facturada: {
        required: true
      },
      idorden: {
        required: true
      },
      numero_factura: {
        required: true
      }
    },
    submitHandler: function(form) {
      var data = $(form).serialize();
      $.ajax({
        type: 'POST',
        dataType: 'JSON',
        data: data
      })
      .done(function(data) {
        $('#modal_result').removeClass().addClass(data.class);
        $('#modal_result_icon').removeClass().addClass(data.icon);
        $('#modal_result_message').html(data.message);
        $('#modal_result').modal();

        if (data.status == 'success') {
          $('#form_registrar_pedido').trigger("reset");
          $("#modal_result").on('hide.bs.modal', function () {
            location.reload();
          });
        }

        console.log("success");
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
      return false;
    }
  })
</script>