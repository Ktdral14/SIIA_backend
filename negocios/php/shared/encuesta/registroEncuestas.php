<?php

error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json; charset=utf-8');

require '../../../../datos/php/conexion.php';

$response = array();



$bloque				= $_POST['bloque'];
$id_areas     		= $_POST['id_areas'];
$valor_respuesta    = $_POST['valor_respuesta'];
$pregunta    		= $_POST['pregunta'];


$bloque  			= htmlspecialchars(filter_var($bloque, FILTER_SANITIZE_STRING));
$pregunta    		= htmlspecialchars(filter_var($pregunta, FILTER_SANITIZE_STRING));



		$sql1 = "SELECT id_bloques FROM  bloques WHERE bloque =  '".$bloque."'";
		$stmtId = $conexion->prepare($sql1);
		$stmtId->execute();
		$stmtId->bind_result($id_bloques);

		if($stmtId->fetch()) {
			$stmtId->store_result();

		$sql = "INSERT INTO respuestas 
		(id_bloques, id_areas, valor_respuesta, pregunta) 
		VALUES (?,?,?,?)";

		$stmt = $conexion->prepare($sql);
		
		$stmt->bind_param("iiis", $id_bloques, $id_areas, $valor_respuesta, $pregunta);
		$stmt->execute();
        $error = $conexion->error;
		if ($conexion->affected_rows >= 1) {
			$response = [
				'exito' => 'Registro exitoso'
			];
		} else {
			$response = [
				'error' => 'Ha ocurrido un error',
				'info' => $error
			];
		}
}else{
	$response = [
				'error' => 'El id del bloque no coincide'
			];
    
}
$conexion->close();

echo json_encode($response);
