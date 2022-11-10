<?php
ini_set('session.gc_maxlifetime',10);
session_set_cookie_params(10);
session_start();

include "conn.php";
include "funciones.php";

//comprobamos si el usuario ha pulsado el botón de registrar un usuario
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    //guardamos los datos
    $username = $_POST['username'];
    $password = $_POST['pwd'];
    //comprobamos que los campos no estuviesen vacíos
    if (!empty($username) && !empty($password)) {
        //sentencia SQL - Lee de la DB el usuario de login, comprobando así si existe
        $query = "SELECT * FROM usuario WHERE username ='$username'";
        $result = mysqli_query($conn, $query);
        //comprobamos si result tiene contenido
        if ($result) {
            //comprueba que el número de filas es mayor que 0 (ha entrado con éxito en
            //la DB)
            if (mysqli_num_rows($result) > 0) {
                session_start();

                $datos = mysqli_fetch_assoc($result);
                $hash = $datos['password'];
                if (password_verify($password, $hash)) {
                    //redirigimos dentro de la página de login, es decir, ha podido logear
                    //correctamente
                    $_SESSION['id'] = $datos['id'];
                    header("Location: login.php");
                    die;
                } else {
                    echo "error pass";
                }
            } else {
                echo "error sql";
            }
        } else {
            echo "Campos no válidos";
        }
    } else {
        echo "Campos no válidos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="center">
    <h1>Hola</h1>
    <form method="post">
        <div class="txt_field">
            <input type="text" name="username" required>
            <span></span>
            <label>Usuario</label>
        </div>
        <div class="txt_field">
            <input type="password" name="pwd" required>
            <span></span>
            <label>Contraseña</label>
        </div>
        <input type="submit" value="Iniciar sesión" name="login">
        <div class="signup_link">
            <a href="registrar_usuario.php">Registrarse</a><br>
            <label><input type="checkbox" name="recordar" value="Sí"> Recordarme</label>
        </div>
    </form>
</div>
</body>
</html>