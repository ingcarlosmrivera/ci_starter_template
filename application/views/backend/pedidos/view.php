<?php require(__DIR__.'/../header.php') ?>
<?php require(__DIR__.'/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Ficha de Pedidos
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-user"></i> Pedidos</a></li>
        <li class="active">Ficha de Pedido</li>
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
              <h3 class="box-title">Ficha de Pedido - #<?= $pedido->idpedido ?></h3>
            </div>
            
            <div class="box-body">

              <div class="col-xs-12 col-md-2">
                <label class="no-margin" for="">Fecha</label>
                <h4 class="no-margin-top"><?= _date($pedido->creado) ?></h4>
              </div>

              <div class="col-xs-12 col-md-10">
                <label class="no-margin" for="">Servicio</label>
                <h4 class="no-margin-top"><?= $pedido->servicio ?></h4>
              </div>

              <div class="col-xs-12 col-md-3">
                <label class="no-margin" for="">Estado</label>
                <input type="hidden" name="idpedido" id="idpedido" value="<?= $pedido->idpedido ?>">
                <select name="estado" id="estado" class="form-control input-sm" disabled="disabled">
                  <?php if (is_admin()): ?>
                    <option value="porconfirmar" <?= ($pedido->estado == 'porconfirmar') ? 'selected' : '' ?>>Por confirmar</option>
                  <?php endif ?>
                  <option value="activado" <?= ($pedido->estado == 'activado') ? 'selected' : '' ?>>Activado</option>
                  <option value="analisis" <?= ($pedido->estado == 'analisis') ? 'selected' : '' ?>>Análisis</option>
                  <option value="finalizado" <?= ($pedido->estado == 'finalizado') ? 'selected' : '' ?>>Finalizado</option>
                </select>
              </div>

              <div class="col-xs-12 col-md-3">
                <label class="no-margin" for="">Candidato</label>
                <h4 class="no-margin-top"><?= $pedido->candidato ?></h4>
              </div>

              <div class="col-xs-12 col-md-3">
                <label class="no-margin" for="">DNI</label>
                <h4 class="no-margin-top"><?= $pedido->dni ?></h4>
              </div>

              <div class="col-xs-12 col-md-3">
                <label class="no-margin" for="">Teléfono</label>
                <h4 class="no-margin-top"><?= $pedido->telefono ?></h4>
              </div>

              <div class="clearfix"></div>

              <div class="col-xs-12 col-md-3">
                <label class="no-margin" for="">Email</label>
                <h4 class="no-margin-top"><?= $pedido->email ?></h4>
              </div>

              <div class="col-xs-12 col-md-3">
                <label class="no-margin" for="">Vacante</label>
                <h4 class="no-margin-top"><?= $pedido->vacante ?></h4>
              </div>

              <div class="col-xs-12 col-md-6">
                <label class="no-margin" for="">Direción</label>
                <h4 class="no-margin-top"><?= $pedido->direccion ?></h4>
              </div>

              <div class="col-xs-12 col-md-3">
                <label class="no-margin" for="">Provincia</label>
                <h4 class="no-margin-top"><?= $pedido->provincia ?></h4>
              </div>

              <div class="col-xs-12 col-md-3">
                <label class="no-margin" for="">Localidad</label>
                <h4 class="no-margin-top"><?= $pedido->localidad ?></h4>
              </div>

              <div class="col-xs-12 col-md-3">
                <label class="no-margin" for="">Cliente</label>
                <h4 class="no-margin-top"><?= $pedido->cliente ?></h4>
              </div>

              <div class="col-xs-12 col-md-3">
                <label class="no-margin" for="">Subcliente</label>
                <h4 class="no-margin-top"><?= $pedido->subcliente ?></h4>
              </div>

              <div class="col-xs-12 col-md-6 bg-info">
                <label class="no-margin" for="">Proveedor</label>
                <h4 class="no-margin-top"><?= $pedido->proveedor ?></h4>
              </div>

              <div class="col-xs-12 col-md-6 bg-info">
                <label class="no-margin" for="">Analista</label>
                <h4 class="no-margin-top"><?= $pedido->analista ?></h4>
              </div>

              <div class="col-xs-12">
                <h3>Observaciones</h3>
                <p class="text-justify"><?= $pedido->observaciones ?></p>
              </div>

              <div class="col-xs-12 col-md-6">
                <h3>Archivos adjuntos</h3>
                <?php if ($pedido->adjuntos): ?>
                  <?php foreach ($pedido->adjuntos as $a): ?>
                    <a href="/uploads/<?= $a->filename ?>" target='_blank' class='cotizacion'>
                      <span class="fa fa-file-pdf-o fa-3x text-primary pointer" data-toggle="tooltip" title="Archivo: <?= $a->filename ?>"></span>
                    </a>
                  <?php endforeach ?> 
                <?php else: ?>
                  <p>No hay archivos adjuntos</p>
                <?php endif ?>
                <div class="dropzone <?= (!$allow_upload) ? 'collapse' : '' ; ?>">
                  <h4 class="text-info dz-message" data-dz-message>Arrastra o selecciona uno o más archivos para cargarlos</h4>
                </div>
              </div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer text-right">
              <button class="btn btn-warning" id="btn_edit" onclick="habilitar_edicion(this)">
                <i class="fa fa-pencil"></i> 
                Editar estado del pedido
              </button>

              <?php if ($this->auth->is_admin()): ?>
                <a href="/backend/pedidos/edit/<?= $pedido->idpedido ?>" class="btn bg-purple" id="btn_edit">
                  <i class="fa fa-pencil"></i> 
                  Editar datos del pedido
                </a>
              <?php endif ?>

              <button class="btn btn-info view-chat-history" data-idpedido="<?= $pedido->idpedido ?>" data-candidato="<?= $pedido->candidato ?>" data-servicio="<?= $pedido->servicio ?>" data-toggle="modal" data-target="#modal-chat" >
                <i class="fa fa-wechat"></i> 
                Historial de mensajes
              </button>

              <button class="btn btn-danger collapse" id="btn_cancel" onclick="cancelar_edicion(this)">
                <i class="fa fa-close"></i> 
                Cancelar edición
              </button>

              <button class="btn btn-success collapse" id="btn_save" disabled="disabled">
                <i class="fa fa-check"></i> 
                Guardar Cambios
              </button>
            </div>

          </div>
          <!-- /.box -->
        </div>
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
                  <input type="hidden" name="idpedido_nm" id="idpedido_nm" value="<?= $pedido->idpedido ?>">
                  <input type="hidden" name="usuario_nm" id="usuario_nm" value="<?= get_user()->razon ?>">                
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

<?php require(__DIR__.'/../footer.php') ?>

<script>
  Dropzone.autoDiscover = false;

  $('.dropzone').dropzone({
    url: '/actions/upload_file',
    method: 'post',
    sending:function(file, xhr, formData){
      formData.append('id_pedido', <?= $pedido->idpedido ?> );
    }
  });

  var estado_inicial = $('#estado').val();

  if (estado_inicial == 'finalizado') {
    mostrar_dropzone();
  }

  $('#estado').on('change', function(event) {
    event.preventDefault();
    
    if ($(this).val() !== estado_inicial) {
      $('#btn_save').prop('disabled', false);
    }  else {
      $('#btn_save').prop('disabled', true);
    }

    if ($(this).val() == 'finalizado') {
      mostrar_dropzone();
    }  else {
      ocultar_dropzone();
    }


  });

  $('#btn_save').on('click', function(event) {
    event.preventDefault();
    $.ajax({
      url: '/actions/update_estado_pedido',
      type: 'POST',
      dataType: 'JSON',
      data: {idpedido: $('#idpedido').val(), estado_actual: estado_inicial, nuevo_estado: $('#estado').val()},
    })
    .done(function(data) {
      $('#modal_result').removeClass().addClass(data.class);
      $('#modal_result_icon').removeClass().addClass(data.icon);
      $('#modal_result_message').html(data.message);
      $('#modal_result').modal();

      if (data.status == 'success') {
        $("#modal_result").on('hide.bs.modal', function () {
          // location.assign('/backend/pedidos/<?= $pedido->estado ?>');
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
    
  });

  function mostrar_dropzone()
  {
    $('.dropzone').show();
  }

  function ocultar_dropzone()
  {
    $('.dropzone').hide();
  }


  function habilitar_edicion(button)
  {
    $(button).hide();
    $('#estado').prop('disabled', false);
    $('#btn_save, #btn_cancel').show();
  }

  function cancelar_edicion(button)
  {
    $(button).hide();
    $('#estado').prop('disabled', true);
    $('#btn_save, #btn_cancel').hide();
    $('#btn_edit').show();
  }

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