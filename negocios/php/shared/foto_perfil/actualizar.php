<?php

//error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json; charset=utf-8');

require '../../../../datos/php/conexion.php';

$response = array();

if (isset($_FILES['file'])) {
    if (strpos($_FILES['file']['type'], "jpeg") || strpos($_FILES['file']['type'], "jpg") || strpos($_FILES['file']['type'], "png")) {

        $destino = '/home/mantehostingacm/public_html/SIIAA_uploads';
        $destino2 = 'http://mante.hosting.acm.org/SIIAA_uploads';

        $idUsuario = $_POST['idUsuario'];
        $documento = 10;
        $file = $_FILES;
        $archivo = "foto_perfil";
        $extension = "";

        if (strpos($_FILES['file']['type'], "jpeg")) {
            $extension = '.jpeg';
        }
        if (strpos($_FILES['file']['type'], "jpg")) {
            $extension = '.jpg';
        }
        if (strpos($_FILES['file']['type'], "png")) {
            $extension = '.png';
        }

        $sql = "SELECT id_usuarios FROM usuarios WHERE id_usuarios = ?";

        $stmt = $conexion->prepare($sql);

        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $stmt->bind_result($idUsuario);

        if ($stmt->fetch()) {
            $stmt->store_result();

            $destino = $destino . '/' . $idUsuario;
            $destino2 = $destino2 . '/' . $idUsuario;

            if (!file_exists($destino)) {
                mkdir($destino, 0777, true);
            }

            $archivo=$_FILES['file']['name'];

            $destino = $destino . '/' . $archivo . $extension;
            $destino2 = $destino2 . '/' . $archivo . $extension;

            if (move_uploaded_file($file['file']['tmp_name'], $destino)) {

                $sql = "UPDATE archivos_usuarios SET archivo_ruta=? WHERE id_usuarios=? AND id_archivos=?";

                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("sii", $destino2, $idUsuario, $documento);
                $stmt->execute();

                if ($stmt->affected_rows >= 1) {
                    $response = [
                        'exito' => 'Subida exitosa'
                    ];
                } else {
                    unlink($destino);
                    $response = [
                        'error' => 'Ocurrio un error al intentar subir su foto: '
                    ];
                }
            } else {
                $response = [
                    'error' => 'Ocurrio un problema al intentar actualizar su foto de perfil: '
                ];
            }
        } else {
            $response = [
                'error' => 'No se encontro su usuario'
            ];
        }
    } else {
        $response = [
            'error' => 'Tiene que ser un jpg'
        ];
    }
} else {
    $response = [
        'error' => 'Seleccione una imagen con extension jpg o png'
    ];
}

$conexion->close();

echo json_encode($response);
