<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid">
      <h1 class="mt-4">Pagos de Permiso/Intereses y reajustes/ Multas impagas</h1>
      <div class="card mb-4">
        <div class="card-body">
          Ingresa tu patente y revisa el estado de tu veh&iacute;culo.
        </div>
      </div>
      <div class="card mb-4">
        <div class="alert <?php echo $msj['class'] ?>" role="alert">
          <?php echo $msj['text'] ?>
        </div>

        <div class="card-body">
          <form id="myform" method="POST" action="<?= site_url("vehiculo/pagos") ?>">
            <input type="hidden" id="hdnIdVehiculo" name="hdnIdVehiculo" value="">
            <input type="hidden" id="hdnIdPermiso" name="hdnIdPermiso" value="">
            <input type="hidden" id="hdnIdInteres" name="hdnIdInteres" value="">
            <input type="hidden" id="hdnIdMulta" name="hdnIdMulta" value="">
            <div class="row mb-3">
              <label for="inpPatente" class="col-sm-2 col-form-label">Patente</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="inpPatente" name="inpPatente" require>
              </div>
              <div class="col-sm-3">
                <button type="button" class="btn btn-primary" onclick="getVehiculo()">BUSCAR</button>
              </div>
            </div>
            <div class="row mb-3">
              <label for="inpMarca" class="col-sm-2 col-form-label">Marca</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="inpMarca" name="inpMarca" disabled>
              </div>
            </div>
            <div class="row mb-3">
              <label for="inpModelo" class="col-sm-2 col-form-label">Modelo</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="inpModelo" name="inpModelo" disabled>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th class="text-center" scope="col">#</th>
                      <th class="text-center" scope="col">Glosa</th>
                      <th class="text-center" scope="col">Monto</th>
                      <th class="text-center" scope="col">Pagar</th>
                    </tr>
                  </thead>
                  <tbody id="tbody">

                  </tbody>
                </table>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-3 offset-md-2 ">
                <button type="submit" class="btn btn-primary" style="width: 100%;">PAGAR</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
</div>
</div>
<script>
  $(function() {
    $("#myform").validate({
      rules: {
        inpPatente: "required",
      },
      messages: {
        inpPatente: `<p class="text-danger">requerido</p>`,
      },
      submitHandler: function(form) {
        $("#myform").submit();
      }
    });
  });

  function loadModelos(id_marca, id_modelo = null) {
    $.post("<?php echo site_url('vehiculo/loadModelos') ?>", {
        marca: id_marca
      },
      function(data) {
        var html = `<option value="">Seleccionar Modelo</option>`;
        $.each(data, function(index, value) {
          selected = value.id_modelo == id_modelo ? 'selected' : '';
          html += `<option value="${value.id_modelo}" ${selected}>${value.glosa_modelo}</option>`;
        });
        $("#slcModelo").find('option').remove();
        $("#slcModelo").append(html);
      },
      "json"
    );
  }

  function getVehiculo() {
    var patente = $("#inpPatente").val();
    if (!patente) {
      alert('Debe ingresar la patente del vehículo');
      return false;
    }
    $.post("<?php echo site_url('vehiculo/getDataVehiculo') ?>", {
        patente: patente
      },
      function(data) {
        $("#inpMarca").val(data.vehiculo.glosa_marca);
        $("#inpModelo").val(data.vehiculo.glosa_modelo);
        $("#hdnIdVehiculo").val(data.vehiculo.id_vehiculo);
        var html = "";
        $.each(data.detalle, function(index, value) {
          let checked = value.id_estado_pago == 2 ? 'checked disabled' : '';
          html += ` <tr>
                      <td class="text-center">${index +1}</td>
                      <td class="text-center">${value.glosa_tipo_servicio}</td>
                      <td class="text-center">${value.monto}</td>
                      <td class="text-center"><input type="checkbox" class="form-check-input" name="id_vehiculo_detalle[]" value="${value.id_vehiculo_detalle}" ${checked}></td>
                    </tr>`;
        });
        $("#tbody").find('tr').remove();
        $("#tbody").append(html);
      },
      "json"
    );
  }
</script>