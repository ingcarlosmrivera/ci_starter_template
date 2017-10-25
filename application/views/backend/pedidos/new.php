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
                <?php if (isset($prepedido)): ?>
                  <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4 class="no-margin">Finalizar prepedido</h4>
                    <p>
                      Estás finalizando el prepedido iniciado por <b><?= $prepedido->subcliente ?></b> con fecha <b><?= _date($prepedido->creado) ?></b>; Al completar y registrar el pedido, se marcará el prepedido como procesado.
                    </p>

                    <?php if (!is_null($prepedido->fileurl) && !empty($prepedido->fileurl)): ?>
                      <h5 class="no-margin">Este pedido tiene un archivo adjunto. Puedes descargarlo <a href="/uploads/<?= $prepedido->fileurl ?>" target="_blank" class="pointer">aquí</a></h5>
                        <input type="hidden" name="fileurl" id="fileurl" value="<?= $prepedido->fileurl ?>">
                    <?php endif ?>
                  </div>
                  <input type="hidden" name="idprepedido" id="idprepedido" value="<?= $prepedido->idprepedido ?>">
                <?php endif ?>
                <div class="row">
                  <div class="col-xs-12 col-md-2 form-group">
                    <label for="servicio">Fecha</label>
                    <input type="text" class="form-control" name="creado_picker" id="creado_picker">
                    <input type="hidden" name="creado" id="creado">
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
                    <select name="servicio" id="servicio" class="form-control" <?= (isset($prepedido)) ? '' : 'disabled="disabled"' ?>>
                      
                    </select>
                  </div>

                  <div class="col-xs-12 col-md-6 form-group">
                    <label for="proveedor">Proveedor</label>
                    <select name="proveedor" id="proveedor" class="form-control" disabled="disabled">
                      
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

                  <div class="col-xs-12 col-md-4 form-group">
                    <label for="">&nbsp;</label>
                    <div class="checkbox icheck">
                      <label>
                        <input type="checkbox" name="requiere_oc" id="requiere_oc" disabled="true"> Requiere orden de compra para facturar
                      </label>
                    </div>
                  </div>

                  <div class="col-xs-12 col-md-4 form-group collapse">
                    <label for="precio">Orden de compra</label>
                    <input type="text" class="form-control" placeholder="Orden de compra" name="oc" id="oc">
                  </div>

                  <div class="col-xs-12 col-md-12 form-group">
                    <label for="observaciones">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" rows="2" class="form-control full-width"></textarea>
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

<?php require(__DIR__.'/../footer.php') ?>

<script>

  $('.icheck').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    increaseArea: '20%' // optional
  });

  var f = moment().startOf('day');

  function fp(start) {
      start = start.startOf('day').format('YYYY-MM-DD HH:mm:ss');

      $('#creado').val(start);
  }

  $('#creado_picker').daterangepicker({
     "autoApply": true,
      locale: {
        format: 'DD/MM/YYYY'
      },
      singleDatePicker: true
  }, fp);

  fp(f);

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
                text: item.servicio
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

  $('#proveedor').on('change', function(event) {
    event.preventDefault();
    $('#costo').val('0')

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

  $('#cliente, #servicio').on('change', function(event) {
    event.preventDefault();

    if ($('#cliente').val() !== null && $('#servicio').val() !== null) {
      var id_cliente = $('#cliente').val();
      var id_servicio = $('#servicio').val();

      $.ajax({
        url: '/actions/get_precio_servicio/' + id_cliente + '/' + id_servicio,
        dataType: 'JSON'
      })
      .done(function(data) {
        if (data) {
          $('#precio').val(data.precio)
          iziToast.success({
              title: 'Precio obtenido',
              message: 'Precio obtenido para el servicio y cliente seleccionado.',
          });
        } else {
          iziToast.error({
              title: 'Error',
              message: 'No se encontró el precio del servicio con el cliente seleccionado.',
          });
        }
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      }); 
    }

      
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
  
  <?php if (isset($prepedido)): ?>

    // init selects
    var servicio    = $('<option selected><?= $prepedido->servicio ?></option>').val('<?= $prepedido->id_servicio ?>');
    var cliente    = $('<option selected><?= $prepedido->cliente ?></option>').val('<?= $prepedido->id_cliente ?>');
    var subcliente    = $('<option selected><?= $prepedido->subcliente ?></option>').val('<?= $prepedido->id_subcliente ?>');
    var provincia  = '<?= $prepedido->id_provincia ?>';
    var localidad  = '<?= $prepedido->id_localidad ?>';
    var requiere_oc  = '<?= $prepedido->requiere_oc ?>';
    var oc  = '<?= $prepedido->oc ?>';

    $('#servicio').append(servicio).trigger('change');
    $('#cliente').append(cliente).trigger('change');
    $('#subcliente').append(subcliente).trigger('change');
    $('#id_provincia').val(provincia).trigger('change');
    
    if (typeof deferred !==  'undefined') {
      $.when(deferred).then(function() {
        $('#id_localidad').val(localidad);
      });
    }

    if (requiere_oc == '1') {
      $('#requiere_oc').iCheck('check');
      $('#oc').parent().show();
      $('#oc').val(oc);
    } else {
      $('#requiere_oc').iCheck('uncheck');
      $('#oc').parent().hide();
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
  <?php else: ?>
    $("#cliente").select2({
      theme: "bootstrap",
      allowClear: true,
      placeholder: "Escriba y seleccione",
      tags: false,
      multiple: false,
      ajax: {
        url: function (params) {
          return '/actions/search_clientes/' + params.term;
        },
        dataType: "json",
        type: "POST",
        cache: false,
        minimumInputLength: 1,
        processResults: function (data) {
          var results = [];
          $.each(data, function (index, item) {
              results.push({
                  id: item.idcliente,
                  text: item.cliente,
                  email: item.email,
                  forzaroc: item.forzar_oc
              });
          });

          return {
              results: results
          };
        }
      }
    })

    $("#cliente").on('change', function(event) {
      event.preventDefault();
      var p = $(this).val();
      $("#subcliente").empty()

      if (p !== "") {
        // si el cliente requiere oc, chequear, sino, destildar
        if ($(this).select2('data')[0].forzaroc == '1') {
          $('#requiere_oc').iCheck('check');
          $('#oc').parent().show();
        } else {
          $('#requiere_oc').iCheck('uncheck');
          $('#oc').parent().hide();
        }


        $.getJSON('/actions/get_subclientes/' + p, function(subclientes, textStatus) {
            $.each(subclientes, function(index, l) {
              $("#subcliente").append('<option value="' + l.idsubcliente + '">' + l.nombre + '</option>')
            });
        });

        $('#servicio').prop('disabled', false);
      } else {
        $('#servicio').prop('disabled', false);
      }
    });
  <?php endif ?>

  $('#form_registrar_pedido').validate({
    ignore: "#oc:hidden",
    rules: {
      cliente: {
        required: true
      },
      oc: {
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
      $('#requiere_oc').iCheck('enable');
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
            location.assign(data.redirect);
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