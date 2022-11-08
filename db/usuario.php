<?php
include_once 'db.php';
class Usuario extends DB{
    private $nombre;
    private $username;
    
    public function __construct()
    {
        
    }

    /**
     * 
     */
    public function existeUser($user,$pwd){
        $pass_a_md5 = md5($pwd);

        $query = $this->conn()->prepare('SELECT * FROM usuario WHERE username = :user AND password = :pwd');
        $query -> execute(['user'=> $user,'pwd'=> $pwd]);

        if($query ->rowCount()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 
     */
    public function crearUsuario($usuario){
            $query = $this->conn()->prepare('SELECT * FROM usuario WHERE username = :usuario');
            $query -> execute(['user' => $usuario]);

            foreach($query as $usuarioActual){
                $this->nombre = $usuarioActual['nombre'];
                $this->username = $usuarioActual['username'];

            }
    }
    public function getNombre(){
        return $this->nombre;
    }
}