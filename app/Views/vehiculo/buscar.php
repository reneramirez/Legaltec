<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid">
      <h1 class="mt-4">Busca tu vehículo</h1>
      <div class="card mb-4">
        <div class="card-body">
          Ingresa tu patente y revisa el estado de tu veh&iacute;culo.
        </div>
      </div>
      <div class="card mb-4">
        <div class="card-body">
          <form>
            <div class="row mb-3">
              <label for="inpPatente" class="col-sm-2 col-form-label">Patente</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="inpPatente" name="inpPatente">
              </div>
              <div class="col-sm-3">
                <button type="button" class="btn btn-primary" onclick="getVehiculo()">BUSCAR</button>
              </div>
            </div>
            <div class="row mb-6">
              <div class="col-md-12">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th class="text-center" scope="col">PATENTE</th>
                      <th class="text-center" scope="col">VEHÍCULO</th>
                      <th class="text-center" scope="col">VALOR PERMISO</th>
                      <th class="text-center" scope="col">INTERESES Y REAJUSTES</th>
                      <th class="text-center" scope="col">REGISTRO DE MULTAS IMPAGAS</th>
                      <th class="text-center" scope="col">SUBTOTAL</th>
                    </tr>
                  </thead>
                  <tbody id="tbody">

                  </tbody>
                </table>
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
          var marca = data.vehiculo.glosa_marca;
          var modelo = data.vehiculo.glosa_modelo;
          var permiso = 0;
          var servicio = 0;
          var multa = 0;
          var total = 0;

          var html = "";
          $.each(data.detalle, function(index, value) {
            if (value.id_tipo_servicio == 1) {
              permiso= value.monto;
            }
            if (value.id_tipo_servicio == 2) {
              servicio = value.monto;
            }
            if (value.id_tipo_servicio == 3) {
              multa = value.monto;
            }
          }); 
          total = permiso + servicio + multa;
          html += ` <tr>
                      <td class="text-left">${patente}</td>
                      <td class="text-left">${marca} ${modelo}</td>
                      <td class="text-center">${permiso}</td>
                      <td class="text-center">${servicio}</td>
                      <td class="text-center">${multa}</td>
                      <td class="text-center">${total}</td>
                    </tr>`; 
          $("#tbody").find('tr').remove(); 
          $("#tbody").append(html);
      },
      "json"
  );
  }
</script>