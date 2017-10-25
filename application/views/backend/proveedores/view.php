<?php require(__DIR__.'/../header.php') ?>
<?php require(__DIR__.'/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Ficha de Proveedor
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-user"></i> Proveedores</a></li>
        <li class="active">Ficha de Proveedor</li>
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
              <h3 class="box-title"><i class="fa fa-users"></i> Ficha de Proveedor</h3>
            </div>
            
            <div class="box-body">

              <div class="col-xs-12">
                <h1 class="no-margin-top text-uppercase" style="font-weight: 100"><?= $proveedor->razon ?></h1>
              </div>

              <div class="col-md-6">
                <label class="no-margin" for="">Teléfono</label>
                <h4 class="no-margin-top"><?= $proveedor->telefono ?></h4>
              </div>

              <div class="col-md-6">
                <label class="no-margin" for="">Email</label>
                <h4 class="no-margin-top"><?= $proveedor->email ?></h4>
              </div>

              <div class="col-md-6">
                <label class="no-margin" for="">Provincia</label>
                <h4 class="no-margin-top"><?= $proveedor->provincia ?></h4>
              </div>

              <div class="col-md-6">
                <label class="no-margin" for="">Localidad</label>
                <h4 class="no-margin-top"><?= $proveedor->localidad ?></h4>
              </div>

              <div class="col-md-6">
                <label class="no-margin" for="">CBU</label>
                <h4 class="no-margin-top"><?= (!empty($proveedor->cbu)) ? $proveedor->cbu : '-' ?></h4>
              </div>

              <div class="col-md-6">
                <label class="no-margin" for="">Cuenta</label>
                <h4 class="no-margin-top"><?= (!empty($proveedor->cuenta)) ? $proveedor->cuenta : '-' ?></h4>
              </div>

              <div class="col-md-6">
                <label class="no-margin" for="">CUIT</label>
                <h4 class="no-margin-top"><?= (!empty($proveedor->cuit)) ? $proveedor->cuit : '-' ?></h4>
              </div>

              <div class="col-md-6">
                <label class="no-margin" for="">Banco</label>
                <h4 class="no-margin-top"><?= (!empty($proveedor->banco)) ? $proveedor->banco : '-' ?></h4>
              </div>     

              <div class="col-xs-12">
                <label class="no-margin" for="">Domicilio</label>
                <p class="text-justify">
                  <?= ($proveedor->domicilio) ? $proveedor->domicilio : '-' ?>
                </p>
              </div>

              <!-- servicios -->
              <div class="col-xs-12">
                <h3>Servicios</h3>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Servicio</th>
                      <th>Costo</th>
                      <th class="text-center col-xs-1"><i class="fa fa-cogs"></i></th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php if ($servicios_proveedores): ?>
                      <?php foreach ($servicios_proveedores as $s): ?>
                        <tr>
                          <td><?= $s->servicio ?></td>
                          <td>ARS <?= $s->costo ?></td>
                          <td class="text-center"><a href="#" data-url="/actions/delete_servicio_proveedores/<?= $proveedor->idproveedor ?>/<?= $s->idservicio ?>"  class="btn btn-danger btn-sm delete"><i class="fa fa-close"></i> Eliminar</a></td>
                        </tr>
                      <?php endforeach ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="4" class="text-center">No hay servicios asociados a este proveedor</td>
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
              <a href="/backend/proveedores/edit/<?= $proveedor->idproveedor ?>" class="btn btn-warning">
                <i class="fa fa-pencil"></i> 
                Editar proveedor
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
            <h4 class="modal-title">Registrar servicio a proveedor</h4>
          </div>

          <form class="form" method="POST" name="form-add-servicio" id="form-add-servicio">
            <div class="modal-body">
              <div class="row">
                <div class="form-group col-xs-12 col-md-9">
                  <label for="servicio">Selecciona el servicio</label>
                  <select name="servicio" id="servicio" class="form-control">
                    <option value="">Seleccione</option>
                    <?php if ($servicios): ?>
                      <?php foreach ($servicios as $s): ?>
                        <option value="<?= $s->idservicio ?>"><?= $s->servicio ?></option>
                      <?php endforeach ?>
                    <?php endif ?>
                  </select>
                </div>

                <div class="form-group col-xs-12 col-md-3">
                  <label for="servicio">Costo</label>
                  <input type="text" class="form-control" name="costo" id="costo" placeholder="Costo">
                  <input type="hidden" name="proveedor" id="proveedor" value="<?= $proveedor->idproveedor ?>">
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
  $('#form-add-servicio').validate({
    ignore: "",
    rules: {
      servicio: {
        required: true
      },
      costo: {
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
</script>