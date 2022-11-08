<?php
    include_once 'sesion.php';

    $userSession = new Sesion();
    $userSession -> cerrarSesion();

    header("location: ../index.php");

?>