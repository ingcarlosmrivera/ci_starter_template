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
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Detalle de Factura - <?= $factura->numero_factura ?></h3>
            </div>
            
            <div class="box-body">

              <div class="col-xs-12 col-md-6">
                <label class="no-margin" for="">Fecha de Factura</label>
                <h4 class="no-margin-top"><?= _date($factura->fecha) ?></h4>
              </div>

              <div class="col-xs-12 col-md-6">
                <label class="no-margin" for="">Número de Factura</label>
                <h4 class="no-margin-top"><?= $factura->numero_factura ?></h4>
              </div>

              <div class="col-xs-12 col-md-6">
                <label class="no-margin" for=""># de Pedidos</label>
                <h4 class="no-margin-top"><?= $factura->numero_pedidos ?></h4>
              </div>

              <div class="col-xs-12 col-md-6">
                <label class="no-margin" for="">Total facturado</label>
                <h4 class="no-margin-top">ARS <?= $factura->total_factura ?></h4>
              </div>

              <!-- pedidos -->
              <div class="col-xs-12">
                <h3>Pedidos asociados a la factura</h3>
                <div class="alert alert-info visible-xs">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h4 class="text-center">Deslice la tabla a la izquierda para ver los detalles completos</h4>
                </div>

                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Fecha</th>
                        <th>Candidato</th>
                        <th>DNI</th>
                        <th>Vacante</th>
                        <th>Servicio</th>
                        <th>Solicitante</th>
                        <th class="text-center col-xs-1"><i class="fa fa-cogs"></i></th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php if ($factura->pedidos): ?>
                        <?php foreach ($factura->pedidos as $p): ?>
                          <tr>
                            <td><?= _date($p->creado) ?></td>
                            <td><?= $p->candidato ?></td>
                            <td><?= $p->dni ?></td>
                            <td><?= $p->vacante ?></td>
                            <td><?= $p->servicio ?></td>
                            <td><?= $p->subcliente ?></td>
                            <td class="text-center">
                              <?php if ($p->adjuntos): ?>
                                <?php foreach ($p->adjuntos as $a): ?>
                                  <a href="/uploads/<?= $a->filename ?>" target="_blank">
                                    <span class="label label-primary"><i class="fa fa-file-pdf-o"></i></span>
                                  </a>
                                <?php endforeach ?>
                                  
                                
                              <?php endif ?>
                            </td>
                          </tr>
                        <?php endforeach ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="4" class="text-center">No hay pedidos, debe ser un error</td>
                        </tr>
                      <?php endif ?>
                    </tbody>
                  </table>
                </div>
                  
              </div>
              

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

<?php require('footer.php') ?>

<script>

</script>