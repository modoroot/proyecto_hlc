<?php
function comprobar_login($conn){

    if(isset($_SESSION['id'])){
        $id = $_SESSION['id'];
        $query = "SELECT * FROM usuario WHERE id = '$id'";
        $result = mysqli_query($conn,$query);
        //comprueba que el número de filas es mayor que 0 (ha entrado con éxito en
        //la DB)
        if($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
    }
    header("Location:login.php");
    die;
}