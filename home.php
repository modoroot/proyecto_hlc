<?php

include 'conn.php';
include "sesion.php";
include 'Paginator.php';

$user_id = $_SESSION['user_id'];
//redirige a la página de login si está null la variable global de session
//con nombre user_id
if (!isset($user_id)) {
    header('location:login.php');
};
//Cierra la sesión y elimina dicha sesión. He usado la variable global GET para
//ahorrar líneas de código
if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:login.php');
}

//Paginador
$limit = (isset($_GET['limit'])) ? $_GET['limit'] : 25;
$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
$links = (isset($_GET['links'])) ? $_GET['links'] : 7;
$query = "SELECT id_producto, nombre, precio_eur, descripcion FROM producto";
$pager = new Paginator($conn, $query);

$results = $pager->getData($page, $limit);


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
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
<div class="container">
    <div class="col-md-10 col-md-offset-1">
    <h1>Productos</h1>
    <table class="table table-striped table-condensed table-bordered table-rounded">
        <thead>
        <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Precio (€)</th>
            <th>Descripción</th>
        </tr>
        </thead>
        <tbody>
        <?php for ($i = 0; $i < count($results->data); $i++) : ?>
            <tr>
                <td><?php echo $results->data[$i]['id_producto']; ?></td>
                <td><?php echo $results->data[$i]['nombre']; ?></td>
                <td><?php echo $results->data[$i]['precio_eur']; ?></td>
                <td><?php echo $results->data[$i]['descripcion']; ?></td>
            </tr>
            <!--        finaliza bucle for-->
        <?php endfor; ?>
        </tbody>
    </table>
        <?php echo $pager->createLinks($links, 'pagination pagination-sm'); ?>
</div>
</div>

</body>
</html>