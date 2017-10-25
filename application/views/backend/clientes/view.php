<?php require(__DIR__.'/../header.php') ?>
<?php require(__DIR__.'/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Ficha de Cliente
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-user"></i> Clientes</a></li>
        <li class="active">Ficha de Cliente</li>
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
              <h3 class="box-title"><i class="fa fa-users"></i> Ficha de Cliente</h3>
            </div>
            
            <div class="box-body">

              <div class="col-xs-12">
                <label class="no-margin" for="">Razón Social</label>
                <h4 class="no-margin-top"><?= $cliente->razon ?></h4>
              </div>

              <div class="col-md-6">
                <label class="no-margin" for="">CUIT</label>
                <h4 class="no-margin-top"><?= $cliente->cuit ?></h4>
              </div>

              <div class="col-md-6">
                <label class="no-margin" for="">Código</label>
                <h4 class="no-margin-top"><?= $cliente->codigo ?></h4>
              </div>

              <div class="col-md-6">
                <label class="no-margin" for="">Teléfono</label>
                <h4 class="no-margin-top"><?= $cliente->telefono ?></h4>
              </div>

              <div class="col-md-6">
                <label class="no-margin" for="">Email</label>
                <h4 class="no-margin-top"><?= $cliente->email ?></h4>
              </div>

              

              <div class="col-xs-12 col-md-6">
                <label class="no-margin" for="">Dirección</label>
                <p class="text-justify">
                  <?= ($cliente->direccion) ? $cliente->direccion : '-' ?>
                </p>
              </div>

              <div class="col-xs-12 col-md-6">
                <label class="no-margin" for="">Requiere Orden de Compra</label>
                <p class="text-justify">
                  <?= ($cliente->forzar_oc) ? 'Sí' : 'No' ?>
                </p>
              </div>

              <!-- cotizaciones -->
              <div class="col-xs-12 col-md-6">
                <h3>Cotizaciones</h3>
                <div class="dropzone">
                  <h4 class="text-info dz-message" data-dz-message>Arrastra o selecciona una cotización para cargarla</h4>
                </div>
              </div>

              <div class="col-xs-12 col-md-6">
                <h3>&nbsp;</h3>
                <?php if ($cotizaciones): ?>
                    <?php foreach ($cotizaciones as $c): ?>
                      <a href="<?= $c->fileurl ?>" target='_blank' class="cotizacion">
                        <span class="fa fa-file-pdf-o fa-3x text-primary pointer" data-toggle="tooltip" title="Fecha: <?= _date($c->fecha) ?> - Archivo: <?= $c->filename ?>"></span>
                      </a>
                    <?php endforeach ?>
                <?php endif ?>

                
              </div>

              <!-- subclientes -->
              <div class="col-xs-12">
                <h3>Subclientes</h3>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Subcliente</th>
                      <th>Teléfono</th>
                      <th>Email</th>
                      <th class="text-center col-xs-1"><i class="fa fa-cogs"></i></th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php if ($subclientes): ?>
                      <?php foreach ($subclientes as $s): ?>
                        <tr>
                          <td><?= $s->nombre ?></td>
                          <td><?= $s->telefono ?></td>
                          <td><?= $s->email ?></td>
                          <td class="text-center col-md-2">
                            <button type="button" class="btn btn-success btn-sm" data-idsubcliente="<?= $s->idsubcliente ?>" onclick="show_add_modal(this)"><i class="fa fa-user-plus"></i> </button>

                            <button type="button" data-url="/actions/delete_subcliente/<?= $cliente->idcliente ?>/<?= $s->idsubcliente ?>" class="btn btn-danger btn-sm delete"><i class="fa fa-close"></i></button>
                          </td>
                        </tr>
                      <?php endforeach ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="4" class="text-center">No hay subclientes registrados</td>
                      </tr>
                    <?php endif ?>
                  </tbody>

                  <tfoot>
                    <tr>
                      <td colspan="4" class="text-right">
                        <button type="button" class="btn btn-primary" id="add_servicio" data-toggle="modal" data-target="#modal-add-subcliente">
                          <i class="fa fa-user-plus"></i> 
                          Agregar nuevo subcliente
                        </button>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
              
              <!-- servicios -->
              <div class="col-xs-12">
                <h3>Servicios</h3>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Servicio</th>
                      <th>Precio</th>
                      <th class="text-center col-xs-1"><i class="fa fa-cogs"></i></th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php if ($servicios_cliente): ?>
                      <?php foreach ($servicios_cliente as $s): ?>
                        <tr>
                          <td><?= $s->servicio ?></td>
                          <td><?= $s->precio ?></td>
                          <td class="text-center"><a href="#" data-url="/actions/delete_servicio_cliente/<?= $cliente->idcliente ?>/<?= $s->idservicio ?>"  class="btn btn-danger btn-sm delete"><i class="fa fa-close"></i> Eliminar</a></td>
                        </tr>
                      <?php endforeach ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="4" class="text-center">No hay servicios asociados a este cliente</td>
                      </tr>
                    <?php endif ?>
                  </tbody>

                  <tfoot>
                    <tr>
                      <td colspan="4" class="text-right">
                        <button type="button" class="btn btn-primary" id="add_servicio" data-toggle="modal" data-target="#modal-add-servicio">
                          <i class="fa fa-plus"></i> 
                          Agregar nuevo servicio
                        </button>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>

              <!-- Meicos -->
              <div class="col-xs-12">
                <h3>Médicos autorizados</h3>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Médico</th>
                      <th>Email</th>
                      <th class="text-center col-xs-1"><i class="fa fa-cogs"></i></th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php if ($medicos_cliente): ?>
                      <?php foreach ($medicos_cliente as $m): ?>
                        <tr>
                          <td><?= $m->nombre ?></td>
                          <td><?= $m->email ?></td>
                          <td class="text-center"><a href="#" data-url="/actions/delete_medico_cliente/<?= $cliente->idcliente ?>/<?= $m->idmedico ?>"  class="btn btn-danger btn-sm delete"><i class="fa fa-close"></i> Eliminar</a></td>
                        </tr>
                      <?php endforeach ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="4" class="text-center">No hay médicos autorizados para este cliente</td>
                      </tr>
                    <?php endif ?>
                  </tbody>

                  <tfoot>
                    <tr>
                      <td colspan="4" class="text-right">
                        <button type="button" class="btn btn-primary" id="add_servicio" data-toggle="modal" data-target="#modal-add-medico">
                          <i class="fa fa-plus"></i> 
                          Autorizar nuevo médico
                        </button>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer text-right">
              <a href="/backend/clientes/edit/<?= $cliente->idcliente ?>" class="btn btn-warning">
                <i class="fa fa-pencil"></i> 
                Editar cliente
              </a>
            </div>

          </div>
          <!-- /.box -->
        </div>
      </div>

    </section>
    <!-- /.content -->

    <div class="modal fade" id="modal-add-cliente">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Asociar subcliente a nuevo cliente</h4>
          </div>

          <form class="form" method="POST" name="form-add-cliente" id="form-add-cliente">
            <div class="modal-body">
              <div class="row">
                
                <div class="alert alert-info">
                  <h4 class="no-margin">Puedes autorizar a este subcliente para que pueda realizar pedidos a nombre de otro cliente aparte del principal.</h4>
                </div>

                <div class="form-group col-xs-12">
                  <label for="servicio">Selecciona el cliente a autorizar</label>
                  <select name="id_cliente" id="id_cliente" class="form-control">
                    
                  </select>

                  <input type="hidden" name="id_subcliente" id="id_subcliente">
                </div>

                <div class="col-xs-12" id="clientes">
                  <h4>Clientes adicionales autorizados:</h4>

                  <div class="lista-clientes">
                    
                  </div>
                </div>

              </div>
                  
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-add-servicio">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Registrar servicio a cliente</h4>
          </div>

          <form class="form" method="POST" name="form-add-servicio" id="form-add-servicio">
            <div class="modal-body">
              <div class="row">
                <div class="form-group col-xs-12 col-md-9">
                  <label for="servicio">Selecciona el servicio</label>
                  <select name="servicio" id="servicio" class="form-control">
                    <option value="">Seleccione</option>
                    <?php if ($servicios): ?>
                      <?php foreach ($servicios as $s): ?>
                        <option value="<?= $s->idservicio ?>"><?= $s->servicio ?></option>
                      <?php endforeach ?>
                    <?php endif ?>
                  </select>
                </div>

                <div class="form-group col-xs-12 col-md-3">
                  <label for="servicio">Precio</label>
                  <input type="text" class="form-control" name="precio" id="precio" placeholder="Precio">
                  <input type="hidden" name="action" id="action" value="servicio">
                  <input type="hidden" name="cliente" id="cliente" value="<?= $cliente->idcliente ?>">
                </div>
              </div>
                  
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-add-medico">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Autorizar médico a cliente</h4>
          </div>

          <form class="form" method="POST" name="form-add-medico" id="form-add-medico">
            <div class="modal-body">
              <div class="row">
                <div class="form-group col-xs-12">
                  <label for="medico">Selecciona el médico</label>
                  <select name="medico" id="medico" class="form-control">
                    <option value="">Seleccione</option>
                    <?php if ($medicos): ?>
                      <?php foreach ($medicos as $s): ?>
                        <option value="<?= $s->idmedico ?>"><?= $s->nombre ?></option>
                      <?php endforeach ?>
                    <?php endif ?>
                  </select>
                </div>

                <div class="collapse">
                  <input type="hidden" name="action" id="action" value="medico">
                  <input type="hidden" name="cliente" id="cliente" value="<?= $cliente->idcliente ?>">
                </div>
              </div>
                  
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-add-subcliente">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Registrar Subcliente</h4>
          </div>

          <form class="form" method="POST" name="form-add-subcliente" id="form-add-subcliente">
            <div class="modal-body">
              <div class="row">
                <div class="form-group col-xs-12">
                  <label for="servicio">Nombre del subcliente</label>
                  <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre completo">
                </div>

                <div class="form-group col-md-6">
                  <label for="servicio">Teléfono</label>
                  <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Teléfono">
                </div>

                <div class="form-group col-md-6">
                  <label for="servicio">Email</label>
                  <input type="text" class="form-control" name="email" id="email" placeholder="Email">
                  <input type="hidden" name="action" id="action" value="subcliente">
                  <input type="hidden" name="cliente" id="cliente" value="<?= $cliente->idcliente ?>">
                </div>
              </div>
                  
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
  <!-- /.content-wrapper -->

<?php require(__DIR__.'/../footer.php') ?>

<script>

  $("#id_cliente").select2({
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
                  text: item.cliente
              });
          });

          return {
              results: results
          };
        }
      }
    })

  function show_add_modal(button)
  {
    var idsubcliente = $(button).data('idsubcliente');

    $('#id_subcliente').val(idsubcliente);
    $('#modal-add-cliente').modal('show');

    // get subclientes autorizados
    $.ajax({
      url: '/actions/get_subclientes_autorizados',
      type: 'POST',
      dataType: 'JSON',
      data: {id_subcliente: idsubcliente},
    })
    .done(function(data) {
      $('.lista-clientes').empty();
      if (data) {
        $.each(data, function(index, c) {
           var p = $('<p>');
           p.html(c.razon);
           $('.lista-clientes').append(p);
        });
      } else {
        var p = $('<p>');
        p.text('No hay clientes adicionales autorizados');
        $('.lista-clientes').append(p);
      }
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });
    
  }
  Dropzone.autoDiscover = false;

  $('.dropzone').dropzone({
    url: '/actions/upload_cotizacion',
    method: 'post',
    sending:function(file, xhr, formData){
      formData.append('id_cliente', <?= $cliente->idcliente ?> );
    }
  });

  $('.delete').on('click', function(event) {
    event.preventDefault();
    var url = $(this).data('url');
    var response = confirm("¿Seguro desea realizar esta operación?");
    console.log(url);
    if (response == true) {
      $.ajax({
        url: url,
        type: 'GET',
        dataType: 'JSON'
      })
      .done(function(data) {
        $('#modal_result').removeClass().addClass(data.class);
        $('#modal_result_icon').removeClass().addClass(data.icon);
        $('#modal_result_message').html(data.message);
        $('#modal_result').modal();

        if (data.status == 'success') {
          $("#modal_result").on('hide.bs.modal', function () {
            location.reload();
          });
        }

        console.log("success");
      })
      .fail(function(a,b) {
        console.log("error", a , b);
      })
      .always(function() {
        console.log("complete");
      });
      
    }
  });


  $('#form-add-servicio').validate({
    ignore: "",
    rules: {
      servicio: {
        required: true
      },
      precio: {
        required: true,
        number: true
      }
    },
    submitHandler: function(form) {
      var data = $(form).serialize();
      $.ajax({
        type: 'POST',
        dataType: 'JSON',
        data: data,
      })
      .done(function(data) {
        $('#modal_result').removeClass().addClass(data.class);
        $('#modal_result_icon').removeClass().addClass(data.icon);
        $('#modal_result_message').html(data.message);
        $('#modal_result').modal();

        if (data.status == 'success') {
          $('#form_registrar_cliente').trigger("reset");
          $("#modal_result").on('hide.bs.modal', function () {
            location.reload();
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
      
    }
  })

  $('#form-add-subcliente').validate({
    ignore: "",
    rules: {
      nombre: {
        required: true,
        minlength: 3
      },
      telefono: {
        required: true
      },
      email: {
        required: true,
        email: true
      }
    },
    submitHandler: function(form) {
      var data = $(form).serialize();
      $.ajax({
        type: 'POST',
        dataType: 'JSON',
        data: data,
      })
      .done(function(data) {
        $('#modal_result').removeClass().addClass(data.class);
        $('#modal_result_icon').removeClass().addClass(data.icon);
        $('#modal_result_message').html(data.message);
        $('#modal_result').modal();

        if (data.status == 'success') {
          $('#form_registrar_cliente').trigger("reset");
          $("#modal_result").on('hide.bs.modal', function () {
            location.reload();
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
      
    }
  });

  $('#form-add-medico').validate({
    ignore: "",
    rules: {
      idmedico: {
        required: true
      }
    },
    submitHandler: function(form) {
      var data = $(form).serialize();
      $.ajax({
        type: 'POST',
        dataType: 'JSON',
        data: data,
      })
      .done(function(data) {
        $('#modal_result').removeClass().addClass(data.class);
        $('#modal_result_icon').removeClass().addClass(data.icon);
        $('#modal_result_message').html(data.message);
        $('#modal_result').modal();

        if (data.status == 'success') {
          $('#form_registrar_cliente').trigger("reset");
          $("#modal_result").on('hide.bs.modal', function () {
            location.reload();
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
      
    }
  });

  $('#form-add-cliente').validate({
    ignore: "",
    rules: {
      id_cliente: {
        required: true
      }
    },
    submitHandler: function(form) {
      var data = $(form).serialize();
      $.ajax({
        url: '/actions/add_cliente_subcliente',
        type: 'POST',
        dataType: 'JSON',
        data: data,
      })
      .done(function(data) {
        $('#modal_result').removeClass().addClass(data.class);
        $('#modal_result_icon').removeClass().addClass(data.icon);
        $('#modal_result_message').html(data.message);
        $('#modal_result').modal();

        $("#modal_result").on('hide.bs.modal', function () {
          location.reload();
        });

        console.log("success");
      })
      .fail(function() {
        alert('Ha ocurrido un error. Por favor, verifica que no exista este cliente asociado previamente al subcliente.');
        location.reload();
 
          
      })
      .always(function() {
        console.log("complete");
      });
      
    }
  })
</script>