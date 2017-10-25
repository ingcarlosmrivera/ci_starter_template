<?php require(__DIR__.'/../header.php') ?>
<?php require(__DIR__.'/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Registrar Médicos
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-user"></i> Médicos</a></li>
        <li class="active">Registrar Médico</li>
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
              <h3 class="box-title"><i class="fa fa-user-plus"></i> Registrar Médico</h3>
            </div>
            <div class="box-body">
              <!-- validation errors, if case that client side validation fails -->
              <?php if (validation_errors()): ?>
                <ul class="list list-unstyled margin-bottom-10">
                  <li><h4 class="text-red">Some errors were found:</h4></li>
                  <?= validation_errors("<li class='error'>", '</li>') ?>
                </ul>
              <?php endif ?>

              <form method="post" name="form_add_user" id="form_add_user" class="form" role="form">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="nombre">Nombre</label>
                      <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                    </div>
                  </div>


                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="password">Contraseña</label>
                      <input type="text" class="form-control" id="password" name="password" placeholder="Contraseña">
                    </div>
                  </div>

                </div>

                <div class="row">
                  <div class="col-xs-12 text-right">
                    <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-check"></i> Finalizar registro</button>
                    <button type="reset" class="btn btn-danger btn-flat"><i class="fa fa-close"></i> Limpiar datos</button>
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
  </div>
  <!-- /.content-wrapper -->

<?php require(__DIR__.'/../footer.php') ?>

<!-- own script -->
<script>
    $('#form_add_user').validate({
      rules: {
        nombre: {
          required: true, 
          maxlength: 50
        },
        email: {
          required: true, 
          maxlength: 50,
          email: true,
        },
        password: {
          required: true,
          minlength: 5,
          maxlength: 20,
        }
      }
    });
</script>