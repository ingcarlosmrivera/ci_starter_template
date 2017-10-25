<?php require(__DIR__.'/../header.php') ?>
<?php require(__DIR__.'/../sidebar.php') ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Generar nueva orden de compra
        <!-- <small>Optional description</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-line-chart"></i> Facturación</a></li>
        <li class="active">Nueva orden de compra</li>
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
              <h3 class="box-title">Nueva orden de compra</h3>
            </div>
            <div class="box-body">
              <div class="row">
                <form class="form" name="form_search" id="form_search" method="post">
                  <div class="form-group col-xs-12 col-sm-6">
                    <label>Seleccionar proveedor:</label>
                    <select class="form-control" name="proveedor" id="proveedor">
                    </select>
                  </div>

                </form>                        
              </div>

              <div class="alert alert-danger collapse" id="no_results">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>No se encontraron pedidos pendientes de facturar para este proveedor</strong>
              </div>

              <div class="alert alert-info total collapse">
                <strong>Costo total para <span class="cantidad"></span> pedidos seleccionados: <b>ARS <span class="costo_total"></span></b></strong>
              </div>

              <table class="table table-bordered collapse" id="table">
                <thead>
                  <tr>
                    <th class="text-center"> - </th>
                    <th>Fecha</th>
                    <th>Candidato</th>
                    <th>Servicio</th>
                    <th>Costo</th>
                    <th>Estado</th>
                  </tr>
                </thead>

                <tbody>
                  
                </tbody>

                <tfoot class="collapse">
                  <tr>
                    <td colspan="8">
                      <a href="#" data-action="check" id="all" onclick="checkall(this)"><span class="fa fa-hand-pointer-o"></span> Seleccionar/Deseleccionar todos
                      </a>
                    </td>
                  </tr>

                  <tr>
                    <td colspan="8" id="facturar" class="collapse text-right">
                      <form  method="POST" class="form-inline" role="form" name="form" id="form">                      
                        <button type="submit" id="btn" class="btn btn-success">Generar Orden de Compra</button>
                      </form>
                    </td>
                  </tr>
                </tfoot>
              </table>

              <div class="alert alert-info total collapse">
                <strong>Costo total para <span class="cantidad"></span> pedidos seleccionados: <b>ARS <span class="costo_total"></span></b></strong>
              </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">

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
  $("#proveedor").select2({
    theme: "bootstrap",
    allowClear: true,
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
  })

  $("#proveedor").on('change', function(event) {
    event.preventDefault();
    var v = $(this).val();

    if (v !== null && v !== undefined) {
      $('#table').show();

      $.ajax({
        url: '/actions/buscar_pedidos_no_ordenados/' + v,
        dataType: 'JSON',
      })
      .done(function(data) {
        $('#table tbody').empty();
        $('.checkbox').off('change');
        if (data) {
          $('#no_results').hide()
          $('#table tfoot').show()

          $.each(data, function(i, item) {
            var fecha = moment(item.creado).format('DD/MM/YYYY');
            var checkbox = "<input type='checkbox' class='checkbox' name='pedidos[]' data-costo='"+item.costo+"' data-precio='"+item.precio+"' value='"+item.idpedido+"'>";
            var $tr = $('<tr>').append(
              $('<td class="text-center">').html(checkbox),
              $('<td>').text(fecha),
              $('<td>').text(item.candidato),
              $('<td>').text(item.servicio),
              $('<td>').text('ARS ' + item.costo),
              $('<td>').html('<span class="label label-primary">'+item.estado+'</span>')

            ).appendTo('#table tbody');
          });

          $('.checkbox').on('change', function(event) {
            event.preventDefault();
            if ($('.checkbox:checked').length > 0) {
              $('#facturar').show();

              var total = 0;
              var n = $('.checkbox:checked').length;

              $.each($('.checkbox:checked'), function(index, val) {
                 total += $(this).data('costo');
              });

              $('.total').show('fast');
              $('.costo_total').html(total);
              $('.cantidad').html(n);
            } else {
              $('#facturar, .total').hide('fast');
            }
          });

        } else {
          $('#no_results').show();
          $('#table tfoot').hide();
        }
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
      
    }  else {
      $('#table').hide();
    }   
  });

  function checkall(btn)
  {
    event.preventDefault();
    var action = $(btn).data('action');
    if (action == 'check') {
      $('.checkbox').prop('checked', true);
      $(btn).data('action', 'uncheck');
    } else {
      $('.checkbox').prop('checked', false);
      $(btn).data('action', 'check');
    }

    $('.checkbox:first').trigger('change');
  }

  function facturar()
  {
    var data = {idproveedor: $('#proveedor').val(), pedidos: $(".checkbox").map(function(){return $(this).val()}).get()}
  }

  $('#form').validate({
    submitHandler: function(form) {
      $('#btn').prop('disabled', true);
      var data = {idproveedor: $('#proveedor').val(), pedidos: $(".checkbox:checked").map(function(){return $(this).val()}).get()};

      $.ajax({
        type: 'POST',
        dataType: 'html',
        data: data,
      })
      .done(function(data) {
        location.reload();
      })
      .fail(function() {
        alert('ha ocurrido un error');
        location.reload();
      })
      .always(function() {
        console.log("complete");
      });
      

      return false;
    }
  })

    
</script>