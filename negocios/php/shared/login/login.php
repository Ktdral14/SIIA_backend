<?php

// error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json; charset=utf-8');

require '../../../../datos/php/conexion.php';

$response = array();

$correo     = $_POST['correo'];
$contrasena = $_POST['contrasena'];

$correo         = htmlspecialchars(filter_var($correo, FILTER_SANITIZE_EMAIL));
// $hashContrasena = hash("sha256", htmlspecialchars($contrasena));

$sql = "SELECT 
    id_usuarios, 
    id_areas, 
    nombres, 
    apellido_paterno, 
    apellido_materno,
    fecha_de_nacimiento, 
    ciudad, 
    municipio, 
    estado, 
    codigo_postal, 
    num_ext, 
    num_int, 
    colonia, 
    calle, 
    numero_celular, 
    correo_electronico, 
    contrasena
    FROM usuarios WHERE correo_electronico=?";

$stmt = $conexion->prepare($sql);

$stmt->bind_param("s", $correo);

$stmt->execute();

$stmt->bind_result(
    $idUsuarios,
    $idAreas,
    $nombres,
    $apePat,
    $apeMat,
    $fechaNac,
    $ciudad,
    $municipio,
    $estado,
    $cp,
    $numExt,
    $numInt,
    $colonia,
    $calle,
    $celular,
    $correo,
    $contrasenaUsuario
);

if ($stmt->fetch()) {
    if ($contrasenaUsuario == $contrasena) {
        $response = [
            'idUsuario'     => $idUsuarios,
            'idAreas'       => $idAreas,
            'nombres'       => utf8_encode($nombres),
            'apePat'        => utf8_encode($apePat),
            'apeMat'        => utf8_encode($apeMat),
            'fechaNac'      => $fechaNac,
            'ciudad'        => utf8_encode($ciudad),
            'municipio'     => utf8_encode($municipio),
            'estado'        => utf8_encode($estado),
            'cp'            => $cp,
            'numExt'        => $numExt,
            'numInt'        => $numInt,
            'colonia'       => utf8_encode($colonia),
            'calle'         => utf8_encode($calle),
            'celular'       => $celular,
            'correo'        => $correo
        ];
    } else {
        $error = 'Password';
        $response = [
            'error' => $error
        ];
    }
} else {
    $error = 'Account';
    $response = [
        'error' => $error
    ];
}
echo json_encode($response);