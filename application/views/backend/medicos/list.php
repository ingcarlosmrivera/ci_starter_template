<?php require(__DIR__.'/../header.php') ?>
<?php require(__DIR__.'/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Lista de Médicos
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-user"></i> Médicos</a></li>
        <li class="active">Lista de Médicos</li>
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
              <h3 class="box-title"><i class="fa fa-users"></i> Lista de Médicos</h3>
            </div>
            <div class="box-body">
                <div class="row">
                  <div class="col-xs-12 text-right">
                    <a href="add" class="btn btn-success btn-flat margin-bottom-10"><i class="fa fa-user-plus"></i>  Registrar médico</a>
                    <!-- <button type="button" class="btn btn-warning btn-flat margin-bottom-10"><i class="fa fa-file-excel-o"></i>  Exportar XLS</button> -->
                  </div>
                </div>

              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <th style="width: 10px">ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Creado</th>
                    <th class="text-center"><i class="fa fa-cogs"></i></th>
                  </tr>
                  <?php if ($medicos): ?>
                    <?php foreach ($medicos as $medico): ?>
                      <tr>
                        <td><?= $medico->idmedico ?></td>
                        <td><?= ucwords($medico->nombre) ?></td>
                        <td><?= $medico->email ?></td>
                        <td><?= $medico->password ?></td>
                        <td><?= _date( $medico->creado) ?></td>
                        <td class="text-center">
                          <a href="edit/<?= $medico->idmedico ?>" class="btn  btn-primary btn-sm" data-toggle="tooltip" title="Ver/Editar médico">
                            <i class="glyphicon glyphicon-pencil"></i>
                          </a>
                        </td>
                      </tr>
                    <?php endforeach ?>
                  <?php else: ?>
                    <tr>
                      <td  colspan="6">
                        <h5 class="text-center">
                          No se encontraron resultados
                        </h5>
                      </td>

                    </tr>
                  <?php endif ?>
                    
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

<?php require(__DIR__.'/../footer.php') ?>
