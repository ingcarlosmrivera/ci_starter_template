<?php require(__DIR__.'/../header.php') ?>
<?php require(__DIR__.'/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Consulta de pedidos por Proveedor
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-line-chart"></i> Facturación</a></li>
        <li class="active">Consultas</li>
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
              <h3 class="box-title">Lista de Facturas</h3>
            </div>
            <div class="box-body">
                <div class="row">
                  <form class="form" name="form_search" id="form_search" method="post">
                    <div class="form-group col-sm-3">
                      <label>Seleccionar Proveedor:</label>
                      <select class="form-control" name="id_proveedor" id="id_proveedor">
                        
                      </select>
                    </div>

                    <div class="form-group col-sm-2" >
                      <label for="">Desde</label>
                      <div class="input-group">
                        <input type="text" name="text" id="text" class="form-control pull-right" placeholder="Desde" value="<?= $fecha1 ?>">
                        <span class="input-group-addon pointer">
                          <span class="fa fa-calendar "></span>
                        </span>
                      </div>
                    </div>

                    <div class="form-group col-sm-2" >
                      <label for="">Hasta</label>
                      <div class="input-group">
                        <input type="text" name="text2" id="text2" class="form-control pull-right" placeholder="Hasta" value="<?= $fecha2 ?>">
                        <span class="input-group-addon pointer">
                          <span class="fa fa-calendar "></span>
                        </span>
                      </div>
                    </div>

                    <div class="form-group col-sm-2">
                      <label class="block">&nbsp;</label>
                      <button type="submit" class="btn btn-primary btn-flat">
                        <i class="fa fa-search"></i> 
                        Buscar
                      </button>
                    </div>

                  </form>                        
                </div>

              <?php if ($proveedor): ?>
                <div class="alert alert-success">
                  <h4 class="no-margin">Datos encontrados para el proveedor "<b><?= $proveedor->razon ?></b>"</h4>
                </div>
                  
              <?php endif ?>

              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Servicio</th>
                    <th>Candidato</th>
                    <th class="text-center">Tiene OC</th>
                    <th>Costo</th>
                  </tr>
                </thead>

                <tbody>

                  <?php if ($pedidos): ?>
                    <?php $total = 0; ?>
                    <?php foreach ($pedidos as $c): ?>
                      <tr>
                        <td><?php echo _date($c->creado) ?></td>
                        <td><?php echo $c->cliente ?></td>
                        <td><?php echo $c->servicio ?></td>
                        <td><?php echo $c->candidato ?></td>
                        <td class="text-center">
                          <?php if ($c->id_orden): ?>
                            <i class="fa fa-check text-success"></i>
                          <?php else: ?>
                            <i class="fa fa-close text-danger"></i>
                          <?php endif ?>
                        </td>
                        <td>ARS <?php echo $c->costo; $total += $c->costo; ?></td>
                      </tr>
                    <?php endforeach; ?>

                    <tr>
                      <td colspan="6" class="text-right">
                        Total costo: <b>ARS <?= $total ?></b>
                      </td>
                    </tr>
                  <?php else: ?>
                    <tr>
                      <td  colspan="5">
                        <h5 class="text-center">
                          No se encontraron resultados
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
  $("#id_proveedor").select2({
    theme: "bootstrap",
    placeholder: "Escriba y seleccione",
    tags: false,
    multiple: false,
    ajax: {
      url: function (params) {
        return '/actions/search_proveedores/' + params.term;
      },
      dataType: "json",
      type: "POST",
      cache: false,
      minimumInputLength: 1,
      processResults: function (data) {
        var results = [];
        $.each(data, function (index, item) {
            results.push({
                id: item.idproveedor,
                text: item.proveedor
            });
        });

        return {
            results: results
        };
      }
    }
  });

  $('#text, #text2').datepicker({
    format: 'yyyy-mm-dd'
  });
</script>