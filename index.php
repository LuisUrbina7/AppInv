<?php 

include 'config/globals.php';
include 'config/helpers.php';
session_start();
error_reporting(0);

if($_SESSION['USUARIO']){
  header("location:src/index.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="<?= media() ?>/img/apple-icon.png">
  <link rel="icon" type="image/png" href="<?= media() ?>/img/favicon.png">
  <title>
    App Inventario Fisico
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="<?= media() ?>/css/nucleo-icons.css" rel="stylesheet" />
  <link href="<?= media() ?>/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="<?= media() ?>/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="<?= media() ?>/css/soft-ui-dashboard.css?v=1.0.5" rel="stylesheet" />
  <link id="pagestyle" href="<?= media() ?>/css/styles.css" rel="stylesheet" />
  <link id="pagestyle" href="<?= media() ?>/css/sweetalert.css" rel="stylesheet" />
</head>


<!--loading -->
<div id="divLoading">
  <div>
    <img src="<?= media(); ?>/img/loading.svg" alt="Loading">
  </div>
</div>


<body class="">
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
        
      </div>
    </div>
  </div>
  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-75">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
              <div class="card card-plain mt-8">
                <div class="card-header pb-0 text-left bg-transparent">
                  <h3 class="font-weight-bolder text-info text-gradient">App Inventario Fisico</h3>
                  <p class="mb-0">Ingresa tu usuario y Clave</p>
                </div>
                <div class="card-body">
                  <form role="form" action="" id="formLogin" method="post">
                    <label>Usuario</label>
                    <div class="mb-3">
                      <input type="text" class="form-control" placeholder="Usuario" name="usuario" id="usuario" aria-label="Usuario" aria-describedby="email-addon">
                    </div>
                    <label>Clave</label>
                    <div class="mb-3">
                      <input type="password" class="form-control" placeholder="Clave" name="clave" id="clave" aria-label="Clave" aria-describedby="password-addon">
                    </div>
                    
                    <div class="text-center">
                      <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Ingresar</button>
                    </div>

                    <div class="card-header pb-0 text-left bg-transparent" style="display: flex;justify-content: center;align-items: center;">
                      <img src="<?= media() ?>/img/logo-adn-azul.png">
                    </div>
                  </form>
                </div>
                
              </div>
            </div>
            <div class="col-md-6" style="height: 100vh;">
              <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8" style="clip-path: polygon(100% 0%, 100% 51%, 100% 100%, 10% 100%, 0% 50%, 10% 0);">
                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 " style="background-image:url('<?= media() ?>/img/curved-images/curved7.jpg')"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <footer class="footer py-5">
    <div class="container">

      <div class="row">
        <div class="col-8 mx-auto text-center mt-1">
          <p class="mb-0 text-secondary">
            Copyright © <script>
              document.write(new Date().getFullYear())
            </script> ADN Software.
          </p>
        </div>
      </div>
    </div>
  </footer>
  <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <!--   Core JS Files   -->
  <script src="<?= media() ?>/js/core/popper.min.js"></script>
  <script src="<?= media() ?>/js/core/bootstrap.min.js"></script>
  <script src="<?= media() ?>/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="<?= media() ?>/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="<?= media() ?>/js/plugins/jquery-3.3.1.min.js"></script>
  <script src="<?= media() ?>/js/plugins/sweetalert.min.js"></script>
  <script src="<?= media() ?>/js/soft-ui-dashboard.min.js?v=1.0.5"></script>


  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>

  <script type="text/javascript">

    var base_url = <?= "'".BASE_URL."'" ?>;
    
    let divLoading = document.querySelector("#divLoading");

    $('#formLogin').submit(function(e) {
        e.preventDefault();
        
        let usuario = document.querySelector('#usuario').value;
        let clave = document.querySelector('#clave').value;      
        
        var params = {
            "usuario" : usuario,
            "clave" : clave
        };

        $.ajax({
            type: "POST",
            url: 'config/login.php',
            data: params,
            beforeSend: function () {

              divLoading.style.display = "flex";

          },
          success: function(response)
          {
            var jsonData = JSON.parse(response);
            if (jsonData.login == "true")
            {
                divLoading.style.display = "none";
                window.location.assign(base_url+"/src/index.php")
            }
            else
            {
                divLoading.style.display = "none";
                swal("Atención",jsonData.msg, "error");
            }
        }
    });
    });
</script>



</body>

</html>