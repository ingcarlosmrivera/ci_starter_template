<?php require(__DIR__.'/../header.php') ?>
<?php require(__DIR__.'/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Lista de servicios
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-user"></i> servicios</a></li>
        <li class="active">Lista de servicios</li>
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
              <h3 class="box-title"><i class="fa fa-users"></i> Lista de servicios</h3>
            </div>
            <div class="box-body">
                <div class="row">
                  <form class="form" name="form_search" id="form_search" method="post">
                    <div class="form-group col-sm-3">
                      <label>Buscar por:</label>
                      <select class="form-control" name="buscar_por" id="buscar_por">
                        <option value="codigo" <?= ($buscar_por == 'codigo') ? 'selected' : '' ?>>Código</option>
                        <option value="servicio" <?= ($buscar_por == 'servicio') ? 'selected' : '' ?>>Servicio</option>
                      </select>
                    </div>

                    <div class="form-group col-sm-4" >
                      <label for="">Escribe búsqueda</label>
                      <div class="input-group">
                        <input type="text" name="text" id="text" class="form-control pull-right" placeholder="Buscar" value="<?= $busqueda ?>">
                        <span class="input-group-addon pointer">
                          <span class="fa fa-search "></span>
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

                    <div class="col-sm-3 text-right">
                      <label class="block">&nbsp;</label>
                      <a href="/backend/servicios/add" class="btn btn-success btn-flat margin-bottom-10"><i class="fa fa-plus"></i>  Registrar servicio</a>
                      <!-- <button type="button" class="btn btn-warning btn-flat margin-bottom-10"><i class="fa fa-file-excel-o"></i>  Exportar XLS</button> -->
                    </div>
                  </form>     

                  <div class="col-xs-12">
                    <div class="table-responsive">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Servicio</th>
                            <th class="text-center"><i class="fa fa-cogs"></i></th>
                          </tr>
                        </thead>

                        <tbody>

                          <?php if ($servicios): ?>
                            <?php foreach ($servicios as $c): ?>
                              <tr class="tr_servicio" style="cursor: pointer" data-servicio="<?= $c->idservicio?>">
                                <td><?php echo $c->idservicio ?></td>
                                <td><?php echo $c->codigo ?></td>
                                <td><?php echo $c->servicio ?></td>
                                <td class="text-center">
                                  <a href="/backend/servicios/edit/<?= $c->idservicio ?>" class="btn btn-info btn-sm btn-flat">
                                    <i class="fa fa-pencil"></i>
                                  </a>
                                  <button class="btn btn-danger btn-sm btn-flat delete" data-idservicio="<?= $c->idservicio ?>">
                                    <i class="fa fa-close"></i>
                                  </button>
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
                  </div>                   
                </div>

                      
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

<script>

  $('.delete').on('click', function(event) {
    event.preventDefault();
    var idservicio = $(this).data('idservicio');

    var c = confirm("¿Realmente desea eliminar este servicio? Si existen pedidos asociados al mismo, no se mostrarán correctamente");

    if (c) {
      $.ajax({
        url: '/actions/delete_servicio',
        type: 'POST',
        dataType: 'JSON)',
        data: {idservicio: idservicio},
      })
      .done(function() {
        location.reload();
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
      
    }
  }); 
  
</script>