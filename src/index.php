<?php

session_start();
include '../config/parametros.php';
include '../config/helpers.php';
require_once('../config/validar_session.php');

$sql = "SELECT 
DIN_NUMERO,
DIN_FECHA
FROM ADN_DOCINV 
WHERE DIN_AJUSTE = 1 
AND DIN_ACTIVO = 1
AND DIN_TDT_CODIGO = 'INV'
AND DIN_ESPERA IN(0,1)
ORDER BY DIN_NUMERO DESC;";
$sentencia=$pdo->prepare($sql);
$sentencia->execute();
$listaAjustes=$sentencia->fetchAll(PDO::FETCH_ASSOC);

headerCajero();
?>
<div id="divLoading" >
  <div>
    <img src="<?= media(); ?>/img//loading.svg" alt="Loading"> &nbsp;&nbsp;&nbsp;
  </div>
  <div>&nbsp;&nbsp;&nbsp;<h6>Asignando Por Favor espere</h6></div>
</div>

<!-- End Navbar -->
<div class="container-fluid py-4">
  <div class="row">

   <div class="row my-4">

    <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
      <div class="card">
        <div class="card-header p-0 py-2">
          <div class="row">
            <div class="col-lg-12 col-12">
              <h6>Ajustes de Inventario</h6>
              <p class="text-sm mb-0">                    
                <span class="font-weight-bold ms-1">Ajustes Abiertos</span> 

                <div class="table-responsive">
                  <table class="table align-items-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Número</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Fecha</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php foreach ($listaAjustes as $key => $value) { ?>

                        <tr>
                          <td>
                            <div class="d-flex px-2 py-1">
                              <div>
                                <!-- <i style="color: green;" class="fa-solid fa-circle-check"></i>&nbsp;&nbsp; -->
                              </div>
                              <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm"><?= substr($value['DIN_NUMERO'], -10) ?></h6>
                              </div>
                            </div>
                          </td>
                          <td>
                            <h6 class="mb-0 text-sm"><?= $value['DIN_FECHA'] ?></h6>
                          </td>
                          
                          <td style="width: 5%;" class="align-middle text-center text-sm">
                            <a href="productos.php?din=<?= $value['DIN_NUMERO'] ?>" class="btn btn-sm btn-info" style="margin: auto;" type="button" id="">ver</a>
                          </td>
                        </tr>

                      <?php } ?>               

                    </tbody>
                  </table>
                </div>
              </p>
            </div>
          </div>
        </div>
        <div class="card-body">



        </div>
      </div>
    </div>

  </div>
  <?php 

  footerCajero();

  ?>

  <script type="text/javascript">

    function confirmation() {
      if(!confirm("Realmente desea registrar conteo?")) return false; 
    }
  </script>

  <script type="text/javascript">
    let divLoading = document.querySelector("#divLoading");

    $('#formAsignacion').submit(function(e) {
      e.preventDefault();
      let id = document.querySelector('#id').value;
      var params = {
        "id" : id
      };

      $.ajax({
        type: "POST",
        url: 'asignacion.php',
        data: params,
        beforeSend: function () {

          divLoading.style.display = "flex";

        },
        success: function(response)
        {
          var jsonData = JSON.parse(response);
          if (jsonData.success == "1")
          {
            divLoading.style.display = "none";
            location.reload();
          }
          else
          {
            alert('No se pudo asignar!');
          }
        }
      });
    });
  </script>
