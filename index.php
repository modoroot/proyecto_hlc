<?php
include_once 'db/usuario.php';
include_once 'db/sesion.php';

$sesionUsuario = new Sesion();
$usuario = new Usuario();

if (isset($_SESSION['usuario'])) {
    $usuario->crearUsuario($sesionUsuario->getUsuarioActual());
    include_once 'mvc/home.php';
} else if (isset($_POST['username']) && isset($_POST['pwd'])) {
    //mapeamos el usuario y contraseña transmitida
    $formUsuario = $_POST['username'];
    $formPwd = $_POST['pwd'];
    //comprueba si el usuario pasado por los campos del formulario
    //existe
    if($usuario->existeUser($formUsuario, $formPwd)){
            $sesionUsuario->usuarioActual($formUsuario);
         $usuario->crearUsuario($formUsuario);
         include_once 'home.php';
    }else{
        $errorLogin = "Nombre o contraseña errónea";
        include_once 'mvc/login.php';
    }
} else {
    include_once 'mvc/login.php';
}
?>
