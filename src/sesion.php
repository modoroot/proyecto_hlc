<?php

session_start();
//tiempo que tardará en caducar la sesión actual
//modificar este valor para probar el funcionamiento del código
$t = 300;
//Inicialización del tiempo restante de la sesión actual
$sesion_restante = 0;
if (isset($_SESSION["timeout"])) {
    $sesion_restante = time() - $_SESSION["timeout"];
    $_SESSION['count']++;
}
//cuando sea mayor que el tiempo definido, destruirá la sesión y redirigirá
//hacia la página de inicio de sesión
if($sesion_restante > $t){
    session_destroy();
    header("Location: index.php");
    die;
}else{
    //reinicia el contador
    $_SESSION['count'] = 0;
}
$_SESSION["timeout"] = time();