<?php

//error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json; charset=utf-8');

require '../../../../datos/php/conexion.php';

$respuesta = array();
$mes =  $_POST['mes'];
$conexion->set_charset("utf8");

$mes      = htmlspecialchars(filter_var($mes, FILTER_SANITIZE_NUMBER_INT));

$sql = "SELECT nombres, fecha_de_nacimiento FROM usuarios WHERE MONTH(fecha_de_nacimiento) = ?";

$stmt = $conexion->prepare($sql);

$stmt->bind_param("i", $mes);

$stmt->execute();

$stmt->bind_result($nombres, $fecha_de_nacimiento);

$arrDatos = myGetResult($stmt);
$arrResultado = ($arrDatos) ? $arrDatos : array("error" => "La consulta no  arrojó  datos");

$conexion->close();

echo json_encode($arrResultado);

function myGetResult($Statement)
{
    $RESULT = array();
    $Statement->store_result();
    for ($i = 0; $i < $Statement->num_rows; $i++) {
        $Metadata = $Statement->result_metadata();
        $PARAMS = array();
        while ($Field = $Metadata->fetch_field()) {
            $PARAMS[] = &$RESULT[$i][$Field->name];
        }
        call_user_func_array(array($Statement, 'bind_result'), $PARAMS);
        $Statement->fetch();
    }
    return $RESULT;
}
?>