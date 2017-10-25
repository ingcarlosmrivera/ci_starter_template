<?php require('header.php') ?>
<?php require('sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Registrar Pedido
        <!-- <small>Optional description</small> -->
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
              <h3 class="box-title"><i class="fa fa-users"></i> Registrar Pedido</h3>
            </div>
            
            <form class="form" name="form_registrar_pedido" id="form_registrar_pedido" method="post">
              <div class="box-body">
                <div class="row">
                  <div class="col-xs-12 col-md-2 form-group">
                    <label for="servicio">Fecha</label>
                    <input type="text" class="form-control" value="<?= date('d/m/Y') ?>" readonly>
                  </div>

                  <div class="col-xs-12 col-md-10 form-group">
                    <label for="cliente">Cliente</label>
                    <select name="id_cliente" id="id_cliente" class="form-control">
                      <?php foreach ($clientes as $cliente): ?>
                        <option value="<?= $cliente->idcliente ?>" data-forzar-oc="<?= $cliente->forzar_oc ?>"><?= $cliente->razon ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>

                  <div class="col-xs-12 form-group no-margin-bottom collapse div_input_oc">
                    <div class="alert alert-warning">
                      <label for="servicio">Los pedidos para este cliente requieren Orden de Compra autorizada. Por favor, coloque la orden de compra en el siguiente campo:</label>
                      <input type="text" class="form-control ignore" name="oc" id="oc" placeholder="Orden de Compra">
                    </div>
                      
                  </div>

                  <div class="col-xs-12 form-group">
                    <label for="servicio">Servicio</label>
                    <select name="servicio" id="servicio" class="form-control">
                      
                    </select>
                  </div>

                  <div class="col-xs-12 col-md-6 form-group">
                    <label for="candidato">Candidato</label>
                    <input type="text" class="form-control" placeholder="Candidato" name="candidato" id="candidato">
                  </div>

                  <div class="col-xs-12 col-md-3 form-group">
                    <label for="dni">DNI</label>
                    <input type="text" class="form-control" placeholder="DNI" name="dni" id="dni">
                  </div>

                  <div class="col-xs-12 col-md-3 form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" class="form-control" placeholder="Teléfono" name="telefono" id="telefono">
                  </div>

                  <div class="col-xs-12 col-md-3 form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" placeholder="Email" name="email" id="email">
                  </div>

                  <div class="col-xs-12 col-md-3 form-group">
                    <label for="vacante">Vacante</label>
                    <input type="text" class="form-control" placeholder="Vacante" name="vacante" id="vacante">
                  </div>

                  <div class="col-xs-12 col-md-6 form-group">
                    <label for="dirección">Dirección</label>
                    <input type="text" class="form-control" placeholder="Dirección" name="direccion" id="direccion">
                  </div>

                  <div class="clearfix"></div>

                  <div class="col-xs-12 col-md-6 form-group">
                    <label>Provincia</label>
                    <select name="id_provincia" id="id_provincia" class="form-control">
                      <option value="">Seleccione...</option>
                      <?php foreach ($provincias as $p): ?>
                        <option value="<?= $p->idprovincia ?>"><?= $p->provincia ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>

                  <div class="col-xs-12 col-md-6 form-group">
                    <label>Localidad</label>
                    <select name="id_localidad" id="id_localidad" class="form-control">
                      <option value="">Seleccione...</option>
                    </select>
                  </div>

                  <div class="col-xs-12 col-md-12 form-group">
                    <label for="observaciones">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" rows="2" class="form-control full-width"></textarea>
                  </div>

                  <div class="col-xs-12">
                    <div class="form-group">
                      <label for="">Adjuntar archivo</label>
                      <div class="checkbox">
                        <label><input type="checkbox" name="a" id="a"></label>
                      </div>
                    </div>
                    <div class="dropzone form-group collapse">

                    </div>
                    <div class="form-group collapse">
                      <input type="hidden" class="fileurl" name="fileurl" id="fileurl">
                    </div>
                  </div>

                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer text-right">
                <button type="button" class="btn btn-default btn-flat">Cancelar</button>
                <button type="submit" class="btn btn-success btn-flat">Registrar Pedido</button>
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

<?php require('footer.php') ?>

<script>
  $('#id_cliente').change(function(event) {
    var forzar_oc = $('#id_cliente option:selected').data('forzar-oc');

    if (forzar_oc) {
      $('.div_input_oc').show('fast');
      $('#oc').removeClass('ignore');
    } else {
      $('.div_input_oc').hide('fast');
      $('#oc').addClass('ignore');
    }
  });
  $('#id_cliente').trigger('change');

  $('#a').change(function(event) {
    ($(this).is(':checked')) ? $('.dropzone').show() : $('.dropzone').hide()
  });

  $(".dropzone").dropzone({
    url: "/actions/upload_file",
    maxFiles: 1,
    addRemoveLinks: true,
    dictDefaultMessage: "Arrastre o seleccione para cargar archivo",
    init: function () {
      this.on("success", function (file, response) {
          if (response.status == "success") {
            var filename = response.filename;
            $('#fileurl').val(filename)
            $('#form_registrar_pedido').valid();
            return;
          } else {
            alert(response.error)
          }
         // este punto no debe alcanzarse. si lo hace, hubo error.
         this.removeFile(file);
      }),
      this.on("error", function (file, message) {
        alert(message)
        this.removeFile(file);
      }),
      this.on("removedfile", function (file) {
        // delete file on servicio_registrado
        $('#fileurl').val('')
        console.log('file removed');
      });
    }
  });

  $("#servicio").select2({
    theme: "bootstrap",
    allowClear: true,
    placeholder: "Escriba y seleccione",
    tags: false,
    multiple: false,
    ajax: {
      url: function (params) {
        return '/actions/search_servicios/' + $('#id_cliente').val() + '/' + params.term;
      },
      dataType: "json",
      type: "POST",
      cache: false,
      minimumInputLength: 1,
      processResults: function (data) {
        var results = [];
        $.each(data, function (index, item) {
            results.push({
                id: item.idservicio,
                text: item.servicio
            });
        });

        return {
            results: results
        };
      }
    }
  })  




  $("#id_provincia").on('change', function(event) {
    event.preventDefault();
    var p = $(this).val();
    $("#id_localidad").empty()

    if (p !== "") {
      deferred = $.getJSON('/actions/get_localidades/' + p, function(localidades, textStatus) {
          $.each(localidades, function(index, l) {
            $("#id_localidad").append('<option value="' + l.idlocalidad + '">' + l.localidad + '</option>')
          });
      });
    }
  });

  $('#form_registrar_pedido').validate({
    ignore: ".ignore",
    rules: {
      id_cliente: {
        required: true
      },
      servicio: {
        required: true
      },
      vacante: {
        required: true
      }, 
      oc: {
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
          $('#form_registrar_pedido').trigger("reset");
          $("#modal_result").on('hide.bs.modal', function () {
            location.assign('/clientes/pedido');
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

   <?php if (isset($prepedido)): ?>

    // init selects
    var cliente    = '<?= $prepedido->id_cliente ?>';
   
    var provincia  = '<?= $prepedido->id_provincia ?>';
    var localidad  = '<?= $prepedido->id_localidad ?>';

    $('#id_cliente').val(cliente);
    $('#id_provincia').val(provincia).trigger('change');
    
    if (typeof deferred !==  'undefined') {
      $.when(deferred).then(function() {
        $('#id_localidad').val(localidad);
      });
    }

    // populate data
    $('#candidato').val('<?= $prepedido->candidato ?>')
    $('#dni').val('<?= $prepedido->dni ?>')
    $('#telefono').val('<?= $prepedido->telefono ?>')
    $('#email').val('<?= $prepedido->email ?>')
    $('#vacante').val('<?= $prepedido->vacante ?>')
    $('#direccion').val('<?= $prepedido->direccion ?>')
    <?php $string = str_replace(array("\r\n", "\r", "\n"), " - ", $prepedido->observaciones); ?>
    $('#observaciones').html('<?= $string ?>')
  <?php endif ?>


</script>