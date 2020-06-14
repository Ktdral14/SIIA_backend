<?php

//error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json; charset=utf-8');

require '../../../../datos/php/conexion.php';

$response = array();

$id_usuarios = $_POST['id_usuarios'];
$bloque = $_POST['bloque'];

$bloque = htmlspecialchars(filter_var($bloque, FILTER_SANITIZE_STRING));

$sql1 ="SELECT bloque FROM progreso_encuestas WHERE id_usuarios = ? AND bloque = ?";

$stmtBloque = $conexion->prepare($sql1);
$stmtBloque -> bind_param("is", $id_usuarios, $bloque);
$stmtBloque->execute();

$stmtBloque->bind_result($encontro);

if(!$stmtBloque -> fetch()) {
    $stmtBloque->store_result();
	$sql = "INSERT INTO progreso_encuestas (id_usuarios, bloque) VALUES(?,?)";
	$stmt = $conexion->prepare($sql);
	$stmt->bind_param("is", $id_usuarios, $bloque);
	$stmt->execute();
    $error = $conexion->error;
    if ($conexion->affected_rows >= 1) {
		$response = [
		    'status' => "false",
		];
	} else {
		$response = [
			'error' => 'Ha ocurrido un error',
			'info' => $error
		];
	}
}else{
    $response = [
	    'status' => "true"
	   ];
}
$conexion->close();

echo json_encode($response);