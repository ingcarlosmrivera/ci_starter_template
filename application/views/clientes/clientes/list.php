<?php require(__DIR__.'/../header.php') ?>
<?php require(__DIR__.'/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Lista de Clientes
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-user"></i> Clientes</a></li>
        <li class="active">Lista de Clientes</li>
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
              <h3 class="box-title"><i class="fa fa-users"></i> Lista de Clientes</h3>
            </div>
            <div class="box-body">
                <div class="row">
                  <form class="form" name="form_search" id="form_search" method="post">
                    <div class="form-group col-sm-3">
                      <label>Buscar por:</label>
                      <select class="form-control" name="buscar_por" id="buscar_por">
                        <option value="razon" <?= ($buscar_por == 'razon') ? 'selected' : '' ?>>Razón Social</option>
                        <option value="cuit" <?= ($buscar_por == 'cuit') ? 'selected' : '' ?>>CUIT</option>
                        <option value="codigo" <?= ($buscar_por == 'codigo') ? 'selected' : '' ?>>Código</option>
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
                      <a href="/backend/clientes/add" class="btn btn-success btn-flat margin-bottom-10"><i class="fa fa-user-plus"></i>  Registrar cliente</a>
                      <!-- <button type="button" class="btn btn-warning btn-flat margin-bottom-10"><i class="fa fa-file-excel-o"></i>  Exportar XLS</button> -->
                    </div>
                  </form>                        
                </div>

              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Razón Social</th>
                    <th>CUIT</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Código</th>
                    <th class="text-center"><i class="fa fa-cogs"></i></th>
                  </tr>
                </thead>

                <tbody>

                  <?php if ($clientes): ?>
                    <?php foreach ($clientes as $c): ?>
                      <tr class="tr_cliente" style="cursor: pointer" data-cliente="<?= $c->idcliente?>">
                        <td><?php echo $c->razon ?></td>
                        <td><?php echo $c->cuit ?></td>
                        <td><?php echo $c->direccion ?></td>
                        <td><?php echo $c->telefono ?></td>
                        <td><?php echo $c->email ?></td>
                        <td><?php echo $c->codigo ?></td>
                        <td class="text-center">
                          <a href="/backend/clientes/view/<?= $c->idcliente ?>" class="btn btn-info btn-sm btn-flat">
                            <i class="fa fa-eye"></i> 
                            Ver cliente
                          </a>
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

    </section>
    <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->

<?php require(__DIR__.'/../footer.php') ?>

<script>
  
</script>