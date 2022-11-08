<?php
include_once 'db/usuario.php';
include_once 'db/sesion.php';

$sesionUsuario = new Sesion();
$usuario = new Usuario();

if (isset($_SESSION['usuario'])) {
    echo "sesión fina";
} else if (isset($_POST['username']) && isset($_POST['pwd'])) {
    echo "validación de login";
} else {
    include_once 'mvc/login.php';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animated Login Form | CodingNepal</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="center">
        <h1>Hola</h1>
        <form method="post">
            <div class="txt_field">
                <input type="text" required>
                <span></span>
                <label>Usuario</label>
            </div>
            <div class="txt_field">
                <input type="password" required>
                <span></span>
                <label>Contraseña</label>
            </div>
            <div class="pass">Forgot Password?</div>
            <input type="submit" value="Login">
            <div class="signup_link">
                ¿No tienes usuario?<a href="#"> Registrarse</a>
            </div>
        </form>
    </div>
</body>

</html>