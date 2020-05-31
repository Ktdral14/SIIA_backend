<?php

error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json; charset=utf-8');

require '../../../../datos/php/conexion.php';

$respuesta = array();
$idNota =  $_POST['idNota'];

$idNota      = htmlspecialchars(filter_var($idNota, FILTER_SANITIZE_NUMBER_INT));

$sql = "DELETE FROM notas WHERE id_notas=?";

$stmt = $conexion->prepare($sql);

$stmt->bind_param("i", $idNota);

$stmt->execute();

if ($conexion->affected_rows >= 1) {
    $respuesta = [
        'exito' => 'Elimado exitosamente'
    ];
} else {
    $respuesta = [
        'error' => 'No se ha podido eliminar'
    ];
}

$conexion->close();

echo json_encode($respuesta);

?>