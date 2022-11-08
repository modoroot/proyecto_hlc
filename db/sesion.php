<?php
class Sesion{
    /**
     * crea una sesión
     */
    public function __construct()
    {
        session_start();
    }
    /**
     * iguala el usuario seleccionado al usuario
     * dentro de la sesión
     */
    public function usuarioActual($usuario){
        $_SESSION['usuario'] = $usuario;
    }

    public function getUsuarioActual(){
        return $_SESSION['usuario'];
    }

    public function cerrarSesion(){
        //borra los valores de las sesiones
        session_unset();
        //destruye las sesiones
        session_destroy();
    }
}