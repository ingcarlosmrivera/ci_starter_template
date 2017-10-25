<?php require(__DIR__.'/../header.php') ?>
<?php require(__DIR__.'/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Historial de Gastos
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-line-chart"></i> Facturación</a></li>
        <li class="active">Gastos</li>
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
              <h3 class="box-title">Lista de Gastos</h3>
            </div>
            <div class="box-body">

              <div class="row">
                <div class="col-xs-12 text-right">
                  <button class="btn btn-success btn-flat" data-toggle="modal" data-target="#modal-gasto"><i class="fa fa-plus"></i> Cargar nuevo gasto</button>
                </div>
              </div>

              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Fecha carga</th>
                    <th>Fecha gasto</th>
                    <th>Concepto</th>
                    <th>Total</th>
                    <th class="text-center"><i class="fa fa-cogs"></i></th>
                  </tr>
                </thead>

                <tbody>

                  <?php if ($gastos): ?>
                    <?php foreach ($gastos as $g): ?>
                      <tr>
                        <td><?php echo _date($g->creado) ?></td>
                        <td><?php echo _date($g->fecha) ?></td>
                        <td><?php echo $g->concepto ?></td>
                        <td>ARS <?php echo $g->total ?></td>
                        <td class="text-center">
                          <?php if ($g->adjunto): ?>
                            <a href="<?= $g->adjunto ?>" target='_blank' class="btn btn-info btn-sm btn-flat">
                              <i class="fa fa-file-pdf-o"></i> 
                              Ver adjunto
                            </a>
                          <?php else: ?>
                            -
                          <?php endif ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td  colspan="7">
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
            <div class="box-footer clearfix">
              <?php echo $this->pagination->create_links() ?>
            </div>
          </div>
          <!-- /.box -->
        </div>
      </div>

      <div class="modal fade" id="modal-gasto">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Nuevo gasto</h4>
            </div>
            <form name="form_gasto" id="form_gasto" class="form" method="POST" enctype="multipart/form-data"> 
              <div class="modal-body">
                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="">Fecha de carga</label>
                    <input type="text" class="form-control" value="<?= date('d/m/Y') ?>" readonly>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="">Fecha de Gasto</label>
                    <input type="text" class="form-control" id="picker">
                    <input type="hidden" name="fecha" id="fecha">
                  </div>

                  <div class="form-group col-xs-12 col-md-9">
                    <label for="">Concepto</label>
                    <input type="text" class="form-control" name="concepto" id="concepto" placeholder="Concepto de factura">
                  </div>

                  <div class="form-group col-xs-12 col-md-3">
                    <label for="">Total</label>
                    <input type="text" class="form-control" name="total" id="total" placeholder="Total factura">
                  </div>

                  <div class="form-group col-xs-12">
                    <label for="exampleInputFile">Adjuntar archivo</label>
                    <input type="file" id="file" name="file">
                  </div>

                  <div class="form-group col-xs-12">
                    <label for="">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" rows="2" class="form-control full-width"></textarea>
                  </div>
                </div>
                  
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
              </div>
            </form>
              
          </div>
        </div>
      </div>

    </section>
    <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->

<?php require(__DIR__.'/../footer.php') ?>

<script>
  var start = moment();

  function cb(start) {
      $('#reportrange span').html(start.format('DD/MM/YYYY'));
      start = start.format('YYYY-MM-DD HH:mm:ss');

      $('#fecha').val(start)
  }

  $('#picker').daterangepicker({
     singleDatePicker: true,
     "autoApply": true,
      locale: {
        format: 'DD/MM/YYYY'
      },
      startDate: start,
      ranges: {
         'Hoy': [moment().startOf('day'), moment().endOf('day')],
         'Ayer': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
      }
  }, cb);

  cb(start);

  $('#form_gasto').validate({
    ignore: "",
    rules: {
      fecha: {
        required: true
      },
      concepto: {
        required: true,
        minlength: 5
      },
      total: {
        required: true,
        number: true
      }
    }, 
    submitHandler: function(form)
    {
      return true;
    }
  })
</script>