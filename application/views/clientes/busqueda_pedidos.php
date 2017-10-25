<?php require('header.php') ?>
<?php require('sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Búsqueda avanzada
        <!-- <small>Resumen de pedidos</small> -->
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
          <div class="box box-info">
            <div class="box-header with-border">
              <h4 class="box-title">Búsqueda de pedidos</h4>
            </div>
            <div class="box-body">
              <div class="row">
                <form class="form" name="form_buscar_pedido" id="form_buscar_pedido" method="post">
                  <div class="col-xs-12 col-md-2 form-group">
                    <label for="Fecha">Fecha </label>
                    <input type="text" class="form-control datepicker" name="creado" id="creado" placeholder="Fecha pedido" value="<?=(isset($datos->creado)) ? $datos->creado : '' ?>">
                  </div>

                  <div class="form-group col-xs-12 col-md-2">
                    <label for="">Candidato</label>
                    <input type="text" class="form-control" id="candidato" name="candidato" placeholder="Candidato" value="<?=(isset($datos->candidato)) ? $datos->candidato : '' ?>">
                  </div>

                  <div class="form-group col-xs-12 col-md-2">
                    <label for="">DNI</label>
                    <input type="text" class="form-control" id="dni" name="dni" placeholder="DNI" value="<?=(isset($datos->dni)) ? $datos->dni : '' ?>">
                  </div>

                  <div class="form-group col-xs-12 col-md-2">
                    <label for="">Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="<?=(isset($datos->email)) ? $datos->email : '' ?>">
                  </div>

                  <div class="form-group col-xs-12 col-md-2">
                    <label for="">Vacante</label>
                    <input type="text" class="form-control" id="vacante" name="vacante" placeholder="Vacante" value="<?=(isset($datos->vacante)) ? $datos->vacante : '' ?>">
                  </div>

                  <div class="form-group col-xs-12 col-md-2">
                    <label for="">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono" value="<?=(isset($datos->telefono)) ? $datos->telefono : '' ?>">
                  </div>

                  <div class="col-xs-12 text-right">
                    <a href="/external/busqueda" class="btn btn-danger btn-flat"><i class="fa fa-close"></i> Limpiar</a>
                    <button type="submit" class="btn btn-success btn-flat"><i class="fa fa-search"></i> Buscar</button>
                  </div>

                </form>
              </div>

              <?php if (isset($pedidos)): ?>

                  <div class="row" style="margin-top:20px">
                    <?php if ($pedidos): ?>

                      <div class="col-xs-12">
                        <div class="alert alert-info visible-xs">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          <h4 class="text-center">Deslice la tabla a la izquierda para ver los detalles completos</h4>
                        </div>
                        <div class="table-responsive">
                          <table class="table table-bordered table-striped table-hover">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Candidato</th>
                                <th>DNI</th>
                                <th>Provincia</th>
                                <th>Email</th>
                                <th>Vacante</th>
                                <th>Servicio</th>
                                <th>Status</th>
                                <th>Archivos</th>
                              </tr>
                            </thead>

                            <tbody>
                              <?php foreach ($pedidos as $p): ?>
                                <tr>
                                  <td><?php echo $p->idpedido ?></td>
                                  <td><?php echo _date($p->creado) ?></td>
                                  <td><?php echo $p->candidato ?></td>
                                  <td><?php echo $p->dni ?></td>
                                  <td><?php echo $p->provincia ?> <br> <small><?= $p->localidad ?></small></td>
                                  <td><?php echo $p->email ?></td>
                                  <td><?php echo $p->vacante ?></td>
                                  <td><?php echo $p->servicio ?></td>
                                  <td><span class="label label-default"><?= $p->estado ?></span></td>
                                  <td>
                                    <button class="btn btn-sm btn-info view-chat-history" data-idpedido="<?= $p->idpedido ?>" data-candidato="<?= $p->candidato ?>" data-servicio="<?= $p->servicio ?>" data-toggle="modal" data-target="#modal-chat" >
                                      <i class="fa fa-wechat"></i>
                                    </button>
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
                    <?php else: ?>
                      <div class="col-xs-12" >
                        <div class="alert alert-danger" role="alert">
                          <h4 class="no-margin"><i class="fa fa-close"></i> No se encontraron resultados</h4>
                        </div>
                      </div>

                    <?php endif; ?>
                  </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <!-- /.col -->
      </div>
         
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
                <h5 class="no-margin" id="candidato2"></h5>
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

    </section>
    <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->

<?php require('footer.php') ?>

<script>
  $(document).ready(function(){
    $('.datepicker').datepicker({
      autoclose: "true",
      clearBtn: "true",
      endDate: "0d",
      format: "yyyy-mm-dd",
      todayHighlight: "true"
    });
  })

  // chat
  $('.view-chat-history').on('click', function(event) {
    event.preventDefault();
    var id = $(this).data('idpedido');
    var candidato = $(this).data('candidato');
    var servicio = $(this).data('servicio');

    console.log(id, candidato, servicio)

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
        $('#candidato2').html(candidato);
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