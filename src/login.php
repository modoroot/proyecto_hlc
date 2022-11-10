<?php

include "sesion.php";
include "conn.php";
include "funciones.php";

$datos = comprobar_login($conn);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div>
        <ul>
            <li>Home</li>
            <li>
                <a href="index.php" class="signup_link">Cerrar sesión</a>
            </li>
        </ul>
    </div>

    <section>
        <h1>Bienvenido <?php echo $datos['nombre']?> </h1>
    </section>
    
</body>
</html>