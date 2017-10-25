<?php require(__DIR__.'/../header.php') ?>
<?php require(__DIR__.'/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Lista de pre-pedidos
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-user"></i> pre-pedidos</a></li>
        <li class="active">Lista de pre-pedidos</li>
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
              <h3 class="box-title"><i class="fa fa-users"></i> Lista de pre-pedidos</h3>
            </div>
            <div class="box-body">

              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Cliente (Subcliente)</th>
                    <th>Candidato</th>
                    <th>DNI</th>
                    <th>Email</th>
                    <th>Vacante</th>
                    <th>Servicio</th>
                    <th class="text-center"><i class="fa fa-cogs"></i></th>
                  </tr>
                </thead>

                <tbody>

                  <?php if ($prepedidos): ?>
                    <?php foreach ($prepedidos as $p): ?>
                      <tr class="tr_servicio" style="cursor: pointer" data-servicio="<?= $p->idprepedido?>">
                        <td><?= $p->idprepedido ?></td>
                        <td><?= _date($p->creado) ?></td>
                        <td><?= sprintf("%s (%s)", $p->cliente, $p->subcliente) ?></td>
                        <td><?= $p->candidato ?></td>
                        <td><?= $p->dni ?></td>
                        <td><?= $p->email ?></td>
                        <td><?= $p->vacante ?></td>
                        <td><?= $p->servicio ?></td>
                        <td class="text-center">
                          <a href="/backend/pedidos/new/<?= $p->idprepedido ?>" class="btn btn-primary btn-sm btn-flat">
                            <i class="fa fa-check"></i> 
                            Finalizar
                          </a>

                          <?php if (!is_null($p->fileurl) && !empty($p->fileurl)): ?>
                            <a href="/uploads/<?= $p->fileurl ?>" target="_blank" class="small block">Descargar adjunto</a>   
                          <?php endif ?>

                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td  colspan="9">
                        <h5 class="text-center">
                          No se encontraron prepedidos
                        </h5>
                      </td>

                    </tr>
                  <?php endif; ?>

                </tbody>
              </table>
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

<script>
  
</script>