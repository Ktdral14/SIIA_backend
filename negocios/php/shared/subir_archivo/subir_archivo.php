<?php

error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json; charset=utf-8');

require '../../../../datos/php/conexion.php';

$response = array();

if (isset($_FILES['file'])) {
    if ($_FILES['file']['type'] == 'application/pdf') {

        $destino = '/home/mantehostingacm/public_html/SIIAA_uploads';

        $idUsuario = $_POST['idUsuario'];
        $documento = $_POST['fileType'];
        $file = $_FILES;

        $sql = "SELECT id_archivos FROM archivos WHERE id_archivos = ?";

        $stmt = $conexion->prepare($sql);

        $stmt->bind_param("i", $documento);

        $stmt->execute();

        $stmt->bind_result($documento);


        if ($stmt->fetch()) {
            $stmt->store_result();
            $archivo = "";
            switch ($documento) {
                case 1:
                    $archivo = 'acta_nacimiento';
                    break;
                case 2:
                    $archivo = 'rfc';
                    break;
                case 3:
                    $archivo = 'curp';
                    break;
                case 4:
                    $archivo = 'ine';
                    break;
                case 5:
                    $archivo = 'comprobante_domicilio';
                    break;
                case 6:
                    $archivo = 'curriculum';
                    break;
                case 7:
                    $archivo = 'kardex';
                    break;
                case 8:
                    $archivo = 'titulo';
                    break;
                case 9:
                    $archivo = 'cedula';
                    break;
                case 11:
                    $archivo = 'maestria';
                    break;
            }

            $sql = "SELECT id_usuarios FROM usuarios WHERE id_usuarios = ?";

            $stmt = $conexion->prepare($sql);

            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $stmt->bind_result($idUsuario);

            if ($stmt->fetch()) {
                $stmt->store_result();

                $destino = $destino . '/' . $idUsuario;

                if (!file_exists($destino)) {
                    mkdir($destino, 0777, true);
                }

                $destino = $destino . '/' . $archivo . '.pdf';

                if (move_uploaded_file($file['file']['tmp_name'], $destino)) {
                    $sql = "INSERT INTO archivos_usuarios VALUES(?, ?, ?)";

                    $stmt = $conexion->prepare($sql);
                    $stmt->bind_param("iis", $idUsuario, $documento, $destino);
                    $stmt->execute();

                    if ($stmt->affected_rows >= 1) {
                        $response = [
                            'exito' => 'Subida exitosa'
                        ];
                    } else {
                        unlink($destino);
                        $response = [
                            'error' => 'Ocurrió un error al subir el archivo'
                        ];
                    }
                } else {
                    $response = [
                        'error' => 'Ocurrió un problema al subir el archivo'
                    ];
                }
            } else {
                $response = [
                    'error' => 'No se encontró su usuario'
                ];
            }
        } else {
            $response = [
                'error' => 'Archivo inválido'
            ];
        }
    } else {
        $response = [
            'error' => 'Tiene que ser un pdf'
        ];
    }
} else {
    $response = [
        'error' => 'Suba un archivo'
    ];
}

echo json_encode($response);