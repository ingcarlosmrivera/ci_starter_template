<?php require(__DIR__.'/../header.php') ?>
<?php require(__DIR__.'/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Editar Médicos
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-user"></i> Médicos</a></li>
        <li class="active">Editar médico</li>
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
              <h3 class="box-title"><i class="fa fa-pencil"></i> Editar Médico</h3>
            </div>
            <div class="box-body">
              <!-- validation errors, if case that client side validation fails -->
              <?php if (validation_errors()): ?>
                <ul class="list list-unstyled margin-bottom-10">
                  <li><h4 class="text-red">Some errors were found:</h4></li>
                  <?= validation_errors("<li class='error'>", '</li>') ?>
                </ul>
              <?php endif ?>

              <form method="post" name="form_edit_user" id="form_edit_user" class="form" role="form">
                <!-- userid is required for changes -->
                <input type="hidden" name="idmedico" id="idmedico" value="<?= $medico->idmedico ?>">

                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="nombre">Nombre</label>
                      <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" value="<?= $medico->nombre ?>">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?= $medico->email ?>">
                    </div>
                  </div>


                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="password">Contraseña</label>
                      <input type="text" class="form-control" id="password" name="password" placeholder="Contraseña" value="<?= $medico->password ?>">
                    </div>
                  </div>

                <div class="row">
                  <div class="col-xs-12 text-right">
                    <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-check"></i> Guardar cambios</button>
                    <button type="button" class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modal-delete-user"><i class="fa fa-trash"></i> Borrar médico</button>
                  </div>
                </div>
              </form>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>

    </section>
    <!-- /.content -->

    <!-- own modal -->
    <div class="modal fade" id="modal-delete-user">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">¿Seguro quieres borrar este médico?</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="alert alert-danger">
                <h5 class="text-center text-uppercase no-margin">ESTA ACCIÓN NO PUEDE DESHACERSE</h5>
              </div>
            </div>

            <p>Si se require restringir el accesso a un médico, se recomienda desactivarlo, ya que una vez borrado, toda la información asociada a este médico <strong class="text-danger">se perderá y no podrá recuperarse.</strong></p>
              
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Cancelar</button>
            <a href="../delete/<?= $medico->idmedico ?>" class="btn btn-danger btn-flat">Borrar médico</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content-wrapper -->

<?php require(__DIR__.'/../footer.php') ?>

<!-- own script -->
<script>
  $('#form_edit_user').validate({
    rules: {
      nombre: {
        required: true, 
        maxlength: 50
      },
      email: {
        required: true, 
        maxlength: 100,
        email: true
      },
      password: {
        minlength: <?= $this->config->item('min_password_length', 'ion_auth'); ?>,
        maxlength: <?= $this->config->item('max_password_length', 'ion_auth'); ?>,
      }
    }
  });
</script>