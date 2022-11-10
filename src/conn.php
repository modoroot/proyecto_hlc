<?php

$ar = fopen("txt/conn.txt", "r") or
die("No se pudo abrir el archivo");
//guarda el contenido del fichero en variables y borra espacios en blanco
//para poder usar el contenido en la conexión a la DB
$host = fgets($ar);
$user = fgets($ar);
$pwd = fgets($ar);
$db = fgets($ar);
$host = trim($host);
$user = trim($user);
$pwd = trim($pwd);
$db = trim($db);

//si no funciona, muere la conexión (seguridad)
if (!$conn = mysqli_connect($host,$user,$pwd,$db)) {
    die("No se pudo conectar a la DB");
}
//cierra el flujo de entrada de datos del fichero
fclose($ar);