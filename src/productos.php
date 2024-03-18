﻿<?php

session_start();
error_reporting(0);

include '../config/parametros.php';
include '../config/helpers.php';
require_once('../config/validar_session.php');

$din = $_GET['din'];
$search = empty($search = $_GET['search']) ? "" : $_GET['search'];

if (!empty($search)) {
  $sql = "SELECT 
MIN_ID,  
PDT_CODIGO,
PDT_DESCRIPCION,
MIN_FIS_CONTEO AS  MIN_FIS_CONTEO
FROM ADN_DOCINV 
INNER JOIN ADN_MOVINV ON DIN_NUMERO = MIN_DIN_NUMERO AND DIN_TDT_CODIGO = MIN_DIN_TDT_CODIGO
INNER JOIN ADN_PRODUCTOS ON MIN_UPP_PDT_CODIGO = PDT_CODIGO
LEFT JOIN adn_barras ON pdt_codigo = bar_ugr_pdt_codigo 
WHERE DIN_AJUSTE = 1 
AND DIN_ACTIVO = 1
AND DIN_TDT_CODIGO = 'INV'
AND DIN_NUMERO = '$din'
AND (PDT_CODIGO LIKE '%$search%' OR PDT_DESCRIPCION LIKE '%$search%' OR BAR_BARRA LIKE '%$search%')
";
} else {
  $sql = "SELECT 
MIN_ID,
PDT_CODIGO,
PDT_DESCRIPCION,
MIN_FIS_CONTEO AS  MIN_FIS_CONTEO
FROM ADN_DOCINV 
INNER JOIN ADN_MOVINV ON DIN_NUMERO = MIN_DIN_NUMERO AND DIN_TDT_CODIGO = MIN_DIN_TDT_CODIGO
INNER JOIN ADN_PRODUCTOS ON MIN_UPP_PDT_CODIGO = PDT_CODIGO
LEFT JOIN adn_barras ON pdt_codigo = bar_ugr_pdt_codigo 
WHERE DIN_AJUSTE = 1 
AND DIN_ACTIVO = 1
AND DIN_TDT_CODIGO = 'INV'
AND DIN_NUMERO = '$din'";
}



$sentencia = $pdo->prepare($sql);
$sentencia->execute();
$listaProductos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

headerCajero();

if ($_SESSION['info']) {
  /*echo '<script>swal("Exito!","'.$_SESSION['info'].'", "success");</script>';
  unset($_SESSION['info']);*/
}
?>

<div id="divLoading">
  <div>
    <img src="<?= media(); ?>/img//loading.svg" alt="Loading"> &nbsp;&nbsp;&nbsp;
  </div>
  <div>&nbsp;&nbsp;&nbsp;<h6>Asignando Por Favor espere</h6>
  </div>
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
                <h6>Ajustes de Inventario <?= substr($din, -10) ?></h6>

                <form action="" method="get">
                  <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                    <div class="input-group">
                      <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                      <input type="hidden" name="din" value="<?= $din ?>">
                      <input type="text" class="form-control" placeholder="Buscar" name="search" value="<?= $search ?>" onfocus="focused(this)" onfocusout="defocused(this)">
                      <a href="<?= BASE_URL_BARRA ?>?din=<?= $din ?>" class="btn btn-secondary m-auto" type="button" id="button-addon2">Barra</a>
                    </div>
                  </div>
                </form>

                <div class="table-responsive">
                  <table class="table align-items-center mb-0" id="table">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Código</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Descripcion</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cantidad</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Procesar</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php foreach ($listaProductos as $key => $value) { ?>

                        <tr>
                          <td>
                            <div class="d-flex px-2 py-1">
                              <div>
                                <!-- <i style="color: green;" class="fa-solid fa-circle-check"></i>&nbsp;&nbsp; -->
                              </div>
                              <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm"><?= $value['PDT_CODIGO'] ?></h6>
                              </div>
                            </div>
                          </td>
                          <td>
                            <h6 class="mb-0 text-sm"><?= strtolower($value['PDT_DESCRIPCION']) ?></h6>
                          </td>

                          <input type="hidden" step="0.01" name="conteo" id="conteo" class="form-control" style="padding-left: 20px !important;" min="0" placeholder="<?= $value['MIN_FIS_CONTEO'] ?>" required>
                          <form action="contar.php" method="post" onsubmit="return confirmation()">
                            <td class="align-middle text-center text-sm">
                              <div class="input-group">

                                <input onkeypress="copy()" type="number" step="0.01" name="conteo" id="conteo" class="form-control copy" style="padding-left: 20px !important;" min="0" placeholder="<?= $value['MIN_FIS_CONTEO'] ?>" required>

                              </div>
                            </td>
                            <td class="align-middle text-center text-sm">
                              <input type="hidden" name="din" value="<?= $din ?>">
                              <input type="hidden" name="search" value="<?= $search ?>">
                              <input type="hidden" name="id" value="<?= $value['MIN_ID'] ?>">
                              <?php if ($value['MIN_FIS_CONTEO'] == 0) { ?>
                                <button type="submit" class="btn btn-outline-success" style="margin: auto;" type="button" id="">Contar</button>
                              <?php } else { ?>
                                <button type="submit" class="btn btn-success" style="margin: auto;" type="button" id="<?= $value['MIN_ID'] ?>">Contado</button>
                              <?php } ?>
                          </form>

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

      $(document).ready(function(){

        $("#table-new").on('click', 'tr', function(e) {



        } );





      });
      function confirmation() {
        if (!confirm("Realmente desea registrar conteo?")) return false;
      }

      document.addEventListener('DOMContentLoaded', function() {
        var tabla = document.getElementById('table');


        var fila = tabla.getElementsByTagName('tr');


     

        console.log(fila.length);
        console.log(fila[2].cells[2].addEventListener( "click", function(value){
        alert('hola');

        console.log(this);
        }));

      });

      function copy() {

        let data = $(this).find('.copy');

        console.log(data);

      }

      <?php if ($_SESSION['info']) { ?>
        swal("Exito!", "<?= $_SESSION['info'] ?>", "success");
      <?php unset($_SESSION['info']);
      } ?>
    </script>