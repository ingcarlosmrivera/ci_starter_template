<?php require(__DIR__.'/../header.php') ?>
<?php require(__DIR__.'/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Registrar Pedido
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-user"></i> Pedidos</a></li>
        <li class="active">Registrar Pedido</li>
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
              <h3 class="box-title"><i class="fa fa-users"></i> Registrar Pedido</h3>
            </div>
            
            <form class="form" name="form_registrar_pedido" id="form_registrar_pedido" method="post">
              <div class="box-body">

                <div class="row">
                  <div class="col-xs-12 col-md-2 form-group">
                    <label for="servicio">Fecha</label>
                    <input type="text" class="form-control" id="fecha" readonly>
                    <input type="hidden" name="idpedido" id="idpedido" value="<?= $pedido->idpedido ?>">
                  </div>

                  <div class="col-xs-12 col-md-5 form-group">
                    <label for="cliente">Cliente</label>
                    <select name="cliente" id="cliente" class="form-control">
                      
                    </select>
                  </div>

                  <div class="col-xs-12 col-md-5 form-group">
                    <label for="subcliente">Subcliente</label>
                    <select name="subcliente" id="subcliente" class="form-control">
                      
                    </select>
                  </div>

                  <div class="col-xs-12 form-group">
                    <label for="servicio">Servicio</label>
                    <select name="servicio" id="servicio" class="form-control">
                      
                    </select>
                  </div>

                  <div class="col-xs-12 col-md-6 form-group">
                    <label for="proveedor">Proveedor</label>
                    <select name="proveedor" id="proveedor" class="form-control">
                      
                    </select>
                  </div>

                  <div class="col-xs-12 col-md-6 form-group">
                    <label for="analista">Analista</label>
                    <select name="analista" id="analista" class="form-control">
                      
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

                  <div class="col-xs-12 col-md-4 form-group">
                    <label for="costo">Costo del servicio <span class="badge" data-toggle="tooltip" title="El costo está definido en el servicio mismo. Este valor no puede ser modificado directamente.">?</span></label>
                    <input type="text" class="form-control" placeholder="Costo del servicio" name="costo" id="costo">
                  </div>

                  <div class="col-xs-12 col-md-4 form-group">
                    <label for="precio">Precio del servicio <span class="badge" data-toggle="tooltip" title="El precio es asignado a cada cliente al relacionarle un servicio">?</span></label>
                    <input type="text" class="form-control" placeholder="Precio del servicio" name="precio" id="precio">
                  </div>

                  <?php if ($pedido->requiere_oc): ?>
                    <div class="col-xs-12 col-md-4 form-group">
                      <label for="oc">Orden de compra <span class="badge" data-toggle="tooltip" title="Este pedido no podrá ser facturado hasta no asignarle una orden de compra">?</span></label>
                      <input type="text" class="form-control" placeholder="Orden de compra" name="oc" id="oc" value="<?= $pedido->oc ?>">
                    </div>
                  <?php else: ?>
                    <div class="col-xs-12 col-md-4 form-group">
                      <label for="oc">&nbsp;</label>
                      <p>Este pedido no requiere Orden de compra</p>
                    </div>
                  <?php endif ?>

                  <div class="col-xs-12 col-md-12 form-group">
                    <label for="observaciones">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" rows="2" class="form-control full-width"></textarea>
                  </div>

                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer text-right">
                <button type="button" class="btn btn-default btn-flat">Cancelar</button>
                <button type="submit" class="btn btn-success btn-flat">Guardar Cambios</button>
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

  $("#servicio").select2({
    theme: "bootstrap",
    allowClear: true,
    placeholder: "Escriba y seleccione",
    tags: false,
    multiple: false,
    ajax: {
      url: function (params) {
        return '/actions/search_servicios/' + $('#cliente').val() + '/' + params.term;
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
                text: item.servicio,
                costo:item.costo
            });
        });

        return {
            results: results
        };
      }
    }
  })

  $("#proveedor, #analista").select2({
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

  $('#servicio').on('change', function(event) {
    event.preventDefault();
    $('#costo').val('0')

    if ($('#proveedor').is(':disabled')) {
      $('#proveedor').prop('disabled', false);
      return;
    }
    

    var id = $('#servicio').val();
    var idproveedor = $('#proveedor').val();
    $.ajax({
      url: '/actions/get_costo_servicio/' + idproveedor + '/' + id,
      dataType: 'JSON'
    })
    .done(function(data) {
      if (data) {
        $('#costo').val(data.costo)
        iziToast.success({
            title: 'Costo obtenido',
            message: 'Costo obtenido para el servicio y proveedor seleccionado.',
        });
      } else {
        iziToast.error({
            title: 'Error',
            message: 'No se encontró el costo del servicio con el proveedor seleccionado.',
        });
      }
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    }); 
  });

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
  
    // init values
    var pedido = <?= json_encode($pedido) ?>;

    console.log(pedido)
    var servicio   = $('<option selected><?= $pedido->servicio ?></option>').val('<?= $pedido->id_servicio ?>');
    var cliente    = $('<option selected><?= $pedido->cliente ?></option>').val('<?= $pedido->id_cliente ?>');
    var proveedor  = $('<option selected><?= $pedido->proveedor ?></option>').val('<?= $pedido->id_proveedor ?>');
    var analista   = $('<option selected><?= $pedido->analista ?></option>').val('<?= $pedido->id_analista ?>');
    var subcliente = $('<option selected><?= $pedido->subcliente ?></option>').val('<?= $pedido->id_subcliente ?>');
    var provincia  = pedido.id_provincia;
    var localidad  = pedido.id_localidad;



    $('#proveedor').append(proveedor).trigger('change');
    $('#analista').append(analista).trigger('change');
    $('#servicio').append(servicio);
    $('#cliente').append(cliente).trigger('change');
    $('#subcliente').append(subcliente).trigger('change');
    $('#id_provincia').val(provincia).trigger('change');

    $('#fecha').val('<?= _date($pedido->creado) ?>')
    
    if (typeof deferred !==  'undefined') {
      $.when(deferred).then(function() {
        $('#id_localidad').val(localidad);
      });
    }

    // populate data
    $('#candidato').val(pedido.candidato)
    $('#dni').val(pedido.dni)
    $('#telefono').val(pedido.telefono)
    $('#email').val(pedido.email)
    $('#vacante').val(pedido.vacante)
    $('#direccion').val(pedido.direccion)
    $('#observaciones').val(pedido.observaciones)
    $('#costo').val(pedido.costo)
    $('#precio').val(pedido.precio)

  $('#form_registrar_pedido').validate({
    ignore: "",
    rules: {
      idpedido: {
        required: true
      },
      cliente: {
        required: true
      },
      subcliente: {
        required: true
      },
      servicio: {
        required: true
      },
      proveedor: {
        required: true
      },
      analista: {
        required: true
      },
      candidato: {
        required: true
      },
      dni: {
        required: true
      },
      telefono: {
        required: true
      },
      email: {
        required: true
      },
      vacante: {
        required: true
      },
      direccion: {
        required: true
      },
      id_provincia: {
        required: true
      },
      id_localidad: {
        required: true
      },
      costo: {
        required: true
      },
      precio: {
        required: true,
        number: true
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
            location.assign('/backend/pedidos/new');
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