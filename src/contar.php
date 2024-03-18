<?php 
include '../config/conexion.php';
include '../config/helpers.php';
session_start();

$arrResponse = array("success" => "","status" => "","msg"=>""); 


//dep($_POST);die();
if($_POST){
$din = $_POST['din'];
$search = $_POST['search'];
$id = $_POST['id'];
$conteo	= $_POST['conteo'];


$update = "UPDATE adn_movinv SET MIN_FIS_CONTEO = $conteo WHERE MIN_ID = $id";
$sentencia=$pdo->prepare($update);
$sentencia->execute();

$_SESSION['info'] = "Conteo registrado";

header("Location:".base_url()."/src/productos.php?din=".$din."&search=".$search."#".$id);

}

?>