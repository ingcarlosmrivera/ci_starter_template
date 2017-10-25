<?php require('header.php') ?>
<?php require('sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Resumen de pedidos</small>
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
        <?php if ($cotizaciones): ?>
          <div class="col-xs-12">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title">Historial de cotizaciones</h3>
              </div>
              <div class="box-body">
                <?php foreach ($cotizaciones as $c): ?>
                  <a href="<?= $c->fileurl ?>" target='_blank' class='cotizacion'>
                    <span class="fa fa-file-pdf-o fa-3x text-primary pointer" data-toggle="tooltip" title="Fecha: <?= _date($c->fecha) ?> - Archivo: <?= $c->filename ?>"></span>
                  </a>
                <?php endforeach ?>            
              </div>
            </div>
            <!-- /.box -->
          </div>
        <?php endif ?>


        <?php if ($prepedidos): ?>
          <div class="col-xs-12">
            <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title">Pedidos preprocesados</h3>
              </div>
              <div class="box-body">
                <div class="col-xs-12 col-md-4">
                  <div class="small-box bg-yellow">
                    <div class="inner">
                      <h3><?= count($prepedidos) ?></h3>

                      <p>Pedidos por procesar</p>
                    </div>
                    <div class="icon">
                      <i class="fa fa-line-chart"></i>
                    </div>
                    <a data-toggle="collapse" data-target=".detalles-prepedidos" class="pointer small-box-footer">Mostrar detalles <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>

                <div class="col-xs-12 detalles-prepedidos collapse">
                  <div class="alert alert-info visible-xs">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4 class="text-center">Deslice la tabla a la izquierda para ver los detalles completos</h4>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped">
                      <thead>
                        <th>Fecha</th>
                        <th>Candidato</th>
                        <th>DNI</th>
                        <th>Provincia</th>
                        <th>Vacante</th>
                        <th>Servicio</th>
                        <th>Cliente</th>
                        <th>Solicitante</th>
                        <th class="text-center text-primary col-md-1"><i class="fa fa-arrow-circle-down"></i></th>
                      </thead>
                      <tbody>
                        <?php foreach ($prepedidos as $p): ?>
                          <tr>
                            <td><?php echo _date($p->creado) ?></td>
                            <td><?php echo ($p->candidato) ? $p->candidato : '-' ?></td>
                            <td><?php echo ($p->dni) ? $p->dni : '-' ?></td>
                            <td><?php echo $p->provincia ?> <br> <small><?= $p->localidad ?></small></td>
                            <td><?php echo ($p->vacante) ? $p->vacante : '-' ?></td>
                            <td>
                              <?php echo ($p->servicio) ? $p->servicio : '-' ?>
                            </td>
                            <td><?= $p->cliente ?></td>
                            <td><?= $p->subcliente ?></td>
                            <td class="text-center col-md-1">
                              <?php if ($p->fileurl): ?>
                                <a  href="/uploads/<?= $p->fileurl?>" >
                                  <i class="fa fa-file-pdf-o"></i>
                                </a>
                              <?php endif; ?>

                              <a href="/clientes/pedido/<?= $p->idprepedido ?>" class="text-primary" data-toggle="tooltip" title="Copiar prepedido"><i class="fa fa-copy"></i></a>

                              <a href="/actions/eliminar_prepedido/<?= $p->idprepedido ?>" class="text-danger eliminar_prepedido" data-toggle="tooltip" title="Eliminar"><i class="fa fa-close"></i></a>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>

                </div>
                  
              </div>

            </div>
            <!-- /.box -->
          </div>
        <?php else: ?>
          <div class="col-xs-12">
            <div class="alert alert-info">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <strong>No tienes pedidos pendientes de aprobación</strong>
            </div>
          </div>
        <?php endif ?>


        <!-- pedidos en curso -->
        <?php if ($pedidos): ?>
          <div class="col-xs-12">
            <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title">Pedidos en curso</h3>
              </div>
              <div class="box-body">
                <div class="col-xs-12">
                  <div class="alert alert-info visible-xs visible-xs">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4 class="text-center">Deslice la tabla a la izquierda para ver los detalles completos</h4>
                  </div>
                  
                  <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped">
                      <thead>
                        <th>Fecha</th>
                        <th>Candidato</th>
                        <th>Servicio</th>
                        <th>DNI</th>
                        <th>Provincia</th>
                        <th>Vacante</th>
                        <th>Cliente</th>
                        <th>Solicitante</th>
                        <th class="text-center">Estado</th>
                      </thead>
                      <tbody>
                        <?php foreach ($pedidos as $p): ?>
                          <tr>
                            <td><?php echo _date($p->creado) ?></td>
                            <td><?php echo ($p->candidato) ? $p->candidato : '-' ?></td>
                            <td><?php echo ($p->servicio) ? $p->servicio : '-' ?></td>
                            <td><?php echo ($p->dni) ? $p->dni : '-' ?></td>
                            <td><?php echo $p->provincia ?> <br> <small><?= $p->localidad ?></small></td>
                            <td><?php echo ($p->vacante) ? $p->vacante : '-' ?></td>
                            <td><?php echo $p->cliente ?></td>
                            <td><?php echo $p->subcliente ?></td>
                            <td class="col-xs-2 text-center">
                              <span class="label label-info"><?php echo $p->estado ?></span> 
                              <button class="btn btn-sm btn-primary view-chat-history" data-idpedido="<?= $p->idpedido ?>" data-candidato="<?= $p->candidato ?>" data-servicio="<?= $p->servicio ?>" data-toggle="modal" data-target="#modal-chat" >
                                <i class="fa fa-wechat"></i>
                              </button>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
                  
              </div>

            </div>
            <!-- /.box -->
          </div>
        <?php else: ?>
          <div class="col-xs-12">
            <div class="alert alert-info visible-xs">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <strong>No tienes pedidos en curso</strong>
            </div>
          </div>
        <?php endif ?>

        <!-- pedidos finalizados -->
        <?php if ($finalizados): ?>
          <div class="col-xs-12">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title">Pedidos finalizados</h3>
              </div>
              <div class="box-body">
                <div class="col-xs-12">
                  <div class="alert alert-info visible-xs visible-xs">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4 class="text-center">Deslice la tabla a la izquierda para ver los detalles completos</h4>
                  </div>
                  
                  <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped">
                      <thead>
                        <th>Fecha</th>
                        <th>Candidato</th>
                        <th>Servicio</th>
                        <th>DNI</th>
                        <th>Provincia</th>
                        <th>Vacante</th>
                        <th>Cliente</th>
                        <th>Solicitante</th>
                        <th class="text-center">info</th>
                      </thead>
                      <tbody>
                        <?php foreach ($finalizados as $p): ?>
                          <tr>
                            <td><?php echo _date($p->creado) ?></td>
                            <td><?php echo ($p->candidato) ? $p->candidato : '-' ?></td>
                            <td><?php echo ($p->servicio) ? $p->servicio : '-' ?></td>
                            <td><?php echo ($p->dni) ? $p->dni : '-' ?></td>
                            <td><?php echo $p->provincia ?> <br> <small><?= $p->localidad ?></small></td>
                            <td><?php echo ($p->vacante) ? $p->vacante : '-' ?></td>
                            <td><?php echo $p->cliente ?></td>
                            <td><?php echo $p->subcliente ?></td>
                            <td class="col-xs-2 text-center">
                              <span class="label label-success"><?php echo $p->estado ?></span> 
                              <?php if ($p->facturado): ?>
                                <span class="label label-primary">facturado</span> 
                              <?php endif ?>

                              <?php if ($p->adjuntos): ?>
                                <?php foreach ($p->adjuntos as $a): ?>
                                  <a href="/uploads/<?= $a->filename ?>" target="_blank">
                                    <span class="label label-primary"><i class="fa fa-file-pdf-o"></i></span>
                                  </a>
                                <?php endforeach ?>
                                  
                                
                              <?php endif ?>
                              
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
                  
              </div>

            </div>
            <!-- /.box -->
          </div>
        <?php else: ?>
          <div class="col-xs-12">
            <div class="alert alert-info">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <strong>No tienes pedidos finalizados</strong>
            </div>
          </div>
        <?php endif ?>
         
      </div>

    </section>
    <!-- /.content -->

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
                  <input type="hidden" name="usuario_nm" id="usuario_nm" value="<?= ($tipo == 'cliente') ? $cliente->razon : $cliente->nombre ?>">                
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

  </div>
  <!-- /.content-wrapper -->

<?php require('footer.php') ?>

<script>

  $('.eliminar_prepedido').on('click', function(event) {
    var c = confirm("¿Realmente quiere eliminar este pedido? Esta acción no puede deshacerse");
    if (!c) {
      event.preventDefault();
    }      
  }); 

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


</script>