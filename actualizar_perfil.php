<?php

include 'conn.php';
include 'sesion.php';
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}
//guarda los datos del usuario actual
$select = mysqli_query($conn, "SELECT * FROM usuario WHERE id = '$user_id'") or die('query failed');
if (mysqli_num_rows($select) > 0) {
    $fetch = mysqli_fetch_assoc($select);
}
if (isset($_POST['act_perfil'])) {

    $update_username = mysqli_real_escape_string($conn, $_POST['update_username']);
    $update_nombre = mysqli_real_escape_string($conn, $_POST['update_nombre']);

    mysqli_query($conn, "UPDATE usuario SET nombre = '$update_nombre', username = '$update_username' 
               WHERE id = '$user_id'") or die('query failed');

    $old_password = $_POST['password_antigua'];
    $act_password = mysqli_real_escape_string($conn, md5($_POST['act_password']));
    $nueva_password = mysqli_real_escape_string($conn, md5($_POST['nueva_password']));
    $confirmar_password = mysqli_real_escape_string($conn, md5($_POST['confirmar_password']));
    //si las contraseñas no están vacías...
    if (!empty($act_password) || !empty($nueva_password) || !empty($confirmar_password)) {
        if ($act_password != $old_password) {
            $mensaje[] = 'No coincide la contraseña actual';
        } elseif ($nueva_password != $confirmar_password) {
            $mensaje[] = 'Las nuevas contraseñas no coinciden';
        } else {
            mysqli_query($conn, "UPDATE usuario SET password = '$confirmar_password' WHERE id = '$user_id'")
            or die('query failed');
            $mensaje[] = 'Contraseña actualizada';
        }
    }
    //nueva imagen
    $act_imagen = $_FILES['act_imagen']['name'];
    $act_imagen_size = $_FILES['act_imagen']['size'];
    $act_imagen_tmp_name = $_FILES['act_imagen']['tmp_name'];
    $act_image_folder = 'public/uploads/' . $act_imagen;
    //actualización de imagen
    if (!empty($act_imagen)) {
        $imagen_update_query = mysqli_query($conn, "UPDATE usuario SET imagen = '$act_imagen' WHERE id = '$user_id'")
        or die('query failed');
        move_uploaded_file($act_imagen_tmp_name, $act_image_folder);
        $mensaje[] = 'Imagen actualizada';
    }
}

if (isset($_POST['elim_user'])) {
    //primero elimina las sesiones anteriores guardadas del usuario debido a que posee una clave ajena, por lo que
    //si no elimino primero sus sesiones anteriores, resultaría en error la consulta por dependencia directa
    mysqli_query($conn, "DELETE FROM session WHERE id_usuario='$user_id'") or die ("fallo en la query");
    //elimino el usuario
    mysqli_query($conn, "DELETE FROM usuario WHERE id='$user_id'") or die("fallo en la query");
    //elimino la sesión y redirijo
    session_destroy();
    header('Location: login.php');
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de perfil</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="conf-usuario">
    <!--enctype para poder subir archivos-->
    <form action="" method="post" enctype="multipart/form-data">
        <?php
        //imagen del usuario.  Si no tiene, se le coloca la predeterminada
        if ($fetch['imagen'] == '') {
            echo '<img src="img/default-avatar.png">';
        } else {
            echo '<img src="public/uploads/' . $fetch['imagen'] . '">';
        }
        //Mensaje para posibles errores o confirmaciones
        if (isset($mensaje)) {
            foreach ($mensaje as $mensaje) {
                echo '<div class="mensaje">' . $mensaje . '</div>';
            }
        }
        ?>
        <div class="flex">
            <div class="inputBox">
                <span>Tu nuevo usuario :</span>
                <!--                incluye el username que tiene actualmente el usuario-->
                <input type="text" name="update_username" value="<?php echo $fetch['username']; ?>" class="box">
                <span>Tu nuevo nombre:</span>
                <!--                incluye el nombre que tiene actualmente el usuario-->
                <input type="text" name="update_nombre" value="<?php echo $fetch['nombre']; ?>" class="box">
                <span>Tu nueva imagen:</span>
                <input type="file" name="act_imagen" accept="image/jpg, image/jpeg, image/png" class="box">
            </div>
            <div class="inputBox">
                <!--                guardo la antigua variable para poder compararla con la nueva-->
                <input type="hidden" name="password_antigua" value="<?php echo $fetch['password']; ?>">
                <span>Contraseña actual:</span>
                <input type="password" name="act_password" placeholder="Contraseña actual" class="box">
                <span>Nueva contraseña:</span>
                <input type="password" name="nueva_password" placeholder="Nueva contraseña" class="box">
                <span>Confirma nueva contraseña:</span>
                <input type="password" name="confirmar_password" placeholder="Confirma nueva contraseña" class="box">
            </div>
        </div>
        <input type="submit" value="Actualizar perfil" name="act_perfil" class="btn">
        <input type="submit" value="Eliminar cuenta de usuario" name="elim_user" class="delete-btn">
        <a href="home.php" class="delete-btn">Atrás</a>
    </form>
</div>
</body>
</html>