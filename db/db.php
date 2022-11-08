<?php
/**
 * Conexión a la DB con parámetros personalizados como pueden
 * para manejar errores o personalizar la conexión
 */
class DB{
    private $hostname;
    private $db;
    private $username;
    private $pwd;

    public function __construct()
    {
        $this->host = 'localhost';
        $this->db = 'proyecto_hlc';
        $this->username = 'root';
        $this->pwd = '';
    }

    function conn(){
    
        try{
            
            $conn = "mysql:host=" . $this->hostname . ";dbname=" . $this->db;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new PDO($conn, $this->username, $this->pwd, $options);
    
            return $pdo;

        }catch(PDOException $e){
            print_r('Error connection: ' . $e->getMessage());
        }   
    }
}