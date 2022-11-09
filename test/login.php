<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="menu">
        <ul>
            <li>Home</li>
            <li class="cerrar-sesion">
                <a href="logout.php">Cerrar sesión</a>
            </li>
        </ul>
    </div>

    <section>
        <h1>Bienvenido <?php echo $_SESSION['username']?> </h1>
    </section>
    
</body>
</html>