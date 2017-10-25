<?php require(__DIR__.'/../header.php') ?>
<?php require(__DIR__.'/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Lista de Pedidos
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-user"></i> Pedidos</a></li>
        <li class="active">Lista de Pedidos</li>
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
              <h3 class="box-title">Lista de Pedidos - <b><?= $status ?></b></h3>
            </div>
            <div class="box-body">
                <div class="row">
                  <form class="form" name="form_search" id="form_search" method="post" action="/backend/pedidos/<?= $estado_actual ?>">
                    <div class="form-group col-sm-2">
                      <label>Buscar por:</label>
                      <select class="form-control" name="buscar_por" id="buscar_por">
                        <option value="pedidos.candidato" <?= ($buscar_por == 'pedidos.candidato') ? 'selected' : '' ?>>Candidato</option>
                        <option value="pedidos.dni" <?= ($buscar_por == 'pedidos.dni') ? 'selected' : '' ?>>DNI</option>
                        <option value="pedidos.oc" <?= ($buscar_por == 'pedidos.oc') ? 'selected' : '' ?>>Orden de Compra</option>
                        <option value="clientes.razon" <?= ($buscar_por == 'clientes.razon') ? 'selected' : '' ?>>Cliente</option>
                        <option value="proveedor" <?= ($buscar_por == 'proveedor') ? 'selected' : '' ?>>Proveedor</option>
                        <option value="analista" <?= ($buscar_por == 'analista') ? 'selected' : '' ?>>Analista</option>
                      </select>
                    </div>

                    <div class="form-group col-sm-3" >
                      <label for="">Escribe búsqueda</label>
                      <div class="input-group">
                        <input type="text" name="text" id="text" class="form-control pull-right" placeholder="Buscar" value="<?= $busqueda ?>">
                        <span class="input-group-addon pointer">
                          <span class="fa fa-search "></span>
                        </span>
                      </div>
                    </div>

                    <div class="form-group col-sm-2">
                      <label>Condición:</label>
                      <select class="form-control" name="condicion" id="condicion">
                        <option value="todos" <?= ($condicion == 'todos') ? 'selected' : '' ?>>Todos</option>
                        <option value="flagged" <?= ($condicion == 'flagged') ? 'selected' : '' ?>>Marcados</option>
                        <option value="no_flagged" <?= ($condicion == 'no_flagged') ? 'selected' : '' ?>>No marcados</option>
                      </select>
                    </div>

                    <div class="col-sm-1">
                      <div class="form-group">
                        <label>Costo 0</label>
                        <div class="checkbox">
                          <label>
                            <input type="checkbox" name="sin_costo" id="sin_costo" <?php echo ($sin_costo == 'on') ? 'checked="checked"' : '' ?>>
                          </label>
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-1">
                      <div class="form-group">
                        <label>Precio 0</label>
                        <div class="checkbox">
                          <label>
                            <input type="checkbox" name="sin_precio" id="sin_precio" <?php echo ($sin_precio == 'on') ? 'checked="checked"' : '' ?>>
                          </label>
                        </div>
                      </div>
                    </div>

                    <div class="form-group col-sm-1">
                      <label class="block">&nbsp;</label>
                      <button type="submit" class="btn btn-primary btn-flat">
                        <i class="fa fa-search"></i> 
                        Buscar
                      </button>
                    </div>

                    

                    <div class="form-group col-sm-2 text-right">
                      <label class="block">&nbsp;</label>
                      <button type="button" id="habilitar_masivo" class="btn bg-purple btn-flat">
                        <i class="fa fa-edit"></i> 
                        Acciones masivas
                      </button>

                      <a href="<?= $export_link ?>" class="btn btn-info btn-flat">
                        <i class="fa fa-cloud-download"></i> 
                      </a>
                    </div>

                  </form>  
                </div>   
                
                <div class="row">
                  <div class="col-xs-12">
                    <p class="text-right">
                      <b><?= $rows ?></b> Pedido(s) encontrados
                    </p>
                    <div class="table-responsive">
                      
                      <table class="table table-bordered table-striped">
                        <tbody>

                          <?php if ($pedidos): ?>
                            <?php foreach ($pedidos as $p): ?>
                              <tr>
                                <td class="text-center collapse check" rowspan="2">
                                  <input type="checkbox" class="checkbox" name="pedidos[]" value="<?= $p->idpedido ?>">
                                </td>
                                <td colspan="6">
                                  <b>Fecha:</b> <?php echo _date($p->creado) ?> <br>
                                  <b>Servicio:</b> <?= $p->servicio ?> <br>
                                  <b>Subcliente:</b> <?= $p->subcliente ?>
                                </td>
                                <td class="text-center" rowspan="2" style="line-height: 120px">
                                  <a href="/backend/pedidos/view/<?= $p->idpedido ?>" class="btn btn-info btn-sm btn-flat" target="_blank">
                                    <i class="fa fa-eye"></i>  
                                  </a>

                                  <a href="/backend/pedidos/copy/<?= $p->idpedido ?>" class="btn bg-purple btn-sm btn-flat">
                                    <i class="fa fa-clipboard"></i>  
                                  </a>

                                  <button class="btn btn-sm btn-primary btn-flat view-chat-history" data-idpedido="<?= $p->idpedido ?>" data-candidato="<?= $p->candidato ?>" data-servicio="<?= $p->servicio ?>" data-toggle="modal" data-target="#modal-chat" >
                                    <i class="fa fa-wechat"></i>
                                  </button>

                                  <?php if ($p->estado != 'finalizado'): ?>
                                    <button class="btn btn-default btn-sm btn-flat flag" data-idpedido="<?= $p->idpedido ?>" data-status="<?= $p->flagged ?>">
                                      <?php if ($p->flagged): ?>
                                        <i class="fa fa-flag text-primary"></i>
                                      <?php else: ?>
                                        <i class="fa fa-flag"></i>
                                      <?php endif ?>
                                    </button>
                                  <?php endif ?>

                                    

                                  <label class="label label-default block"><?= $p->estado ?></label>
                                </td>
                              </tr>

                              <tr>
                                <td>
                                  <label class="block">Candidato</label>
                                  <?php echo $p->candidato ?>
                                </td>
                                <td>
                                  <label class="block">DNI</label>
                                  <?php echo $p->dni ?>
                                </td>
                                <td>
                                  <label class="block">Cliente</label>
                                  <?php echo $p->cliente ?>
                                </td>
                                <td>
                                  <label class="block">Provincia</label>
                                  <?php echo $p->provincia ?> <br>
                                  <small><?php echo $p->localidad ?></small>
                                </td>
                                <td>
                                  <label class="block">Proveedor</label>
                                  <?php echo $p->proveedor ?>
                                </td>
                                <td>
                                  <label class="block">Analista</label>
                                  <?php echo $p->analista ?>
                                </td>
                              </tr>
                            <?php endforeach; ?>

                            <tr class="acciones collapse">
                              <td colspan="2">
                                <div class="form-group">
                                  <label for="">Cambiar estado a:</label>
                                  <select name="nuevo_estado" id="nuevo_estado" class="form-control">
                                    <option value="porconfirmar">Por confirmar</option>
                                    <option value="activado">Activado</option>
                                    <option value="analisis">Análisis</option>
                                    <option value="finalizado">Finalizado</option>
                                  </select>

                                  <input type="hidden" name="estado_actual" id="estado_actual" value="<?= $estado_actual ?>">
                                </div>
                              </td>
                              <td colspan="5">
                                <div class="form-group">
                                  <label class="block">&nbsp;</label>
                                  <button id="save" class="btn btn-sm btn-success btn-flat">Guardar</button>
                                </div>
                              </td>
                            </tr>
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

    <div class="modal fade" id="modal-chat">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Historial de mensajes</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="">Candidato</label>
              <h5 class="no-margin" id="candidato"></h5>
            </div>

            <div class="form-group">
              <label for="">Servicio</label>
              <h5 class="no-margin" id="servicio"></h5>
            </div>

            <div id="mensajes">
              
            </div>

            <div id="nm">

              <form name="form_nm" id="form_nm" method="POST">
                <div class="form-group">
                  <label for="">Enviar nuevo mensaje</label>
                  <textarea name="mensaje_nm" id="mensaje_nm" rows="1" class="form-control full-width"></textarea>
                  <input type="hidden" name="idpedido_nm" id="idpedido_nm">
                  <input type="hidden" name="usuario_nm" id="usuario_nm" value="<?= sprintf("%s %s", get_user()->first_name, get_user()->last_name) ?>">                
                </div>
                

                <div class="form-group text-right">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                  <button type="submit" class="btn btn-success">Enviar mensaje</button>
                </div>              
              </form>
                
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->

<?php require(__DIR__.'/../footer.php') ?>

<script>

  // chat
  $('.view-chat-history').on('click', function(event) {
    event.preventDefault();
    var id = $(this).data('idpedido');
    var candidato = $(this).data('candidato');
    var servicio = $(this).data('servicio');

    actualizar_comentarios(id, candidato, servicio);
  });


  $('#form_nm').validate({
    ignore: "",
    rules: {
      mensaje_nm: {
        required: true
      }
    },
    submitHandler: function(form) {
      var data = $(form).serialize();
      $.ajax({
        url: '/actions/save_chat',
        type: 'POST',
        dataType: 'json',
        data: data
      })
      .done(function(data) {
        actualizar_comentarios($('#idpedido_nm').val())
        if (data.status == 'success') {
          iziToast.show({
              title: 'OK',
              message: 'Su mensaje ha sido enviado',
              timeout: 7000,
              color: 'green'
          });
        } else {
          iziToast.show({
              title: 'Error',
              message: 'Algo salió mal',
              color: 'red',
              timeout: 7000
          });
        }

        $('#form_nm').validate().resetForm();
        
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

  function actualizar_comentarios(id, candidato = null, servicio = null) {

    $('#mensaje_nm').val('')
    $('#mensajes').empty();
    $.ajax({
      url: '/actions/get_chat_history/' + id,
      dataType: 'JSON'
    })
    .done(function(data) {
      if (candidato !== null) {
        $('#candidato').html(candidato);
      }

      if (servicio !== null) {
        $('#servicio').html(servicio);
      }

      $('#idpedido_nm').val(id);

      if (data.status == 'success') {
        // clear chat container
        $('.mensajes').empty();
        // templates
        var l = "<div class='direct-chat-msg left'>            <div class='direct-chat-info clearfix'>              <span class='direct-chat-name pull-left'>{NAME}</span>              <span class='direct-chat-timestamp pull-right'>{DATE}</span>            </div>            <div class='direct-chat-text'>              {MESSAGE}            </div>          </div>";
        var r = "<div class='direct-chat-msg right'>            <div class='direct-chat-info clearfix'>              <span class='direct-chat-name pull-right'>{NAME}</span>              <span class='direct-chat-timestamp pull-left'>{DATE}</span>            </div>            <div class='direct-chat-text'>              {MESSAGE}            </div>          </div>";

        var init = 'r';
        var nombre = '';
        if (data.messages_found > 0) {

          $.each(data.chats, function(index, c) {
            if (nombre != c.usuario) {
              init = (init == 'l') ? 'r' : 'l';
            }

            if (init == 'l') {
              var m = l.replace('{NAME}', c.usuario);
              m = m.replace('{DATE}', c.fecha);
              m = m.replace('{MESSAGE}', c.comentario);
            } else {
              var m = r.replace('{NAME}', c.usuario);
              m = m.replace('{DATE}', c.fecha);
              m = m.replace('{MESSAGE}', c.comentario);
            }

            nombre = c.usuario;

            $('#mensajes').append(m)
          });
        }
      }
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });
  }

  $('#modal-chat').on('show.bs.modal', function () {
    $('.modal .modal-body').css('overflow-y', 'auto'); 
    $('.modal .modal-body').css('max-height', $(window).height() * 0.8);
  });


  $('#habilitar_masivo').on('click', function(event) {
    event.preventDefault();
    $('.check').toggle()
  }); 

  $('.flag').on('click', function(event) {
    event.preventDefault();
    var idpedido = $(this).data('idpedido');
    var status = $(this).data('status');
    var btn = $(this);
    var btni = $(this).find('i.fa');

    var data = {idpedido: idpedido, flag_status: status};

    $.ajax({
      url: '/actions/flag_pedido',
      type: 'POST',
      dataType: 'JSON',
      data: data,
    })
    .done(function(response) {
      console.log(status)
      if (status == 0) {
        btni.addClass('text-primary');
        btn.data('status', '1');
      } else {
        btni.removeClass('text-primary');
        btn.data('status', '0');
      }
      console.log("success");
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });
    
  });

  $('.checkbox').on('change', function(event) {
    event.preventDefault();
    if ($('.checkbox:checked').length > 0) {
      $('.acciones').show();
    } else {
      $('.acciones').hide();
    }
  });

  $('#save').on('click', function(event) {
    event.preventDefault();
    var data = {estado_actual: $('#estado_actual').val(), nuevo_estado: $('#nuevo_estado').val(), pedidos: $(".checkbox:checked").map(function(){return $(this).val()}).get()}
    
    var c = confirm("Esta acción no puede revertirse. ¿Está seguro de modificar el estado de los pedidos seleccionados?");

    if (c) {
      $.ajax({
        url: '/actions/update_estado_pedidos',
        type: 'POST',
        dataType: 'JSON',
        data: data,
      })
      .done(function(response) {
        $('#modal_result').removeClass().addClass(response.class);
        $('#modal_result_icon').removeClass().addClass(response.icon);
        $('#modal_result_message').html(response.message);
        $('#modal_result').modal();

        $("#modal_result").on('hide.bs.modal', function () {
          location.reload();
        });
        console.log("success");
      })
      .fail(function() {
        alert('fail')
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
      
    }
  });
</script>