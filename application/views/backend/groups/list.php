<?php require('/../header.php') ?>
<?php require('/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Lista de Grupos
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-group"></i> Grupos</a></li>
        <li class="active">Lista de Grupos</li>
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
              <h3 class="box-title"><i class="fa fa-groups"></i> Lista de Grupos</h3>
            </div>
            <div class="box-body">
                <div class="row">
                  <div class="col-xs-12 text-right">
                    <a href="add" class="btn btn-success btn-flat margin-bottom-10"><i class="fa fa-user-plus"></i>  Registrar grupo</a>
                    <!-- <button type="button" class="btn btn-warning btn-flat margin-bottom-10"><i class="fa fa-file-excel-o"></i>  Exportar XLS</button> -->
                  </div>
                </div>

              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <th style="width: 10px">ID</th>
                    <th>Nombre del grupo</th>
                    <th>Descripción</th>
                    <th>Permisos</th>
                    <th># de usuarios</th>
                    <th class="text-center"><i class="fa fa-cogs"></i></th>
                  </tr>
                  
                  <?php foreach ($groups as $group): ?>
                    <tr>
                      <td><?= $group->id ?></td>
                      <td><?= $group->name ?></td>
                      <td><?= $group->description ?></td>
                      <td>
                        <?php if (count($group->permissions > 0)): ?>
                          <?php foreach ($group->permissions as $permission): ?>
                            <label for="" class="label label-<?= get_random_contextual_class() ?>" data-toggle="tooltip" title="<?= $permission->description ?>"><?= $permission->permission ?></label>
                          <?php endforeach ?>
                        <?php else: ?>
                          -
                        <?php endif ?>
                      </td>
                      <td><?= $group->number_users ?></td>
                      <td class="text-center">
                        <a href="edit/<?= $group->id ?>" class="btn  btn-primary btn-sm" data-toggle="tooltip" title="Ver/Editar grupo">
                          <i class="glyphicon glyphicon-pencil"></i>
                        </a>
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
  </div>
  <!-- /.content-wrapper -->

<?php require('/../footer.php') ?>
