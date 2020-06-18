<?php

//error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json; charset=utf-8');

require '../../../../datos/php/conexion.php';

$respuesta = array();
$idUsuario =  $_POST['idUsuario'];

$archivo = 10;
$destino = "https://www.nicepng.com/png/full/202-2022264_usuario-annimo-usuario-annimo-user-icon-png-transparent.png";

$idUsuario      = htmlspecialchars(filter_var($idUsuario, FILTER_SANITIZE_NUMBER_INT));

$stmt_check = $conexion->prepare("SELECT id_subida FROM archivos_usuarios WHERE id_usuarios=? AND id_archivos=?");
$stmt_check->bind_param("ii", $idUsuario, $archivo);
$stmt_check->execute();
if ($stmt_check->num_rows > 0) {
} else {
    $stmt_check->store_result();
    
    $stmt1 = $conexion->prepare("INSERT INTO archivos_usuarios (id_usuarios, id_archivos, archivo_ruta) VALUES (?,?,?)");
    

    $stmt1->bind_param("iis", $idUsuario, $archivo, $destino);

    $stmt1->execute();
}

$sql = "
SELECT areas.nombre_area as area, 
archivos_usuarios.archivo_ruta as ruta, 
usuarios.id_usuarios as idUser FROM usuarios 
INNER JOIN areas ON areas.id_areas = usuarios.id_areas 
INNER JOIN archivos_usuarios ON archivos_usuarios.id_usuarios = usuarios.id_usuarios 
WHERE archivos_usuarios.id_archivos=10 AND archivos_usuarios.id_usuarios=?
";

$stmt = $conexion->prepare($sql);

$stmt->bind_param("i", $idUsuario);

$stmt->execute();

$stmt->bind_result($tipo_area, $ruta_foto, $idUser);

$arrDatos = myGetResult($stmt);
$arrResultado = ($arrDatos) ? $arrDatos : array("error" => "La consulta no  arrojo  datos");

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