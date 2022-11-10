<?php
session_start();

include("conn.php");
include("funciones.php");

//comprobamos si el usuario ha pulsado el botón de registrar un usuario
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //guardamos los datos
    $username = $_POST['username'];
    $nombre = $_POST['nombre'];
    $password = $_POST['pwd'];
    //comprobamos que los campos no estuviesen vacíos
    if(!empty($username) && !empty($nombre) &&!empty($password))
    {
        //sentencia SQL - Inserta el usuario registrado
        $query = "INSERT INTO usuario (id,nombre,username,password) VALUES (NULL,'$nombre','$username','$password')";
        mysqli_query($conn, $query);
        //Redirige de nuevo a la página ppal
        header("Location: index.php");
        die;
    }else
    {
        echo "Campos no válidos";
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registro</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="center">
    <h1>Registrarse</h1>
    <form method="post">
        <div class="txt_field">
            <input type="text" name="nombre" required>
            <span></span>
            <label>Nombre</label>
        </div>
        <div class="txt_field">
            <input type="text" name="username" required>
            <span></span>
            <label>Usuario</label>
        </div>
        <div class="txt_field">
            <input type="password" name ="pwd" required>
            <span></span>
            <label>Contraseña</label>
        </div>
        <input type="submit" value="Crear registro" name = "login">
        <div class="signup_link">
            <a href="login.php">Iniciar sesión</a><br>
        </div>
    </form>
</div>
</body>
</html>
