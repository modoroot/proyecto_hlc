<?php
session_start();
$conn = mysqli_connect('localhost','root','root','proyecto_hlc') or die('No se pudo conectar');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login HLC</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="center">
        <h1>Hola</h1>
        <form action = "index.php" method="post">
        <?php
            if(isset($errorLogin)){
                echo $errorLogin;
            }
        ?>
            <div class="txt_field">
                <input type="text" name="username" required>
                <span></span>
                <label>Usuario</label>
            </div>
            <div class="txt_field">
                <input type="password" name ="pwd" required>
                <span></span>
                <label>Contraseña</label>
            </div>
            <input type="submit" value="Login" name = "login">
            <div class="signup_link">
                ¿No tienes usuario?<a href="#"> Registrarse</a>
            </div>
        </form>
    </div>
</body>

</html>
<?php
if (isset($_POST['login'])){
    $username = $_POST['username'];
    $pwd = $_POST['pwd'];
    $select = mysqli_query($conn, "SELECT * FROM usuario WHERE username='$username' AND password='$pwd'");
    $row = mysqli_fetch_array($select);

    if(is_array($row)){
        $_SESSION['username'] = $row ['username'];
        $_SESSION['pwd'] = $row ['password'];
    }else{
        echo '<script type = "text/javascript">';
        echo 'alert("Usuario y/o contraseña erróneos");';
        echo 'window.location.href = "index.php"';
        echo '</script>';
    }
    if(isset($_SESSION['username'])){
        header("Location:login.php");
    }
}
?>