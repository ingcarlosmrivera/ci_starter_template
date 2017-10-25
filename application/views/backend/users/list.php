<?php require(__DIR__.'/../header.php') ?>
<?php require(__DIR__.'/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Lista de Usuarios
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-user"></i> Usuarios</a></li>
        <li class="active">Lista de Usuarios</li>
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
              <h3 class="box-title"><i class="fa fa-users"></i> Lista de Usuarios</h3>
            </div>
            <div class="box-body">
                <div class="row">
                  <div class="col-xs-12 text-right">
                    <a href="add" class="btn btn-success btn-flat margin-bottom-10"><i class="fa fa-user-plus"></i>  Registrar usuario</a>
                    <!-- <button type="button" class="btn btn-warning btn-flat margin-bottom-10"><i class="fa fa-file-excel-o"></i>  Exportar XLS</button> -->
                  </div>
                </div>

              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <th style="width: 10px">ID</th>
                    <th>Username</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Creado</th>
                    <th>Último Login</th>
                    <th class="text-center">Status</th>
                    <th>Grupos</th>
                    <th class="text-center"><i class="fa fa-cogs"></i></th>
                  </tr>
                  
                  <?php foreach ($users as $user): ?>
                    <tr>
                      <td><?= $user->id ?></td>
                      <td><?= $user->username ?></td>
                      <td><?= sprintf("%s %s", $user->first_name, $user->last_name) ?></td>
                      <td><?= $user->email ?></td>
                      <td><?= date_( $user->created_on) ?></td>
                      <td><?= date_($user->last_login) ?></td>
                      <td class="text-center"><?= ($user->active) ? "<a href='status/{$user->id}'><span class='fa fa-check text-success'></span></a>" : "<a href='status/{$user->id}'><span class='fa fa-close text-danger'></span></a>" ?></td>
                      <td>
                        <?php foreach ($user->groups as $group): ?>
                          <a href="#" class="label label-flat label-<?= get_random_contextual_class() ?>" data-toggle="tooltip" title="<?= $group->description ?>"><?= strtoupper(substr($group->name, 0, 1)) ?></a>
                        <?php endforeach ?>
                      </td>
                      <td class="text-center">
                        <a href="edit/<?= $user->id ?>" class="btn  btn-primary btn-sm" data-toggle="tooltip" title="Ver/Editar usuario">
                          <i class="glyphicon glyphicon-pencil"></i>
                        </a>

                        <button class="btn btn-info btn-sm restore-password" data-email="<?= $user->email ?>" data-toggle="tooltip" title="Restablecer contraseña">
                          <i class="glyphicon glyphicon-envelope"></i>
                        </button>
                      </td>
                    </tr>
                  <?php endforeach ?>
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

    </section>
    <!-- /.content -->

    <div class="modal fade" id="modal-restore-password">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Restablecer contraseña</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="alert alert-info">
                <h5 class="text-center no-margin">Ha solicitado iniciar el procesa para restablecer la contraseña a <span class="restore-email"></span>. Esto enviará al usuario un email con la información necesaria para restablecer su cntraseña de acceso.</h5>
              </div>
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control" name="restore-email" id="restore-email" readonly="readonly">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-success" onclick="restore_password()">Restablecer contraseña</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content-wrapper -->

<?php require(__DIR__.'/../footer.php') ?>

<script>
  $('.restore-password').on('click', function(e) {
    e.preventDefault();
    var email = $(this).data('email');
    
    $('#modal-restore-password').modal();
    $('.restore-email').html(email);
    $('#restore-email').val(email);
  });

  var restore_password = function (email) {
    $.ajax({
      url: '/backend/actions/restore_password',
      type: 'POST',
      dataType: 'JSON',
      data: {email: $('#restore-email').val()},
      beforeSend: function() {
        $('#modal-restore-password').modal('hide');
      }
    })
    .done(function(response) {
      // estas alertas se reemplazaran por un plugin, quizá izitoast
      if(response.status == 'success') {
        alert('exito');
      } else {
        alert('fail');
      }
    })
    .fail(function() {
      alert('ocurrió un error');
    })
    
  }
</script>