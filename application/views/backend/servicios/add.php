<?php require(__DIR__.'/../header.php') ?>
<?php require(__DIR__.'/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Registrar Servicio
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-user"></i> Servicios</a></li>
        <li class="active">Registrar Servicio</li>
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
              <h3 class="box-title"><i class="fa fa-users"></i> Registrar Servicio</h3>
            </div>
            
            <form class="form" name="form_registrar_servicio" id="form_registrar_servicio" method="post">
              <div class="box-body">
                <div class="row">
                  <div class="col-xs-12 form-group">
                    <label for="servicio">Servicio</label>
                    <input type="text" class="form-control" placeholder="Razón social" name="servicio" id="servicio">
                  </div>

                  <div class="col-xs-12 col-md-6 form-group">
                    <label for="cuit">Código</label>
                    <input type="text" class="form-control" placeholder="Código" name="codigo" id="codigo">
                  </div>

                  <div class="col-xs-12 col-md-6 form-group">
                    <label for="cuit">Servicio médico</label>
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" value="1" name="is_medical" id="is_medical">
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer text-right">
                <button type="button" class="btn btn-default btn-flat">Cancelar</button>
                <button type="submit" class="btn btn-success btn-flat">Registrar Servicio</button>
              </div>
            </form>

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
  $('#form_registrar_servicio').validate({
      rules: {
        servicio: {
          required: true,
          minlength: 5
        },
        codigo: {
          required: true
        }
      },
      submitHandler(form) {
        var data = $(form).serialize();
        $.ajax({
          type: 'POST',
          dataType: 'JSON',
          data: data
        })
        .done(function(data) {
          $('#modal_result').removeClass().addClass(data.class);
          $('#modal_result_icon').removeClass().addClass(data.icon);
          $('#modal_result_message').html(data.message);
          $('#modal_result').modal();

          if (data.status == 'success') {
            $('#form_registrar_servicio').trigger("reset");
            $("#modal_result").on('hide.bs.modal', function () {
              location.assign('/backend/servicios/list');
            });
          }

          console.log("success");
        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });
        return false;
      }
    })
</script>