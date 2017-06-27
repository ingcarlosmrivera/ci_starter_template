<?php require('/../header.php') ?>
<?php require('/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Editar Grupos
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-users"></i> Grupos</a></li>
        <li class="active">Editar grupo</li>
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
              <h3 class="box-title"><i class="fa fa-user-plus"></i> Editar Grupo (<?= $group->name ?>)</h3>
              <div class="box-tools">
                <span class="fa fa-users"></span> <?= $group->number_users ?> Usuarios asignados
              </div>
            </div>
            <div class="box-body">
              <!-- validation errors, if case that client side validation fails -->
              <?php if (validation_errors()): ?>
                <ul class="list list-unstyled margin-bottom-10">
                  <li><h4 class="text-red">Some errors were found:</h4></li>
                  <?= validation_errors("<li class='error'>", '</li>') ?>
                </ul>
              <?php endif ?>

              <form method="post" name="form_add_group" id="form_add_group" class="form" role="form">
                <!-- groupid is required for changes -->
                <input type="hidden" name="group_id" id="group_id" value="<?= $group->id ?>">

                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="name">Nombre</label>
                      <input type="text" class="form-control" id="name" name="name" placeholder="Nombre del grupo" value="<?= $group->name ?>">
                    </div>
                  </div>

                  <div class="col-md-8">
                    <div class="form-group">
                      <label for="description">Descripción</label>
                      <input type="text" class="form-control" id="description" name="description" placeholder="Descripción del grupo" value="<?= $group->description ?>">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-xs-12 text-right">
                    <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-check"></i> Finalizar registro</button>
                    <button type="button" class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modal-delete-user"><i class="fa fa-trash"></i> Borrar grupo</button>
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
            <h4 class="modal-title">¿Seguro quieres borrar este grupo?</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="alert alert-danger">
                <h5 class="text-center text-uppercase no-margin">ESTA ACCIÓN NO PUEDE DESHACERSE</h5>
              </div>
            </div>

            <p>Toda la información asociada a este grupo <strong class="text-danger">se perderá y no podrá recuperarse.</strong></p>
              
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Cancelar</button>
            <a href="../delete/<?= $group->id ?>" class="btn btn-danger btn-flat">Borrar grupo</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content-wrapper -->

<?php require('/../footer.php') ?>

<!-- own script -->
<script>
    $('#form_add_group').validate({
      rules: {
        name: {
          required: true, 
          maxlength: 20
        },
        description: {
          required: true, 
          maxlength: 100
        }
      }
    });
</script>