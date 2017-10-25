<?php require(__DIR__.'/../header.php') ?>
<?php require(__DIR__.'/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Lista de Ordenes de Compra
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-bar-chart"></i> Ordenes de Compra</a></li>
        <li class="active">Lista de Ordenes</li>
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
              <h3 class="box-title">Lista de Ordenes de Compra</h3>
            </div>
            <div class="box-body">
                <div class="row">
                  <form class="form" name="form_search" id="form_search" method="post">
                    <div class="form-group col-sm-3">
                      <label>Buscar por:</label>
                      <select class="form-control" name="buscar_por" id="buscar_por">
                        <option value="ordenes_compra.idorden" <?= ($buscar_por == 'ordenes_compra.idorden') ? 'selected' : '' ?>>Número de Orden de Compra</option>
                        <option value="ordenes_compra.numero_factura" <?= ($buscar_por == 'ordenes_compra.numero_factura') ? 'selected' : '' ?>>Número de Factura</option>
                        <option value="users.razon" <?= ($buscar_por == 'users.razon') ? 'selected' : '' ?>>Proveedor</option>
                      </select>
                    </div>

                    <div class="form-group col-sm-3" >
                      <label for="">Escribe búsqueda</label>
                      <div class="input-group">
                        <input type="text" name="text" id="text" class="form-control pull-right" placeholder="Buscar" value="<?= $busqueda ?>">
                        <span class="input-group-addon pointer">
                          <span class="fa fa-search "></span>
                        </span>
                      </div>
                    </div>



                    <div class="form-group col-sm-3">
                      <label for="">Rango</label>
                      <input type="text" class="form-control" id="ordenes_daterangepicker">
                      <input type="hidden" name="ordenes_fecha_inicio" id="ordenes_fecha_inicio">
                      <input type="hidden" name="ordenes_fecha_fin" id="ordenes_fecha_fin">
                    </div>

                    <div class="form-group col-sm-2">
                      <label>Facturada:</label>
                        <select class="form-control" name="facturada" id="facturada">
                          <option value=""> Todas</option>
                          <option value="1" <?= ($facturada == 1) ? 'selected' : '' ?>> Sí</option>
                          <option value="FALSE" <?= ($facturada == 'FALSE') ? 'selected' : '' ?>> No</option>
                        </select>
                    </div>

                    <div class="form-group col-sm-2">
                      <label>Pagada:</label>
                      <select class="form-control" name="pagada" id="pagada">
                        <option value=""> Todas</option>
                        <option value="1" <?= ($pagada == 1) ? 'selected' : '' ?>> Sí</option>
                          <option value="FALSE" <?= ($pagada == 'FALSE') ? 'selected' : '' ?>> No</option>
                      </select>
                    </div>

                    <div class="form-group col-sm-2">
                      <label>Descargar resultado:</label>
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" value="on" name="download" id="download">
                        </label>
                      </div>
                    </div>

                    <div class="form-group col-sm-3">
                      <label class="block">&nbsp;</label>
                      <button type="submit" class="btn btn-primary btn-flat">
                        <i class="fa fa-search"></i> 
                        Buscar
                      </button>
                    </div>

                  </form>  

                  <div class="col-xs-12">
                    <div class="table-responsive">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>Fecha</th>
                            <th># Orden</th>
                            <th class="text-center"># Pedidos</th>
                            <th>Proveedor</th>
                            <th>Total</th>
                            <th class="text-center"><i class="fa fa-cogs"></i></th>
                          </tr>
                        </thead>

                        <tbody>

                          <?php if ($ordenes): ?>
                            <?php foreach ($ordenes as $c): ?>
                              <tr class="tr_cliente" style="cursor: pointer" data-orden="<?= $c->idorden?>">
                                <td><?php echo _date($c->fecha) ?></td>
                                <td>
                                  OC-
                                  <?php echo $c->idorden ?>
                                  <?php if ($c->pagada): ?>
                                    <span class="label label-success" data-toggle="tooltip" title="Marcada como pagada el <?= _date($c->fecha_pago) ?>">Pagada</span>                              
                                  <?php endif ?> 

                                  <?php if ($c->facturada): ?>
                                    
                                    <span class="label bg-purple" data-toggle="tooltip" title="Facturada el: <?= _date($c->fecha_facturada) ?>">
                                      Factura <?= $c->numero_factura ?>
                                    </span>
                                    
                                    <?php if ($c->adjunto): ?>
                                      <a href="<?= $c->adjunto ?>" target="_blank" class="badge label-info"><i class="fa fa-file-o"></i> Ver adjunto</a>
                                    <?php else: ?>
                                      <span class="badge label-primary adjuntar" data-idorden="<?= $c->idorden ?>"><i class="fa fa-file-o"></i> Adjuntar archivo</span>
                                    <?php endif ?>
                                      
                                  <?php endif ?>    
                                </td>
                                <td class="text-center"><?php echo $c->numero_pedidos ?></td>
                                <td><?php echo $c->razon ?></td>
                                <td>ARS <?php echo $c->total_orden ?></td>
                                <td class="text-right">
                                  <?php if (!$c->pagada && $c->facturada): ?>
                                    <button class="btn btn-warning btn-sm btn-flat pagar" data-idorden="<?= $c->idorden ?>">
                                      <i class="fa fa-dollar"></i> 
                                      Pagar
                                    </button>
                                  <?php endif ?>

                                  <?php if (!$c->facturada): ?>
                                    <button class="btn bg-purple btn-sm btn-flat facturar" data-idorden="<?= $c->idorden ?>">
                                      <i class="fa fa-file-o"></i> 
                                      Facturar
                                    </button>
                                  <?php endif ?>

                                  <a href="/backend/orden_pdf/<?= $c->idorden ?>" target='_blank' class="btn btn-info btn-sm btn-flat">
                                    <i class="fa fa-eye"></i> 
                                    Ver orden
                                  </a>
                                </td>
                              </tr>
                            <?php endforeach; ?>
                          <?php else: ?>
                            <tr>
                              <td  colspan="7">
                                <h5 class="text-center">
                                  No se encontraron resultados
                                </h5>
                              </td>

                            </tr>
                          <?php endif; ?>

                        </tbody>
                      </table>

                      <?php if (isset($montos)): ?>
                        <h4>Montos adeudados para estos parámetros de búsqueda</h4>
                        <table class="table">
                          <thead>
                            <tr>
                              <th class="text-center bg-danger">Deuda Total</th>
                              <th class="text-center bg-success">Deuda Pagada</th>
                              <th class="text-center bg-warning">Deuda Pendiente</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <?php $pendiente = (float)$montos['total'] - (float)$montos['pagado'] ?>
                              <td class="text-center bg-danger"><?php echo "ARS {$montos['total']}" ?></td>
                              <td class="text-center bg-success"><?php echo "ARS {$montos['pagado']}" ?></td>
                              <td class="text-center bg-warning"><?php echo "ARS " . $pendiente ?></td>
                            </tr>
                          </tbody>
                        </table>
                      <?php endif ?>
                        
                    </div>
                  </div>                      
                </div>
                    
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <?php echo $this->pagination->create_links() ?>
            </div>
          </div>
          <!-- /.box -->
        </div>
      </div>

    </section>
    <!-- /.content -->

    <div class="modal fade" id="modal-confirmar">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Pagar Factura</h4>
          </div>
          <form method="POST" role="form" name="form-pagar" id="form-pagar">

            <div class="modal-body">
              <div class="alert alert-info">
                <h5 class="no-margin">Se marcará la orden como pagada y ya no podrá cambiarse el estado. Si la fecha de pago es diferente a la de hoy, por favor, selecciona la fecha correcta y guarda los cambios.</h5>
              </div>

              <div class="row">
                <div class="form-group col-xs-12 ">
                  <label for="">Fecha de pago</label>
                  <input type="text" class="form-control" id="fecha_pago" name="fecha_pago">
                  <input type="hidden" name="idorden" class="idorden">
                  <input type="hidden" name="fechapago" id="fechapago">
                </div>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-success">Guardar cambios</button>
            </div>
          
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-facturar">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Facturar orden de compra</h4>
          </div>
          <form method="POST" role="form" name="form-facturar" id="form-facturar">

            <div class="modal-body">
              <div class="alert alert-info">
                <h5 class="no-margin">Se marcará la orden como facturada y ya no podrá cambiarse el estado. Es necesario el número de factura.</h5>
              </div>

              <div class="row">
                <div class="form-group col-xs-12 col-md-6">
                  <label for="">Fecha de facturación</label>
                  <input type="text" class="form-control" id="fecha_facturada" name="fecha_facturada">
                  <input type="hidden" name="idorden" class="idorden">
                  <input type="hidden" name="fechafacturada" id="fechafacturada">
                </div>

                <div class="form-group col-xs-12 col-md-6">
                  <label for="">Número de Factura</label>
                  <input type="text" class="form-control" id="numero_factura" name="numero_factura">
                </div>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-success">Guardar cambios</button>
            </div>
          
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-add">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Agregar adjunto</h4>
          </div>
          <form method="POST" role="form" name="form-pagar" id="form-adjunto">

            <div class="modal-body">
              <div class="alert alert-info">
                <h5 class="no-margin">Desde acá puedes adjuntar un archivo al registro seleccionado.</h5>
              </div>

              <div class="dropzone">
                
              </div>

              <input type="hidden" name="idorden" class="idorden">

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Volver</button>
            </div>
          
          </form>
        </div>
      </div>
    </div>

  </div>
  <!-- /.content-wrapper -->

<?php require(__DIR__.'/../footer.php') ?>

  

<script>

  <?php if ($ordenes_fecha_inicio): ?>
    var start = moment('<?= date('Y-m-d', strtotime($ordenes_fecha_inicio)) ?>');
    var end = moment('<?= date('Y-m-d', strtotime($ordenes_fecha_fin)) ?>');
  <?php else: ?>
    var start = moment();
    var end = moment();
  <?php endif ?>


  
  var end = moment();

  function acb(start, end, label) {
      $('#reportrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
      start = start.startOf('day').format('YYYY-MM-DD HH:mm:ss');
      end = end.endOf('day').format('YYYY-MM-DD HH:mm:ss');

      $('#ordenes_fecha_inicio').val(start)
      $('#ordenes_fecha_fin').val(end)
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