<?php

error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json; charset=utf-8');

require '../../../../datos/php/conexion.php';

$respuesta = array();
$idUsuario =  $_POST['idUsuario'];

$idUsuario      = htmlspecialchars(filter_var($idUsuario, FILTER_SANITIZE_NUMBER_INT));

$sql = "SELECT titulo FROM notas WHERE id_usuarios=?";

$stmt = $conexion->prepare($sql);

$stmt->bind_param("i", $idUsuario);

$stmt->execute();

$stmt -> bind_result($id_usuarios, $id_notas, $titulo, $descripcion, $fecha);
$stmt->store_result();
$notasTotales = $stmt->num_rows;

if ($notasTotales < 6 ) {
    $respuesta = [
        'exito' => $notasTotales
    ];
} else {
    $respuesta = [
        'error' => 'Alcanzo el limite'
    ];
}

$conexion->close();

echo json_encode($respuesta);