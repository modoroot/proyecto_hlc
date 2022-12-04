<?php

include 'conn.php';
include "sesion.php";

$user_id = $_SESSION['user_id'];
//redirige a la página de login si está null la variable global de session
//con nombre user_id
if (!isset($user_id)) {
    header('location:login.php');
};
//cierra la sesión y elimina dicha sesión. He usado la variable global GET para
//ahorrar líneas de código
if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <div class="perfil_usuario">
        <?php
        //sentencia sql
        $select = mysqli_query($conn, "SELECT * FROM usuario WHERE id = '$user_id'") or die('fallo en
        la query');
        //si existe guarda en un array los datos traídos de la DB
        if (mysqli_num_rows($select) > 0) {
            $fetch = mysqli_fetch_assoc($select);
        }
        //si no tiene imagen guardada en la DB, pone una imagen por defecto
        if ($fetch['imagen'] == '') {
            echo '<img src="img/default-avatar.png" alt="avatar por defecto">';
        } else {
            echo '<img src="img_db/' . $fetch['imagen'] . '">';
        }
        ?>
        <!--nombre del usuario-->
        <h3><?php echo $fetch['nombre']; ?></h3>
        <a href="actualizar_perfil.php" class="btn">Configurar perfil</a>
        <a href="home.php?logout=<?php echo $user_id; ?>" class="delete-btn">Cerrar sesión</a>
    </div>
</div>
</body>
</html>