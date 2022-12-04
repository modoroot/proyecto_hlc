<?php

include 'conn.php';

if (isset($_POST['submit'])) {
    //guarda los datos introducidos en el formulario en variables para pasarlos a la DB
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));
    $confirmar_password = mysqli_real_escape_string($conn, md5($_POST['confirmar_password']));
    //nombre
    $imagen = $_FILES['img_perfil']['name'];
    //tamaño en bytes
    $imagen_size = $_FILES['img_perfil']['size'];
    //ruta temporal
    $imagen_tmp_name = $_FILES['img_perfil']['tmp_name'];
    //guarda la imagen de la DB de manera local
    $imagen_folder = 'img_db/' . $imagen;
    //sentencia sql para comprobar si ya existe el usuario en la DB
    $select = mysqli_query($conn, "SELECT * FROM usuario WHERE username = '$username' AND password = '$password'")
    or die('query failed');
    //si recoge 1 línea, significa que el usuario ya existe, por lo que no registrará el usuario
    if (mysqli_num_rows($select) > 0) {
        $mensaje[] = 'El usuario ya existe';
    } else {
        if ($password != $confirmar_password) {
            $mensaje[] = 'Las contraseñas no son iguales';
        }
         else {
            $insert = mysqli_query($conn, "INSERT INTO usuario(username, nombre, password, imagen)
            VALUES('$username', '$nombre', '$password', '$imagen')") or die('query failed');
            if ($insert) {
                //mueve el fichero a la carpeta de subida de imágenes de la DB
                move_uploaded_file($imagen_tmp_name, $imagen_folder);
                $mensaje[] = 'Se ha registrado el usuario correctamente';
                header('location:login.php');
            } else {
                $mensaje[] = 'Registro fallido';
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="form-container">
    <form action="" method="post" enctype="multipart/form-data">
        <h3>Nuevo registro de usuario</h3>
        <?php
        if (isset($mensaje)) {
            foreach ($mensaje as $mensaje) {
                echo '<div class="mensaje">' . $mensaje . '</div>';
            }
        }
        ?>
        <input type="text" name="nombre" placeholder="Introduce tu nombre" class="box" required>
        <input type="text" name="username" placeholder="enter username" class="box" required>
        <input type="password" name="password" placeholder="enter password" class="box" required>
        <input type="password" name="confirmar_password" placeholder="confirm password" class="box" required>
        <input type="file" name="img_perfil" class="box" accept="image/jpg, image/jpeg, image/png">
        <input type="submit" name="submit" value="Registrarse" class="btn">
        <p><a href="login.php">Volver a inicio de sesión</a></p>
    </form>
</div>
</body>
</html>