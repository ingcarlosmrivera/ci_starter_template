<?php require('/../header.php') ?>
<?php require('/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Editar Usuarios
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-users"></i> Usuarios</a></li>
        <li class="active">Editar usuario</li>
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
              <h3 class="box-title"><i class="fa fa-pencil"></i> Editar Usuario</h3>
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
                <input type="hidden" name="user_id" id="user_id" value="<?= $user->id ?>">

                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="first_name">Nombre</label>
                      <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Nombre" value="<?= $user->first_name ?>">
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="last_name">Apellido</label>
                      <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Apellido" value="<?= $user->last_name ?>">
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="last_name">Status</label>
                      <select class="form-control" name="status" id="status">
                        <option value="1">Activado</option>
                        <option value="0" <?= (!$user->active) ? 'selected' : '' ?>>Desactivado</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?= $user->email ?>">
                    </div>
                  </div>

                  <div class="clearfix"></div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="password">Cambiar contraseña</label>
                      <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="password_repeat">Repetir nueva contraseña</label>
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
                    <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-check"></i> Guardar cambios</button>
                    <button type="button" class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modal-delete-user"><i class="fa fa-trash"></i> Borrar usuario</button>
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
            <h4 class="modal-title">¿Seguro quieres borrar este usuario?</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="alert alert-danger">
                <h5 class="text-center text-uppercase no-margin">ESTA ACCIÓN NO PUEDE DESHACERSE</h5>
              </div>
            </div>

            <p>Si se require restringir el accesso a un usuario, se recomienda desactivarlo, ya que una vez borrado, toda la información asociada a este usuario <strong class="text-danger">se perderá y no podrá recuperarse.</strong></p>
              
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Cancelar</button>
            <a href="../delete/<?= $user->id ?>" class="btn btn-danger btn-flat">Borrar usuario</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content-wrapper -->

<?php require('/../footer.php') ?>

<!-- own script -->
<script>
  $('#form_edit_user').validate({
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
          url: "/actions/email_check_exclude",
          type: "post",
          data: {
            email: function() {
              return $( "#email" ).val();
            },
            user_id: function() {
              return $( "#user_id" ).val();
            }
          }
        }
      },
      password: {
        minlength: <?= $this->config->item('min_password_length', 'ion_auth'); ?>,
        maxlength: <?= $this->config->item('max_password_length', 'ion_auth'); ?>,
      },
      password_repeat: {
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
  $('#groups').select2({

    initSelection : function (element, callback) {
      
      var data = [];//Array
      <?php foreach ($user->groups as $group): ?>
        data.push({id: '<?= $group->id ?>', text: '<?= $group->name ?>'});//Push values to data array
      <?php endforeach ?>
      
      $('#groups').val(data)

      callback(data); //Fill'em

      // select on select element
      $.each(data, function(i,e){
          $("#groups option[value='" + e.id + "']").prop("selected", true);
      });
    }
  }).on('change', function(e) {
    $('#form_edit_user').valid();
  });
</script>