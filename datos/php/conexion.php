<?php

require "config.php";

$conexion = new mysqli($server, $user, $pass, $db);

if ($conexion->errno) {
    die('Hubo un problema con el servidor');
}

?> 