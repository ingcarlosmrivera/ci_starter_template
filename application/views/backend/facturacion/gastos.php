<?php require(__DIR__.'/../header.php') ?>
<?php require(__DIR__.'/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Historial de Gastos
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-line-chart"></i> Facturación</a></li>
        <li class="active">Gastos</li>
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
              <h3 class="box-title">Lista de Gastos</h3>
            </div>
            <div class="box-body">

              <div class="row">
                <form class="form" name="form_search" id="form_search" method="post">
                    <div class="form-group col-sm-3">
                      <label>Buscar por:</label>
                      <select class="form-control" name="buscar_por" id="buscar_por">
                        <option value="cuit" <?= ($buscar_por == 'cuit') ? 'selected' : '' ?>>CUIT</option>
                        <option value="concepto" <?= ($buscar_por == 'concepto') ? 'selected' : '' ?>>Concepto</option>
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

                    <div class="form-group col-sm-2">
                      <label for="">Rango</label>
                      <input type="text" class="form-control" id="ordenes_daterangepicker">
                      <input type="hidden" name="ordenes_fecha_inicio" id="ordenes_fecha_inicio">
                      <input type="hidden" name="ordenes_fecha_fin" id="ordenes_fecha_fin">
                    </div>

                    <div class="form-group col-sm-2">
                      <label>Pagado:</label>
                        <select class="form-control" name="pagado" id="pagado">
                          <option value=""> Todos</option>
                          <option value="1" <?= ($pagado == 1) ? 'selected' : '' ?>> Sí</option>
                          <option value="FALSE" <?= ($pagado == 'FALSE') ? 'selected' : '' ?>> No</option>
                        </select>
                    </div>

                    <div class="form-group col-sm-2">
                      <label class="block">&nbsp;</label>
                      <button type="submit" class="btn btn-primary btn-flat">
                        <i class="fa fa-search"></i> 
                        Buscar
                      </button>
                    </div>

                  </form> 
                <div class="col-xs-12 text-right">
                  <button class="btn btn-success btn-flat btn-sm " data-toggle="modal" data-target="#modal-gasto"><i class="fa fa-plus"></i> Cargar nuevo gasto</button>
                </div>
              </div>

              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Fecha carga</th>
                    <th>Fecha gasto</th>
                    <th>CUIT</th>
                    <th>Concepto</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th class="text-center"><i class="fa fa-cogs"></i></th>
                  </tr>
                </thead>

                <tbody>

                  <?php if ($gastos): ?>
                    <?php foreach ($gastos as $g): ?>
                      <tr>
                        <td><?php echo _date($g->creado) ?></td>
                        <td><?php echo _date($g->fecha) ?></td>
                        <td><?= $g->cuit ?></td>
                        <td>
                          <?php echo $g->concepto ?>
                          <?php if (!empty($g->observaciones)): ?>
                            <br>
                            <small><b>Observaciones:</b></small> <br>
                            <p class="small"><?php echo $g->observaciones ?></p>
                          <?php endif ?>
                          </td>
                        <td>ARS <?php echo $g->total ?></td>
                        <td>
                          <?php if ($g->pagado): ?>
                            <label class="label label-success" data-toggle="tooltip" title="Pagado el día <?= _date($g->fecha_pago) ?>">Pagado</label>
                          <?php else : ?>
                            <label class="label label-danger">Pendiente</label>
                          <?php endif ?>
                        </td>
                        <td class="text-right">
                          <?php if (!$g->pagado): ?>
                            <button class="btn bg-purple btn-xs btn-flat pagar" data-id="<?= $g->idgasto ?>"><i class="fa fa-dollar"></i> Pagar</button>
                          <?php endif ?>
                          <?php if ($g->adjunto): ?>
                            <a href="<?= $g->adjunto ?>" target='_blank' class="btn btn-info btn-xs btn-flat">
                              <i class="fa fa-file-pdf-o"></i> 
                              Ver adjunto
                            </a>
                          <?php endif ?>

                          <button class="btn btn-danger btn-xs btn-flat delete" data-id="<?= $g->idgasto ?>"><i class="fa fa-close"></i> Borrar</button>
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
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <?php echo $this->pagination->create_links() ?>
            </div>
          </div>
          <!-- /.box -->
        </div>
      </div>

      <div class="modal fade" id="modal-gasto">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Nuevo gasto</h4>
            </div>
            <form name="form_gasto" id="form_gasto" class="form" method="POST" enctype="multipart/form-data"> 
              <div class="modal-body">
                <div class="row">
                  <div class="form-group col-md-4">
                    <label for="">Fecha de carga</label>
                    <input type="text" class="form-control" value="<?= date('d/m/Y') ?>" readonly>
                  </div>

                  <div class="form-group col-md-4">
                    <label for="">Fecha de Gasto</label>
                    <input type="text" class="form-control" id="picker">
                    <input type="hidden" name="fecha" id="fecha">
                  </div>

                  <div class="form-group col-md-4">
                    <label for="">CUIT  <span class="badge label-primary" title='Escriba "-" (Guión sin comillas) para cuando no aplique CUIT' data-toggle="tooltip">?</span></label>
                    <input type="text" class="form-control" id="cuit" name="cuit">
                  </div>

                  <div class="form-group col-xs-12 col-md-9">
                    <label for="">Concepto</label>
                    <input type="text" class="form-control" name="concepto" id="concepto" placeholder="Concepto de factura">
                  </div>

                  <div class="form-group col-xs-12 col-md-3">
                    <label for="">Total</label>
                    <input type="text" class="form-control" name="total" id="total" placeholder="Total factura">
                  </div>

                  <div class="form-group col-xs-12 col-sm-6">
                    <label for="exampleInputFile">Adjuntar archivo</label>
                    <input type="file" id="file" name="file">
                  </div>

                  <div class="form-group col-xs-12 col-sm-6">
                    <label for="exampleInputFile">Pegar archivo <label class="label label-danger borrar_imagen_pegada"><span class="fa fa-times"></span></label></label>
                    <input type="hidden" id="paste" name="paste">
                    <img src="" class="imagen_pegada img img-responsive" alt="Presione CTRL + V para pegar imagen del portapapeles">
                  </div>

                  <div class="form-group col-xs-12 col-md-6">
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="pagado" id="pagado">
                        Marcar como pagado
                      </label>
                    </div>
                  </div>

                  <div class="form-group col-md-6 collapse fecha_pago">
                    <label for="">Fecha de Pago</label>
                    <input type="text" class="form-control picker2">
                    <input type="hidden" name="fecha_pago" class="fecha_pago">
                  </div>

                  <div class="clearfix"></div>

                  <div class="form-group col-xs-12">
                    <label for="">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" rows="2" class="form-control full-width"></textarea>
                  </div>
                </div>
                  
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
              </div>
            </form>
              
          </div>
        </div>
      </div>

      <div class="modal fade" id="modal-pagar">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Pagar gasto</h4>
            </div>
            <form name="form_pagar" id="form_pagar" class="form" method="POST" enctype="multipart/form-data"> 
              <div class="modal-body">
                <div class="row">

                  <div class="form-group col-xs-12">
                    <label for="">Fecha de Pago</label>
                    <input type="text" class="form-control picker2">
                    <input type="hidden" name="fecha_pago" class="fecha_pago">
                    <input type="hidden" name="pagando" value="1">
                    <input type="hidden" name="idgasto" class="idgasto">
                  </div>

                </div>
                  
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
              </div>
            </form>
              
          </div>
        </div>
      </div>

    </section>
    <!-- /.content -->

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

  $('.borrar_imagen_pegada').on('click', function(event) {
    $('.imagen_pegada').prop('src', '');
    $('#paste').val('');
  }); 

  $('body').on('paste', function(event) {
    event.preventDefault();
    // use event.originalEvent.clipboard for newer chrome versions
    var items = (event.clipboardData  || event.originalEvent.clipboardData).items;
    console.log(JSON.stringify(items)); // will give you the mime types
    // find pasted image among pasted items
    var blob = null;
    for (var i = 0; i < items.length; i++) {
      if (items[i].type.indexOf("image") === 0) {
        blob = items[i].getAsFile();
      }
    }
    // load image if there is a pasted image
    if (blob !== null) {
      var reader = new FileReader();
      reader.onload = function(event) {
        console.log(event.target.result); // data url!
        $('.imagen_pegada').prop('src', event.target.result)
        $('#paste').val(event.target.result.split("base64,")[1])
      };
      reader.readAsDataURL(blob);
    } else {
      alert("imagen no encontrada en el portapapeles");
    }
  }); 

  $('.pagar').on('click', function(event) {
    event.preventDefault();
    var id = $(this).data('id');
    $('.idgasto').val(id);

    $('#modal-pagar').modal();
  });

  $('.delete').on('click', function(event) {
    event.preventDefault();
    var id = $(this).data('id');
    var r = confirm("¿Seguro desea eliminar este gasto y sus datos asociados? Esta acción no se puede deshacer");

    if (r) {
      var data = {idgasto: id};
      $.ajax({
        url: '/actions/delete_gasto',
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
    }

    
  });

  $('#pagado').on('change', function(event) {
    event.preventDefault();
    if ($(this).is(':checked')) {
      $('div.fecha_pago').show()
    } else {
      $('div.fecha_pago').hide()
    }
  });

  var start = moment();

  function cb(start) {
      $('#reportrange span').html(start.format('DD/MM/YYYY'));
      start = start.format('YYYY-MM-DD HH:mm:ss');

      $('#fecha').val(start)
  }

  $('#picker').daterangepicker({
     singleDatePicker: true,
     "autoApply": true,
      locale: {
        format: 'DD/MM/YYYY'
      },
      startDate: start,
      ranges: {
         'Hoy': [moment().startOf('day'), moment().endOf('day')],
         'Ayer': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
      }
  }, cb);

  cb(start);

  function cb2(start) {
      $('#reportrange span').html(start.format('DD/MM/YYYY'));
      start = start.format('YYYY-MM-DD HH:mm:ss');

      $('.fecha_pago').val(start)
  }

  $('.picker2').daterangepicker({
     singleDatePicker: true,
     "autoApply": true,
      locale: {
        format: 'DD/MM/YYYY'
      },
      startDate: start,
      ranges: {
         'Hoy': [moment().startOf('day'), moment().endOf('day')],
         'Ayer': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
      }
  }, cb2);

  cb2(start);

  $('#form_gasto').validate({
    ignore: "",
    rules: {
      fecha: {
        required: true
      },
      concepto: {
        required: true,
        minlength: 5
      },
      total: {
        required: true,
        number: true
      }
    }, 
    submitHandler: function(form)
    {
      return true;
    }
  })

  $('#form_pagar').validate({
    ignore: "",
    rules: {
      fecha_pago: {
        required: true
      }
    }, 
    submitHandler: function(form)
    {
      return true;
    }
  })
</script>