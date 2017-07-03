<?php require('/../header.php') ?>
<?php require('/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Registrar Usuarios
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-user"></i> Usuarios</a></li>
        <li class="active">Registrar usuario</li>
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
              <h3 class="box-title"><i class="fa fa-user-plus"></i> Registrar Usuario</h3>
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
                      <label for="first_name">Nombre</label>
                      <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Nombre">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="last_name">Apellido</label>
                      <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Apellido">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                    </div>
                  </div>

                  <div class="clearfix"></div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="password">Contraseña</label>
                      <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="password_repeat">Contraseña</label>
                      <input type="password" class="form-control" id="password_repeat" name="password_repeat" placeholder="Repetir contraseña">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="groups">Seleccionar Grupos</label>
                      <select class="form-control" name="groups[]" id="groups" multiple="multiple">
                        <?php foreach ($groups as $rol): ?>
                          <option value="<?= $rol->id ?>"><?= $rol->name ?></option>
                        <?php endforeach ?>
                      </select>
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

<?php require('/../footer.php') ?>

<!-- own script -->
<script>
    $('#form_add_user').validate({
      rules: {
        first_name: {
          required: true, 
          maxlength: 50
        },
        last_name: {
          required: true, 
          maxlength: 50
        },
        email: {
          required: true, 
          maxlength: 100,
          email: true,
          remote: {
            url: "/actions/email_check",
            type: "post",
            data: {
              email: function() {
                return $( "#email" ).val();
              }
            }
          }
        },
        password: {
          required: true,
          minlength: <?= $this->config->item('min_password_length', 'ion_auth'); ?>,
          maxlength: <?= $this->config->item('max_password_length', 'ion_auth'); ?>,
        },
        password_repeat: {
          required: true,
          minlength: <?= $this->config->item('min_password_length', 'ion_auth'); ?>,
          maxlength: <?= $this->config->item('max_password_length', 'ion_auth'); ?>,
          equalTo: '#password'
        },
        'groups[]': {
          required: true
        }
      },
      messages: {
        email: {
          remote: "This email is already in use."
        }
      }
    });

    // enable select2
    $('#groups').select2().on('change', function(e) {
      $('#form_add_user').valid();
    });;
</script>