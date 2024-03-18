<?php 

include 'globals.php';
include 'helpers.php';

$arrResponse = array("login" => "","status" => "","msg"=>""); 

$conexion = mysqli_connect(SERVIDOR, USUARIO, PASSWORD, BD);
if (!$conexion) {
	echo "error al conectar con la base de datos";
}
else {

	session_start();

	$usuario=strtoupper($_POST["usuario"]);
	$clave=$_POST["clave"];


	$sql=" SELECT a.ope_numero as id, a.ope_nombre as usuario, a.ope_clave as clave, a.ope_activo as activo FROM sistemasadn.`adn_usuarios` as a where a.ope_nombre = '$usuario' and a.ope_clave = '$clave'";
	$resultado=mysqli_query($conexion, $sql);
	$datos=mysqli_fetch_assoc($resultado);
	$filas=mysqli_num_rows($resultado);

	//dep($datos); die();

		if($filas > 0  ){

			if($datos['activo'] == 1){
				$_SESSION['USUARIO']=$datos['usuario'];
				$_SESSION['ID_USUARIO']=$datos['id'];

				$arrResponse['login'] = "true";
		        $arrResponse['status'] = "200";
		        $arrResponse['msg'] = "Login Success";
		        echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}else {
				$arrResponse['login'] = "false";
		        $arrResponse['status'] = "400";
		        $arrResponse['msg'] = "Usuario Inactivo";
		        echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			
			

		} else {
			$arrResponse['login'] = "false";
		    $arrResponse['status'] = "400";
		    $arrResponse['msg'] = "Error! Verifica usuario y clave";
		    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}

}
	
	mysqli_free_result($resultado);
	mysqli_close($conexion);

	?>
