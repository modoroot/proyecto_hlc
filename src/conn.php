<?php
$dbhost="localhost";
$dbuser="root";
$dbpass="root";
$dbname="proyecto_hlc";
//si no funciona, muere la conexión (seguridad)
if(!$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)){
    die("No se pudo conectar a la DB");
}


?>