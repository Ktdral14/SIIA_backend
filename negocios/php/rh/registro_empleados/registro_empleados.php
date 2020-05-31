<?php

error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json; charset=utf-8');

require '../../../../datos/php/conexion.php';

$response = array();

$nombres    = $_POST['nombres'];
$apePat     = $_POST['apePat'];
$apeMat     = $_POST['apeMat'];
$fechaNac   = $_POST['fechaNac'];
$celular    = $_POST['celular'];
$contrasena = $_POST['contrasena'];
$tipoEmp    = $_POST['tipoEmp'];
$cp         = $_POST['cp'];
$ciudad     = $_POST['ciudad'];
$estado     = $_POST['estado'];
$municipio  = $_POST['municipio'];
$colonia    = $_POST['colonia'];
$calle      = $_POST['calle'];
$numInt     = $_POST['numInt'];
$numExt     = $_POST['numExt'];
$correo     = $_POST['correo'];
$contrasenaConfirmar = $_POST['contrasenaConfirmar'];

$nombres    = htmlspecialchars(filter_var($nombres, FILTER_SANITIZE_STRING));
$apePat     = htmlspecialchars(filter_var($apePat, FILTER_SANITIZE_STRING));
$apeMat     = htmlspecialchars(filter_var($apeMat, FILTER_SANITIZE_STRING));
$fechaNac   = htmlspecialchars(filter_var($fechaNac, FILTER_SANITIZE_STRING));
$celular    = htmlspecialchars(filter_var($celular, FILTER_SANITIZE_STRING));
$contrasena = htmlspecialchars(filter_var($contrasena, FILTER_SANITIZE_STRING));
$tipoEmp 	= htmlspecialchars(filter_var($tipoEmp, FILTER_SANITIZE_STRING));
$cp         = htmlspecialchars(filter_var($cp, FILTER_SANITIZE_STRING));
$ciudad     = htmlspecialchars(filter_var($ciudad, FILTER_SANITIZE_STRING));
$estado     = htmlspecialchars(filter_var($estado, FILTER_SANITIZE_STRING));
$municipio  = htmlspecialchars(filter_var($municipio, FILTER_SANITIZE_STRING));
$colonia    = htmlspecialchars(filter_var($colonia, FILTER_SANITIZE_STRING));
$calle      = htmlspecialchars(filter_var($calle, FILTER_SANITIZE_STRING));
$numInt     = htmlspecialchars(filter_var($numInt, FILTER_SANITIZE_STRING));
$numExt     = htmlspecialchars(filter_var($numExt, FILTER_SANITIZE_STRING));
$correo     = htmlspecialchars(filter_var($correo, FILTER_SANITIZE_EMAIL));
$contrasenaConfirmar = htmlspecialchars(filter_var($contrasenaConfirmar, FILTER_SANITIZE_STRING));

if ($contrasena == $contrasenaConfirmar) {

	$continuar = false;

	switch ($tipoEmp) {
		case 'Docente':
			$idAreas = 3;
			$continuar = true;
			break;
		case 'Mantenimiento':
			$idAreas = 6;
			$continuar = true;
			break;
		case 'Intendente':
			$idAreas = 5;
			$continuar = true;
			break;
		case 'Administrativo':
			$idAreas = 1;
			$continuar = true;
			break;
		case 'Auxiliar':
			$idAreas = 4;
			$continuar = true;
			break;
		default:
			$response = [
				'error' => 'No se selecciono tipo de empleado'
			];
			$continuar = false;
			break;
	}

	if ($continuar) {
		$sql = "INSERT INTO usuarios
		(id_areas, nombres, apellido_paterno, apellido_materno, 
		fecha_de_nacimiento, ciudad, municipio, estado, 
		codigo_postal, num_ext, num_int, colonia, 
		calle, numero_celular, correo_electronico, contrasena) 
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

		$stmt = $conexion->prepare($sql);

		$stmt->bind_param(
			"isssssssiiisssss",
			$idAreas, $nombres, $apePat, $apeMat,
			$fechaNac, $ciudad, $municipio, $estado, 
			$cp, $numExt, $numInt, $colonia, 
			$calle, $celular, $correo, $contrasena
		);

		$stmt->execute();

		if ($conexion->affected_rows >= 1) {
			$response = [
				'exito' => 'Registro exitoso'
			];
		} else {
			$response = [
				'error' => 'Ha ocurrido un error'
			];
		}
	}
} else {
	$response = [
		'error' => 'Las contrasenas no coinciden'
	];
}

$conexion->close();

echo json_encode($response);
