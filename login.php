<?php

include 'conn.php';
//empieza sesión
session_start();

if (isset($_POST['submit'])) {
    //guarda el nombre de usuario y contraseña introducidos por el formulario.
    //Uso la función de justo abajo porque me daba problemas al reconocer datos
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
    //sentencia sql
    $select = mysqli_query($conn, "SELECT * FROM usuario WHERE username = '$username' AND password = '$pass'")
    or die('fallo en la query');
    //si existe el usuario...
    if (mysqli_num_rows($select) > 0) {
        //guarda en un array los datos traídos de la DB
        $row = mysqli_fetch_assoc($select);
        //guarda el id del usuario en la variable global de session
        $_SESSION['user_id'] = $row['id'];
        $id_usuario = $row['id'];
        $_SESSION['username'] = $username;
        //regeneramos un nuevo id una vez ha muerto tras el tiempo determinado
        //en el fichero sesion.php
        session_regenerate_id();
        //se guarda el id de la sesion para usarlo posteriormente en la sentencia sql, y así guardarlo en su tabla
        //de la DB
        $sesion = session_id();
        $query = "INSERT INTO session (id,session,id_usuario) VALUES (NULL,'$sesion','$id_usuario')";
        $result = mysqli_query($conn, $query) or die("error en la query");
        //se redirige al home de la web
        header('location:home.php');
    } else {
        $mensaje[] = "Datos de sesión erróneos";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="form-container">
    <form action="" method="post" enctype="multipart/form-data">
        <h3>Inicio de sesión</h3>
        <?php
        //en este caso sólo se utiliza para errores de inicio de datos de sesión. No obstante
        //se puede utilizar para más funciones y así no repetir líneas de código o condicionales
        if (isset($mensaje)) {
            foreach ($mensaje as $mensaje) {
                echo '<div class="mensaje">' . $mensaje . '</div>';
            }
        }
        ?>
        <input type="text" name="username" placeholder="Introduce tu usuario" class="box" required>
        <input type="password" name="password" placeholder="Introduce tu contraseña" class="box" required>
        <input type="submit" name="submit" value="Iniciar sesión" class="btn">
        <p><a href="registro_usuario.php">Registrarse</a></p>
    </form>
</div>
</body>
</html>