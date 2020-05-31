<?php

//error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json; charset=utf-8');

require '../../../../datos/php/conexion.php';

$respuesta = array();
$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
$idUsuario = $_POST['idUsuario'];
$idNota =  $_POST['idNota'];


$titulo         = htmlspecialchars(filter_var($titulo, FILTER_SANITIZE_STRING));
$descripcion    = htmlspecialchars(filter_var($descripcion, FILTER_SANITIZE_STRING));
$fecha          = htmlspecialchars(filter_var($fecha, FILTER_SANITIZE_STRING));
$idUsuario      = htmlspecialchars(filter_var($idUsuario, FILTER_SANITIZE_NUMBER_INT));
$idNota      = htmlspecialchars(filter_var($idNota, FILTER_SANITIZE_NUMBER_INT));

$sql = "UPDATE notas SET titulo=?, descripcion=? WHERE id_usuarios=?  and id_notas=?";

$stmt = $conexion->prepare($sql);

$stmt->bind_param("ssii", $titulo, $descripcion, $idUsuario, $idNota);

$stmt->execute();

if ($conexion->affected_rows >= 1) {
    $respuesta = [
        'exito' => 'Actualizacion exitosa'
    ];
} else {
    $respuesta = [
        'error' => 'No se ha podido actualizar'
    ];
}

$conexion->close();

echo json_encode($respuesta);

?>