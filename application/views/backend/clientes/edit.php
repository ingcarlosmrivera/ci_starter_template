<?php require(__DIR__.'/../header.php') ?>
<?php require(__DIR__.'/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Editar Cliente
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-user"></i> Clientes</a></li>
        <li class="active">Editar Cliente</li>
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
              <h3 class="box-title"><i class="fa fa-users"></i> Editar Cliente</h3>
            </div>
            
            <form class="form" name="form_editar_cliente" id="form_editar_cliente" method="post">
              <div class="box-body">
                <div class="row">
                  <div class="col-xs-12 col-md-6 form-group">
                    <label for="razon">Razón social</label>
                    <input type="hidden" name="idcliente" id="idcliente" value="<?= $cliente->idcliente ?>">
                    <input type="text" class="form-control" placeholder="Razón social" name="razon" id="razon" value="<?= $cliente->razon ?>">
                  </div>

                  <div class="col-xs-12 col-md-6 form-group">
                    <label for="cuit">CUIT</label>
                    <input type="text" class="form-control" placeholder="CUIT" name="cuit" id="cuit" value="<?= $cliente->cuit ?>">
                  </div>

                  <div class="col-xs-12 col-md-6 form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" class="form-control" placeholder="Teléfono" name="telefono" id="telefono" value="<?= $cliente->telefono ?>">
                  </div>

                  <div class="col-xs-12 col-md-6 form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" placeholder="Email" name="email" id="email" value="<?= $cliente->email ?>">
                  </div>

                  <div class="col-xs-12 col-md-6 form-group">
                    <label for="codigo">Código de facturación</label>
                    <input type="text" class="form-control" placeholder="Código de facturación" name="codigo" id="codigo" value="<?= $cliente->codigo ?>">
                  </div>

                  <div class="col-xs-12 col-md-6 form-group">
                    <label for="codigo">Requerir Orden de Compra en pedidos</label>
                    <select name="forzar_oc" id="forzar_oc" class="form-control">
                      <option value="1" <?= ($cliente->forzar_oc) ? 'selected="selected"': '' ?>>Sí</option>
                      <option value="0" <?= (!$cliente->forzar_oc) ? 'selected="selected"': '' ?>>No</option>
                    </select>
                  </div>

                  <div class="col-xs-12 form-group">
                    <label for="direccion">Dirección</label>
                    <textarea class="form-control" placeholder="Dirección" name="direccion" id="direccion" rows="2" style="width:100%"><?= $cliente->direccion ?></textarea>
                  </div>
              </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer text-right">
                <button type="button" class="btn btn-default btn-flat">Cancelar</button>
                <button type="submit" class="btn btn-success btn-flat">Editar Cliente</button>
              </div>
            </form>

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
  $('#form_editar_cliente').validate({
      ignore: "",
      rules: {
        razon: {
          required: true,
          minlength: 5
        },
        cuit: {
          required: true,
          digits: true
        },
        telefono: {
          required: true
        },
        email: {
          required: true,
          // remote: {
          //   url: "/actions/check_email",
          //   type: "post"
          // }
        },
        codigo: {
          required: true
        },
        direccion: {
          required: true
        }
      },
      submitHandler(form) {
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
            $('#form_editar_cliente').trigger("reset");
            $("#modal_result").on('hide.bs.modal', function () {
              location.assign('/backend/clientes/list');
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