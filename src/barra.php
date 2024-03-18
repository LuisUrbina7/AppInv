<?php 

session_start();

include '../config/parametros.php';
include '../config/helpers.php';
require_once('../config/validar_session.php');

$din = $_GET['din'];

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Leer código de barras con JavaScript by parzibyte</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
  </head>


  <style type="text/css">
    video {
      width: 100%;
      height: 40%;
    }

    canvas{
      display: none;
    }
  </style>


  <body>
    <p hidden id="resultado">Aquí aparecerá el código</p>
    <p hidden >A continuación, el contenedor: </p>
    <div id="contenedor"></div>
    <!-- Cargamos Quagga y luego nuestro script -->
    <script src="https://unpkg.com/quagga@0.12.1/dist/quagga.min.js"></script>
    <script src="script.js"></script>
  </body>
</html>

<script type="text/javascript">
  
  document.addEventListener("DOMContentLoaded", () => {
  const $resultados = document.querySelector("#resultado");
  Quagga.init({
    inputStream: {
      constraints: {
        width: 1920,
        height: 1080,
      },
      name: "Live",
      type: "LiveStream",
      target: document.querySelector('#contenedor'), // Pasar el elemento del DOM
    },
    decoder: {
      readers: ["ean_reader"]
    }
  }, function (err) {
    if (err) {
      console.log(err);
      return
    }
    console.log("Iniciado correctamente");
    Quagga.start();
  });

  Quagga.onDetected((data) => {
    location.href = '<?= BASE_URL ?>/src/productos.php?din=<?= $din; ?>&search='+data.codeResult.code;
    // Imprimimos todo el data para que puedas depurar
    console.log(data);
  });

  Quagga.onProcessed(function (result) {
    var drawingCtx = Quagga.canvas.ctx.overlay,
      drawingCanvas = Quagga.canvas.dom.overlay;

    if (result) {
      if (result.boxes) {
        drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
        result.boxes.filter(function (box) {
          return box !== result.box;
        }).forEach(function (box) {
          Quagga.ImageDebug.drawPath(box, { x: 0, y: 1 }, drawingCtx, { color: "green", lineWidth: 2 });
        });
      }

      if (result.box) {
        Quagga.ImageDebug.drawPath(result.box, { x: 0, y: 1 }, drawingCtx, { color: "#00F", lineWidth: 2 });
      }

      if (result.codeResult && result.codeResult.code) {
        Quagga.ImageDebug.drawPath(result.line, { x: 'x', y: 'y' }, drawingCtx, { color: 'red', lineWidth: 3 });
      }
    }
  });
});

</script>