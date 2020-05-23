<?php

error_reporting(0);
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
    genero, 
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
    numero_control, 
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
    $genero,
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
    $nc,
    $correo,
    $contrasenaUsuario
);

if ($stmt->fetch()) {
    if ($contrasenaUsuario == $contrasena) {
        $response = [
            'idUsuario'     => $idUsuarios,
            'idAreas'       => $idAreas,
            'nombres'       => $nombres,
            'apePat'        => $apePat,
            'apeMat'        => $apeMat,
            'genero'        => $genero,
            'fechaNac'      => $fechaNac,
            'ciudad'        => $ciudad,
            'municipio'     => $municipio,
            'estado'        => $estado,
            'cp'            => $cp,
            'numExt'        => $numExt,
            'numInt'        => $numInt,
            'colonia'       => $colonia,
            'calle'         => $calle,
            'celular'       => $celular,
            'numControl'    => $nc,
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