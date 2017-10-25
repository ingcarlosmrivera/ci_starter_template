<?php require(__DIR__.'/../header.php') ?>
<?php require(__DIR__.'/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Generar nueva factura
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-line-chart"></i> Facturación</a></li>
        <li class="active">Nueva factura</li>
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
              <h3 class="box-title"><i class="fa fa-dollar"></i> Nueva factura</h3>
            </div>
            <div class="box-body">
              <div class="row">
                <form class="form" name="form_search" id="form_search" method="post">
                  <div class="form-group col-xs-12 col-sm-6">
                    <label>Seleccionar cliente:</label>
                    <select class="form-control" name="cliente" id="cliente">
                    </select>
                  </div>

                  <div class="form-group col-sm-4">
                      <label for="">Rango</label>
                      <input type="text" class="form-control" id="pedidos_daterangepicker">
                      <input type="hidden" name="pedidos_fecha_inicio" id="pedidos_fecha_inicio">
                      <input type="hidden" name="pedidos_fecha_fin" id="pedidos_fecha_fin">
                    </div>


                </form>                        
              </div>

              <div class="alert alert-danger collapse" id="no_results">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>No se encontraron pedidos pendientes de facturar para este cliente</strong>
              </div>

              <table class="table table-bordered collapse" id="table">
                <thead>
                  <tr>
                    <th class="text-center"> - </th>
                    <th>Fecha</th>
                    <th>Candidato</th>
                    <th>Servicio</th>
                    <th>Cliente</th>
                    <th>Subcliente</th>
                    <th>Precio</th>
                    <th>Estado</th>
                  </tr>
                </thead>

                <tbody>
                  
                </tbody>

                <tfoot class="collapse">
                  <tr>
                    <td colspan="8">
                      <a href="#" data-action="check" id="all" onclick="checkall(this)"><span class="fa fa-hand-pointer-o"></span> Seleccionar/Deseleccionar todos
                      </a>
                    </td>
                  </tr>

                  <tr>
                    <td colspan="8" id="facturar" class="collapse text-right">
                      <form  method="POST" class="form-inline" role="form" name="form" id="form">
                      
                        <div class="form-group">
                          <label class="sr-only" for="">Número de Factura</label>
                          <input type="text" class="form-control" id="factura" name="factura" placeholder="Número de Factura">
                        </div>
                      
                        <button type="submit" class="btn btn-success">Generar Factura</button>
                      </form>
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">

            </div>
          </div>
          <!-- /.box -->
        </div>
      </div>

    </section>
    <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->

<?php require(__DIR__.'/../footer.php') ?>

<script>
  $("#cliente").select2({
    theme: "bootstrap",
    allowClear: true,
    placeholder: "Escriba y seleccione",
    tags: false,
    multiple: false,
    ajax: {
      url: '/actions/search_clientes_pendientes_factura/',
      data: function(params) {
        return {
          q : params.term,
          start : $('#pedidos_fecha_inicio').val(),
          end : $('#pedidos_fecha_fin').val(),
        }
      },
      dataType: "json",
      type: "POST",
      cache: false,
      minimumInputLength: 1,
      processResults: function (data) {
        var results = [];
        $.each(data, function (index, item) {
            results.push({
                id: item.idcliente,
                text: item.cliente
            });
        });

        return {
            results: results
        };
      }
    }
  })

  $("#cliente").on('change', function(event) {
    event.preventDefault();
    var v = $(this).val();

    if (v !== null && v !== undefined) {
      $('#table').show();

      $.ajax({
        url: '/actions/buscar_pedidos_no_facturados/' + v,
        dataType: 'JSON',
        data: {idcliente: v, start: $('#pedidos_fecha_inicio').val(), end: $('#pedidos_fecha_fin').val() },
        type: 'POST'
      })
      .done(function(data) {
        $('#table tbody').empty();
        $('.checkbox').off('change');
        if (data) {
          $('#no_results').hide()
          $('#table tfoot').show()
          var disponible = 0;

          $.each(data.pedidos, function(i, item) {
            var fecha = moment(item.creado).format('DD/MM/YYYY');
            if (item.requiere_oc == 1 && item.oc == null) {
              var checkbox = "<label class='label label-danger'><i class='fa fa-close'></i></label>";
              var oc = "";
            } else if (item.requiere_oc == 1 && item.oc != null) {
              var checkbox = "<input type='checkbox' class='checkbox' name='pedidos[]' data-costo='"+item.costo+"' data-precio='"+item.precio+"' value='"+item.idpedido+"'>";
              var oc = '<span class="label label-info">'+item.oc+'</span>'
            } else {
              var checkbox = "<input type='checkbox' class='checkbox' name='pedidos[]' data-costo='"+item.costo+"' data-precio='"+item.precio+"' value='"+item.idpedido+"'>";
              var oc = "";
            }

            var $tr = $('<tr>').append(
              $('<td class="text-center">').html(checkbox),
              $('<td>').text(fecha),
              $('<td>').text(item.candidato),
              $('<td>').text(item.servicio),
              $('<td>').text(item.cliente),
              $('<td>').text(item.nombre),
              $('<td>').text('ARS ' + item.precio),
              $('<td>').html('<span class="label label-primary">'+item.estado+'</span> ' + oc)

            ).appendTo('#table tbody');


            if (item.requiere_oc == 1 && item.oc != null) {
              disponible += parseFloat(item.precio)
            }
          });
          

          var html = '<span class="label label-warning" style="margin-right: 10px">TOTAL PENDIENTE DE FACTURAR: ARS '+data.total+'</span>';
          if (disponible > 0) {
            html += '<span class="label label-success">TOTAL DISPONIBLE DE FACTURAR: ARS '+disponible+'</span>'
          }
          var tr = $('<tr>').prepend(
            $('<td class="text-center" colspan="8">').html(html)
          );

          $(tr).prependTo('#table tbody');

          $('.checkbox').on('change', function(event) {
            event.preventDefault();
            if ($('.checkbox:checked').length > 0) {
              $('#facturar').show();
            } else {
              $('#facturar').hide();
            }
          });

        } else {
          $('#no_results').show();
          $('#table tfoot').hide();
        }
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
      
    }  else {
      $('#table').hide();
    }   
  });

    <?php if ($pedidos_fecha_inicio): ?>
    var start = moment('<?= date('Y-m-d', strtotime($pedidos_fecha_inicio)) ?>');
    var end = moment('<?= date('Y-m-d', strtotime($pedidos_fecha_fin)) ?>');
  <?php else: ?>
    var start = moment();
    var end = moment();
  <?php endif ?>


  function acb(start, end, label) {
      $('#reportrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
      start = start.startOf('day').format('YYYY-MM-DD HH:mm:ss');
      end = end.endOf('day').format('YYYY-MM-DD HH:mm:ss');

      $('#pedidos_fecha_inicio').val(start)
      $('#pedidos_fecha_fin').val(end)
      $('#cliente').trigger('change')
  }

  $('#pedidos_daterangepicker').daterangepicker({
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

  function checkall(btn)
  {
    var action = $(btn).data('action');
    if (action == 'check') {
      $('.checkbox').prop('checked', true);
      $(btn).data('action', 'uncheck');
    } else {
      $('.checkbox').prop('checked', false);
      $(btn).data('action', 'check');
    }

    $('.checkbox:first').trigger('change');
  }

  function facturar()
  {
    var data = {idcliente: $('#cliente').val(), pedidos: $(".checkbox").map(function(){return $(this).val()}).get()}
  }

  $('#form').validate({
    rules: {
      factura: {
        required: true
      }
    },
    submitHandler: function(form) {
      var data = {factura: $('#factura').val(), idcliente: $('#cliente').val(), pedidos: $(".checkbox:checked").map(function(){return $(this).val()}).get()};

      $.ajax({
        type: 'POST',
        dataType: 'html',
        data: data,
      })
      .done(function(data) {
        location.reload();
      })
      .fail(function() {
        alert('ha ocurrido un error');
        location.reload();
      })
      .always(function() {
        console.log("complete");
      });
      

      return false;
    }
  })

    
</script>