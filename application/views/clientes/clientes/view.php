<?php require(__DIR__.'/../header.php') ?>
<?php require(__DIR__.'/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Ficha de Cliente
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-user"></i> Clientes</a></li>
        <li class="active">Ficha de Cliente</li>
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
              <h3 class="box-title"><i class="fa fa-users"></i> Ficha de Cliente</h3>
            </div>
            
            <div class="box-body">

              <div class="col-xs-12">
                <label class="no-margin" for="">Razón Social</label>
                <h4 class="no-margin-top"><?= $cliente->razon ?></h4>
              </div>

              <div class="col-md-6">
                <label class="no-margin" for="">CUIT</label>
                <h4 class="no-margin-top"><?= $cliente->cuit ?></h4>
              </div>

              <div class="col-md-6">
                <label class="no-margin" for="">Código</label>
                <h4 class="no-margin-top"><?= $cliente->codigo ?></h4>
              </div>

              <div class="col-md-6">
                <label class="no-margin" for="">Teléfono</label>
                <h4 class="no-margin-top"><?= $cliente->telefono ?></h4>
              </div>

              <div class="col-md-6">
                <label class="no-margin" for="">Email</label>
                <h4 class="no-margin-top"><?= $cliente->email ?></h4>
              </div>

              

              <div class="col-xs-12">
                <label class="no-margin" for="">Dirección</label>
                <p class="text-justify">
                  <?= ($cliente->direccion) ? $cliente->direccion : '-' ?>
                </p>
              </div>

              <!-- subclientes -->
              <div class="col-xs-12">
                <h3>Subclientes</h3>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Subcliente</th>
                      <th>Teléfono</th>
                      <th>Email</th>
                      <th class="text-center col-xs-1"><i class="fa fa-cogs"></i></th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php if ($subclientes): ?>
                      <?php foreach ($subclientes as $s): ?>
                        <tr>
                          <td><?= $s->nombre ?></td>
                          <td><?= $s->telefono ?></td>
                          <td><?= $s->email ?></td>
                          <td class="text-center"><button type="button" data-url="/actions/delete_subcliente/<?= $cliente->idcliente ?>/<?= $s->idsubcliente ?>" class="btn btn-danger btn-sm delete"><i class="fa fa-close"></i> Eliminar</button</td>
                        </tr>
                      <?php endforeach ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="4" class="text-center">No hay subclientes registrados</td>
                      </tr>
                    <?php endif ?>
                  </tbody>

                  <tfoot>
                    <tr>
                      <td colspan="4" class="text-right">
                        <button type="button" class="btn btn-primary" id="add_servicio" data-toggle="modal" data-target="#modal-add-subcliente">
                          <i class="fa fa-user-plus"></i> 
                          Agregar nuevo subcliente
                        </button>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
              
              <!-- servicios -->
              <div class="col-xs-12">
                <h3>Servicios</h3>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Servicio</th>
                      <th>Costo</th>
                      <th>Precio</th>
                      <th class="text-center col-xs-1"><i class="fa fa-cogs"></i></th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php if ($servicios_cliente): ?>
                      <?php foreach ($servicios_cliente as $s): ?>
                        <tr>
                          <td><?= $s->servicio ?></td>
                          <td><?= $s->costo ?></td>
                          <td><?= $s->precio ?></td>
                          <td class="text-center"><a href="#" data-url="/actions/delete_servicio_cliente/<?= $cliente->idcliente ?>/<?= $s->idservicio ?>"  class="btn btn-danger btn-sm delete"><i class="fa fa-close"></i> Eliminar</a></td>
                        </tr>
                      <?php endforeach ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="4" class="text-center">No hay servicios asociados a este cliente</td>
                      </tr>
                    <?php endif ?>
                  </tbody>

                  <tfoot>
                    <tr>
                      <td colspan="4" class="text-right">
                        <button type="button" class="btn btn-primary" id="add_servicio" data-toggle="modal" data-target="#modal-add-servicio">
                          <i class="fa fa-plus"></i> 
                          Agregar nuevo servicio
                        </button>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer text-right">
              <a href="/backend/clientes/edit/<?= $cliente->idcliente ?>" class="btn btn-warning">
                <i class="fa fa-pencil"></i> 
                Editar cliente
              </a>
            </div>

          </div>
          <!-- /.box -->
        </div>
      </div>

    </section>
    <!-- /.content -->

    <div class="modal fade" id="modal-add-servicio">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Registrar servicio a cliente</h4>
          </div>

          <form class="form" method="POST" name="form-add-servicio" id="form-add-servicio">
            <div class="modal-body">
              <div class="row">
                <div class="form-group col-xs-12">
                  <label for="servicio">Selecciona el servicio</label>
                  <select name="servicio" id="servicio" class="form-control">
                    <option value="">Seleccione</option>
                    <?php if ($servicios): ?>
                      <?php foreach ($servicios as $s): ?>
                        <option value="<?= $s->idservicio ?>" data-costo="<?= $s->costo ?>"><?= $s->servicio ?></option>
                      <?php endforeach ?>
                    <?php endif ?>
                  </select>
                </div>

                <div class="form-group col-md-6">
                  <label for="servicio">Costo</label>
                  <input type="text" class="form-control" name="costo" id="costo" placeholder="Costo" readonly="readonly">
                </div>

                <div class="form-group col-md-6">
                  <label for="servicio">Precio</label>
                  <input type="text" class="form-control" name="precio" id="precio" placeholder="Precio">
                  <input type="hidden" name="action" id="action" value="servicio">
                  <input type="hidden" name="cliente" id="cliente" value="<?= $cliente->idcliente ?>">
                </div>
              </div>
                  
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-add-subcliente">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Registrar Subcliente</h4>
          </div>

          <form class="form" method="POST" name="form-add-subcliente" id="form-add-subcliente">
            <div class="modal-body">
              <div class="row">
                <div class="form-group col-xs-12">
                  <label for="servicio">Nombre del subcliente</label>
                  <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre completo">
                </div>

                <div class="form-group col-md-6">
                  <label for="servicio">Teléfono</label>
                  <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Teléfono">
                </div>

                <div class="form-group col-md-6">
                  <label for="servicio">Email</label>
                  <input type="text" class="form-control" name="email" id="email" placeholder="Email">
                  <input type="hidden" name="action" id="action" value="subcliente">
                  <input type="hidden" name="cliente" id="cliente" value="<?= $cliente->idcliente ?>">
                </div>
              </div>
                  
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
  <!-- /.content-wrapper -->

<?php require(__DIR__.'/../footer.php') ?>

<script>
  $('.delete').on('click', function(event) {
    event.preventDefault();
    var url = $(this).data('url');
    var response = confirm("¿Seguro desea realizar esta operación?");
    console.log(url);
    if (response == true) {
      $.ajax({
        url: url,
        type: 'GET',
        dataType: 'JSON'
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
      .fail(function(a,b) {
        console.log("error", a , b);
      })
      .always(function() {
        console.log("complete");
      });
      
    }
  });

  $('#servicio').on('change', function(event) {
    event.preventDefault();
    var costo = $('#servicio option:selected').data('costo');
    $('#costo').val(costo);
  });

  $('#form-add-servicio').validate({
    ignore: "",
    rules: {
      servicio: {
        required: true
      },
      precio: {
        required: true,
        number: true
      }
    },
    submitHandler: function(form) {
      var data = $(form).serialize();
      $.ajax({
        type: 'POST',
        dataType: 'JSON',
        data: data,
      })
      .done(function(data) {
        $('#modal_result').removeClass().addClass(data.class);
        $('#modal_result_icon').removeClass().addClass(data.icon);
        $('#modal_result_message').html(data.message);
        $('#modal_result').modal();

        if (data.status == 'success') {
          $('#form_registrar_cliente').trigger("reset");
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
  })

  $('#form-add-subcliente').validate({
    ignore: "",
    rules: {
      nombre: {
        required: true,
        minlength: 3
      },
      telefono: {
        required: true
      },
      email: {
        required: true,
        email: true
      }
    },
    submitHandler: function(form) {
      var data = $(form).serialize();
      $.ajax({
        type: 'POST',
        dataType: 'JSON',
        data: data,
      })
      .done(function(data) {
        $('#modal_result').removeClass().addClass(data.class);
        $('#modal_result_icon').removeClass().addClass(data.icon);
        $('#modal_result_message').html(data.message);
        $('#modal_result').modal();

        if (data.status == 'success') {
          $('#form_registrar_cliente').trigger("reset");
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
  })
</script>