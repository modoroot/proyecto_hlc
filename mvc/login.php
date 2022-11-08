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
        <form action = "" method="post">
        <?php
            if(isset($errorLogin)){
                echo $errorLogin;
            }
        ?>
            <div class="txt_field">
                <input type="text" name="usuario" required>
                <span></span>
                <label>Usuario</label>
            </div>
            <div class="txt_field">
                <input type="password" name ="pwd" required>
                <span></span>
                <label>Contraseña</label>
            </div>
            <div class="pass">Forgot Password?</div>
            <input type="submit" value="Login">
            <div class="signup_link">
                ¿No tienes usuario?<a href="#"> Registrarse</a>
            </div>
        </form>
    </div>
</body>

</html>