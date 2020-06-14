<?php

//error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json; charset=utf-8');

require '../../../../datos/php/conexion.php';

$response = array();

$id_usuarios = $_POST['id_usuarios'];

$id_usuarios = htmlspecialchars(filter_var($id_usuarios, FILTER_SANITIZE_STRING));

$sql = "SELECT bloque FROM  progreso_encuestas WHERE id_usuarios = '".$id_usuarios."'";
$result = mysqli_query($conexion, $sql);
$error = $conexion->error;

$response = [
    "status" => "void"
    ];
while ($bloques = mysqli_fetch_array($result)){
    $bloqueUsuario["bloqueUsuario"] = $bloques["bloque"];
    
    $response["bloquesUsuarios"][] = $bloqueUsuario;
}
	
$conexion->close();

echo json_encode($response);