<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid">
      <h1 class="mt-4">Ingreso de Permiso/Intereses y reajustes/ Multas impagas</h1>
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
          <form id="myform" method="POST" action="<?= site_url("vehiculo/ingreso") ?>">
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
                <select name="slcMarca" id="slcMarca" class="form-control" onchange="loadModelos(this.value)" require>
                  <option value="">Seleccionar Marca</option>
                  <?php foreach ($marcas as $value) : ?>
                    <option value="<?php echo $value['id_marca'] ?>"><?php echo $value['glosa_marca'] ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="inpModelo" class="col-sm-2 col-form-label">Modelo</label>
              <div class="col-sm-3">
                <select name="slcModelo" id="slcModelo" class="form-control" require>
                  <option value="">Seleccionar Modelo</option>
                </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="inpPermiso" class="col-sm-2 col-form-label">Valor permiso</label>
              <div class="col-sm-3">
                <input type="number" class="form-control text-right" id="inpPermiso" name="inpPermiso" value="0" require>
              </div>
            </div>
            <div class="row mb-3">
              <label for="inpInteresReajuste" class="col-sm-2 col-form-label">Intereses y reajustes</label>
              <div class="col-sm-3">
                <input type="number" class="form-control text-right" id="inpInteresReajuste" name="inpInteresReajuste" value="0" require>
              </div>
            </div>
            <div class="row mb-3">
              <label for="inpMultas" class="col-sm-2 col-form-label">Multas impagas</label>
              <div class="col-sm-3">
                <input type="number" class="form-control text-right" id="inpMultas" name="inpMultas" value="0" require>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-3 offset-md-2 ">
                <button type="submit" class="btn btn-primary" style="width: 100%;">GUARDAR</button>
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
        slcMarca: "required",
        slcModelo: "required",
      },
      messages: {
        inpPatente: `<p class="text-danger">requerido</p>`,
        slcMarca: `<p class="text-danger">requerido</p>`,
        slcModelo: `<p class="text-danger">requerido</p>`,
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
        $("#slcMarca").val(data.vehiculo.id_marca);
        loadModelos(data.vehiculo.id_marca, data.vehiculo.id_modelo);
        $("#hdnIdVehiculo").val(data.vehiculo.id_vehiculo);
        $.each(data.detalle, function(index, value) {
          console.log(value);
          if (value.id_tipo_servicio == 1) {
            $("#inpPermiso").val(value.monto);
            $("#hdnIdPermiso").val(value.id_vehiculo_detalle);
          }
          if (value.id_tipo_servicio == 2) {
            $("#inpInteresReajuste").val(value.monto);
            $("#hdnIdInteres").val(value.id_vehiculo_detalle);
          }
          if (value.id_tipo_servicio == 3) {
            $("#inpMultas").val(value.monto);
            $("#hdnIdMulta").val(value.id_vehiculo_detalle);
          }
        });
      },
      "json"
    );
  }
</script>