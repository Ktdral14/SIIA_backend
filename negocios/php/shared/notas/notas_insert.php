<?php

error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json; charset=utf-8');

require '../../../../datos/php/conexion.php';

$respuesta = array();


$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
$fecha = $_POST['fecha'];
$idUsuario = $_POST['idUsuario'];

$titulo         = htmlspecialchars(filter_var($titulo, FILTER_SANITIZE_STRING));
$descripcion    = htmlspecialchars(filter_var($descripcion, FILTER_SANITIZE_STRING));
$fecha          = htmlspecialchars(filter_var($fecha, FILTER_SANITIZE_STRING));
$idUsuario      = htmlspecialchars(filter_var($idUsuario, FILTER_SANITIZE_NUMBER_INT));

$sql = "INSERT INTO notas (id_usuarios, titulo, descripcion, fecha) VALUES (?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);

$stmt->bind_param("isss", $idUsuario, $titulo, $descripcion, $fecha);

$stmt->execute();

if ($conexion->affected_rows >= 1) {
    $respuesta = [
        'exito' => 'Registro exitoso'
    ];
} else {
    $respuesta = [
        'error' => 'No se ha podido insertar la nota'
    ];
}

$conexion->close();

echo json_encode($respuesta);