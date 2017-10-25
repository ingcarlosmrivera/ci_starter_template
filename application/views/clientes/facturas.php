<?php require('header.php') ?>
<?php require('sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Facturas
        <!-- <small>Resumen de pedidos</small> -->
      </h1>
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
        <?php if ($facturas): ?>
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">Detalles de Facturas</h3>
              </div>
              <div class="box-body">
                <div class="col-xs-12">
                  <div class="alert alert-info visible-xs visible-xs">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4 class="text-center">Deslice la tabla a la izquierda para ver los detalles completos</h4>
                  </div>
                  
                  <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped">
                      <thead>
                        <th>Fecha</th>
                        <th># Factura</th>
                        <th># Pedidos</th>
                        <th>Total facturado</th>
                        <th class="text-center"><i class="fa fa-cogs"></i></th>
                      </thead>
                      <tbody>
                        <?php foreach ($facturas as $p): ?>
                          <tr>
                            <td><?php echo _date($p->fecha) ?></td>
                            <td><?php echo $p->numero_factura ?></td>
                            <td><?php echo $p->numero_pedidos ?></td>
                            <td>ARS <?php echo $p->total_factura ?></td>
                            <td class="text-center"><a href="/clientes/facturas/<?= $p->idfactura ?>" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> Ver detalles</a></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
                  
              </div>

            </div>
            <!-- /.box -->
          </div>
        <?php else: ?>
          <div class="col-xs-12">
            <div class="alert alert-info">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <strong>No tienes facturas emitidas</strong>
            </div>
          </div>
        <?php endif ?>
      </div>
         

    </section>
    <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->

<?php require('footer.php') ?>

<script>

</script>