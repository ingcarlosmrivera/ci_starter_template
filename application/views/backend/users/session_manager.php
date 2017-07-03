<?php require('/../header.php') ?>
<?php require('/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Lista de Sesiones
        <small>De más reciente a más antigua</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-users"></i> Usuarios</a></li>
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
              <h3 class="box-title"><i class="fa fa-users"></i> Lista de sesiones</h3>
            </div>
            <div class="box-body">
                <div class="row">
                  <div class="col-xs-12 text-right">
                    <!-- <button type="button" class="btn btn-warning btn-flat margin-bottom-10"><i class="fa fa-file-excel-o"></i>  Exportar XLS</button> -->
                  </div>
                </div>

              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <th>IP</th>
                    <th>Timestamp</th>
                    <th>Usuario</th>
                    <th>Last check</th>
                    <th>Navegador</th>
                    <th class="text-center">S.O</th>
                    <th class="text-center"><i class="fa fa-cogs"></i></th>
                  </tr>
                  
                  <?php foreach ($sessions as $session): ?>
                    <tr>
                      <td><?= $session->ip_address ?></td>
                      <td><?= date_($session->timestamp) ?></td>
                      <td><?= (isset($session->userdata['email'])) ? $session->userdata['email'] : '';?></td>
                      <td><?= (isset($session->userdata['last_check'])) ? $session->userdata['last_check'] : ''; ?></td>
                      <td><?= (isset($session->userdata['browser'])) ? $session->userdata['browser'] : ''; ?></td>
                      <td><?= (isset($session->userdata['platform'])) ? $session->userdata['platform'] : ''; ?></td>
                      <td class="text-center">
                        <a href="session_manager/<?= $session->id ?>" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-close"></i> Kick</a>
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

<?php require('/../footer.php') ?>

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