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
              <h3 class="box-title">Lista de Facturas</h3>
            </div>
            <div class="box-body">
                <div class="row">
                  <form class="form" name="form_search" id="form_search" method="post">
                    <div class="form-group col-sm-3">
                      <label>Buscar por:</label>
                      <select class="form-control" name="buscar_por" id="buscar_por">
                        <option value="numero_factura" <?= ($buscar_por == 'numero_factura') ? 'selected' : '' ?>>Número de Factura</option>
                        <option value="clientes.razon" <?= ($buscar_por == 'clientes.razon') ? 'selected' : '' ?>>Cliente</option>
                      </select>
                    </div>

                    <div class="form-group col-sm-4" >
                      <label for="">Escribe búsqueda</label>
                      <div class="input-group">
                        <input type="text" name="text" id="text" class="form-control pull-right" placeholder="Buscar" value="<?= $busqueda ?>">
                        <span class="input-group-addon pointer">
                          <span class="fa fa-search "></span>
                        </span>
                      </div>
                    </div>

                    <div class="form-group col-sm-2">
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
                            <th># Factura</th>
                            <th class="text-center"># Pedidos</th>
                            <th>Cliente</th>
                            <th>Total facturado</th>
                            <th class="text-center"><i class="fa fa-cogs"></i></th>
                          </tr>
                        </thead>

                        <tbody>

                          <?php if ($facturas): ?>
                            <?php foreach ($facturas as $c): ?>
                              <tr class="tr_cliente" style="cursor: pointer" data-factura="<?= $c->idfactura?>">
                                <td><?php echo _date($c->fecha) ?></td>
                                <td>
                                  <?php echo $c->numero_factura ?>
                                  <?php if ($c->pagada): ?>
                                    <span class="label label-success" data-toggle="tooltip" title="Marcada como pagada el <?= _date($c->fecha_pago) ?>">pagada</span>
                                    
                                    <?php if ($c->adjunto): ?>
                                      <a href="<?= $c->adjunto ?>" target="_blank" class="badge label-info"><i class="fa fa-file-o"></i> Ver adjunto</a>
                                    <?php else: ?>
                                      <span class="badge label-primary adjuntar" data-idfactura="<?= $c->idfactura ?>"><i class="fa fa-file-o"></i> Adjuntar archivo</span>
                                    <?php endif ?>
                                      
                                  <?php endif ?>    
                                </td>
                                <td class="text-center"><?php echo $c->numero_pedidos ?></td>
                                <td><?php echo $c->razon ?></td>
                                <td>ARS <?php echo $c->total_factura ?></td>
                                <td class="text-right">
                                  <button class="btn btn-danger btn-sm btn-flat redo" data-idfactura="<?= $c->idfactura ?>">
                                    <i class="fa fa-refresh"></i>
                                  </button>
                                  <?php if (!$c->pagada): ?>
                                    <button class="btn btn-warning btn-sm btn-flat pagar" data-idfactura="<?= $c->idfactura ?>">
                                      <i class="fa fa-dollar"></i> 
                                      Pagar
                                    </button>
                                  <?php endif ?>

                                  <a href="/backend/factura_pdf/<?= $c->idfactura ?>" target='_blank' class="btn btn-info btn-sm btn-flat">
                                    <i class="fa fa-eye"></i> 
                                    Ver factura
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

    <div class="modal fade" id="modal-redo">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Actualizar Factura</h4>
          </div>
          <div class="modal-body">
            <form action="/actions/update_factura" method="POST" name="form_redo" id="form_redo">
              <input type="hidden" name="idfactura" id="idfactura2">

              <div class="form-group">
                <label for="">Número de Factura</label>
                <input class="form-control" type="text" name="numero_factura" id="numero_factura2">
              </div>

              <div class="form-group">
                <label for="">Total facturado</label>
                <input class="form-control" type="text" name="total_factura" id="total_factura2" readonly="readonly">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" onclick="$('#form_redo').submit()">Guardar Cambios</button>
          </div>
        </div>
      </div>
    </div>

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
                <h5 class="no-margin">Se marcará la factura como pagada y ya no podrá cambiarse el estado. Si la fecha de pago es diferente a la de hoy, por favor, selecciona la fecha correcta y guarda los cambios.</h5>
              </div>

              <div class="form-group">
                <label for="">Fecha de pago</label>
                <input type="text" class="form-control" id="fecha_pago" name="fecha_pago">
                <input type="hidden" name="idfactura" id="idfactura">
                <input type="hidden" name="fechapago" id="fechapago">
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
                <h5 class="no-margin">Desde acá puedes adjuntar un archivo al documento seleccionado.</h5>
              </div>

              <div class="dropzone">
                
              </div>

              <input type="hidden" name="idfactura" id="idfactura">

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
  $('.redo').on('click', function(event) {
    event.preventDefault();

    var c = prompt('Ingrese clave de acceso!');

    if (c != '28297911') {
      alert('error');
      return;
    }

    var idfactura = $(this).data('idfactura');

    $.ajax({
      url: '/actions/get_factura/' + idfactura,
      dataType: 'JSON',
    })
    .done(function(data) {
      $('#idfactura2').val(idfactura);
      $('#numero_factura2').val(data.numero_factura);
      $('#total_factura2').val(data.total_factura);
      $('#modal-redo').modal();
      console.log("success");
    })
    .fail(function() {
      alert('Ha ocurrido un error');
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });
    
  });

  $('#form_redo').on('submit', function(event) {
    event.preventDefault();
    
    $.ajax({
      url: '/actions/update_factura',
      type: 'POST',
      dataType: 'JSON',
      data: {idfactura: $('#idfactura2').val(), numero_factura: $('#numero_factura2').val(), total_factura: $('#total_factura2').val(), },
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

    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });
    
  });

  $('.dropzone').dropzone({
    url: '/actions/upload_file',
    method: 'post',
    thumbnailWidth: 120,
    thumbnailHeigth: null,
    sending:function(file, xhr, formData){
      formData.append('id',  $('#idfactura').val());
      formData.append('callback',  'adjuntar_factura');
    },
    success:function(file, response){
      alert(response)
    }
  });

  $("#modal-add").on('hide.bs.modal', function () {
    location.reload();
  });

  $('.adjuntar').on('click', function(event) {
    event.preventDefault();
    var idfactura = $(this).data('idfactura');

    $('#idfactura').val(idfactura);
    $('#modal-add').modal();
  });
  

  $('.pagar').on('click', function(event) {
    event.preventDefault();
    var idfactura = $(this).data('idfactura');
    $('#idfactura').val(idfactura);

    $('#modal-confirmar').modal()
  });

  var f = moment().startOf('day');

  function fp(start) {
      start = start.startOf('day').format('YYYY-MM-DD HH:mm:ss');

      $('#fechapago').val(start);
  }

  $('#fecha_pago').daterangepicker({
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
      idfactura: {
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
            location.assign('/backend/facturacion/list');
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