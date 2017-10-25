<?php require(__DIR__.'/../header.php') ?>
<?php require(__DIR__.'/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Registrar Proveedor
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-user"></i> Proveedores</a></li>
        <li class="active">Registrar Proveedor</li>
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
              <h3 class="box-title"><i class="fa fa-users"></i> Registrar Proveedor</h3>
            </div>
            
            <form class="form" name="form_registrar_proveedor" id="form_registrar_proveedor" method="post">
              <div class="box-body">
                <div class="row">
                  <div class="col-xs-12 col-md-6 form-group">
                    <label>Razón social</label>
                    <input type="text" class="form-control" placeholder="Razón social" name="razon" id="razon">
                  </div>

                  <div class="col-xs-12 col-md-3 form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" placeholder="Email" name="email" id="email">
                  </div>

                  <div class="col-xs-12 col-md-3 form-group">
                    <label>Contraseña</label>
                    <input type="text" class="form-control" placeholder="Contraseña" name="password" id="password">
                  </div>

                  <div class="col-xs-12 col-md-3 form-group">
                    <label>CBU</label>
                    <input type="text" class="form-control" placeholder="CBU" name="cbu" id="cbu">
                  </div>

                  <div class="col-xs-12 col-md-3 form-group">
                    <label>Cuenta</label>
                    <input type="text" class="form-control" placeholder="Cuenta" name="cuenta" id="cuenta">
                  </div>

                  <div class="col-xs-12 col-md-3 form-group">
                    <label>CUIT</label>
                    <input type="text" class="form-control" placeholder="CUIT" name="cuit" id="cuit">
                  </div>

                  <div class="col-xs-12 col-md-3 form-group">
                    <label>Banco</label>
                    <input type="text" class="form-control" placeholder="Banco" name="banco" id="banco">
                  </div>

                  <div class="col-xs-12 col-md-3 form-group">
                    <label>Teléfono</label>
                    <input type="text" class="form-control" placeholder="Teléfono" name="telefono" id="telefono">
                  </div>

                  <div class="col-xs-12 col-md-9 form-group">
                    <label>Domicilio</label>
                    <input type="text" class="form-control" placeholder="Domicilio" name="domicilio" id="domicilio">
                  </div>

                  <div class="clearfix"></div>

                  <div class="col-xs-12 col-md-6 form-group">
                    <label>Provincia</label>
                    <select name="id_provincia" id="id_provincia" class="form-control">
                      <option value="">Seleccione...</option>
                      <?php foreach ($provincias as $p): ?>
                        <option value="<?= $p->idprovincia ?>"><?= $p->provincia ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>

                  <div class="col-xs-12 col-md-6 form-group">
                    <label>Localidad</label>
                    <select name="id_localidad" id="id_localidad" class="form-control">
                      <option value="">Seleccione...</option>
                    </select>
                  </div>

                  <div class="clearfix"></div>

                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer text-right">
                <button type="button" class="btn btn-default btn-flat">Cancelar</button>
                <button type="submit" class="btn btn-success btn-flat">Registrar Proveedor</button>
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
  $("#id_provincia").on('change', function(event) {
    event.preventDefault();
    var p = $(this).val();
    $("#id_localidad").empty()

    if (p !== "") {
      $.getJSON('/actions/get_localidades/' + p, function(localidades, textStatus) {
          $.each(localidades, function(index, l) {
            $("#id_localidad").append('<option value="' + l.idlocalidad + '">' + l.localidad + '</option>')
          });
      });
    }

  });

  $('#form_registrar_proveedor').validate({
    rules: {
      razon: {
        required: true,
        minlength: 5
      },
      password: {
        required: true,
        minlength: 6
      },
      telefono: {
        required: true
      },
      email: {
        required: true,
        email: true
      },
      id_provincia: {
        required: true
      },
      id_localidad: {
        required: true
      }
    },
    submitHandler(form) {
      return true;
    }
  })
</script>