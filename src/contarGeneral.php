<?php
include '../config/conexion.php';
include '../config/helpers.php';
session_start();

$arrResponse = array("success" => "", "status" => "", "msg" => "");




if ($_POST) {
   
    $product_update = [];
    $din = $_POST['din'];

    foreach ($_POST['conteo'] as $index => $cant) {
    
        if ($cant !== '') {
             $id = $_POST['id'][$index];
    
    
            $update = "UPDATE adn_movinv SET MIN_FIS_CONTEO = $cant WHERE MIN_ID = $id";
            $sentencia = $pdo->prepare($update);
            $sentencia->execute();
    
        }
    }
    
    $_SESSION['info'] = "Conteo registrado";

    header("Location:" . base_url() . "/src/productos.php?din=" . $din);
}
