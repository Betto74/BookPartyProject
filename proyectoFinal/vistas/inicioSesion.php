<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/css2?family=Krub:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styleInicioSesion.css">
    <link rel="stylesheet" href="css/menuTop.css">

</head>

<body>

<div class="fixed">
    <?php   
    session_start();
    if((isset($_SESSION["correo"]) && strlen($_SESSION["correo"])>0))
    {
      if( $_SESSION["rol"]=='usuario' ){
        require("menuUser.php");
      }
      else {
        require("menuOwner.php");
      }
    }
    else{
      require("menuPublico.php");
    }
  ?>
</div>

<!-- -------------------------- LOGIN -------------------------- -->
    <?php
        require_once("../datos/DAOUsuario.php");
    ?>
    <?php
        $correo = $password = $validado = $error = "";
        if (isset($_POST["txtCorreo"]) && isset($_POST["txtPassword"])) {
            $correo = trim($_POST["txtCorreo"]);
            $password = trim($_POST["txtPassword"]);

            // Validar los datos
            if (filter_var($correo, FILTER_VALIDATE_EMAIL) && 
                strlen($password) >=8 && strlen($password) <= 50) {

                
                $dao = new DAOUsuario();
                $usuario=$dao->autenticar($correo,$password);
                // Verificar que el usuario sea admin@gmail.com y pass 12345678
                //var_dump($usuario);
                if($usuario){
       
                    $_SESSION["correo"]  =$correo;
                    $_SESSION["rol"]=$usuario->rol;
                    //var_dump($_SESSION["correo"] );
                    
        
                    header("Location: index.php");
                }
                else {
                    $error = '<div class="msg" style="color: red">Usuario y/o contraseña incorrectos</div>';
                }
            } else {
                $error = '<div class="msg" style="color: red">Datos erroneos</div>';
            }
        }
    ?>
<!-- -------------------------- LOGIN -------------------------- -->
    <form action="" method="post" autocomplete="off" class="<?= $validado ?>" novalidate>
        <div id="principal">
            <br>
            <div>
                <a>Inicio de Sesion</a>
            </div>
            <br>
            <div id="txts">
                <span class="material-symbols-outlined" id="iconos">
                    mail
                </span>
                <input type="email" id="txtCorreo" placeholder="Correo" required maxlength="50" minlength="1"
                    name="txtCorreo" pattern="^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$" value="<?= $correo ?>">
                <div class="msg">Ingresa el correo electrónico y que tenga formato válido</div>
            </div>
            <br>
            <div id="txts">
                <span class="material-symbols-outlined" id="iconos">
                    lock
                </span>
                <input type="password" id="txtPassword" placeholder="Contraseña" required maxlength="50"
                    minlength="8" name="txtPassword" value="<?= $password ?>">
                <div class="msg">La contraseña debe ser de al menos 8 caracteres</div>
            </div>
            <?= $error ?>
            <br>
            <div>
                <a href="crearCuenta.php" target="_blank" id="registrarse">Registrarse</a>
            </div>
            <br>
            
            <div id="botones">
                <a href="index.php"><button type="button">Cancelar</button></a>
                <button type="submit" id="btnAceptar">Iniciar Sesion</button>
            </div>
            <br>
            
        </div>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
    <script src="js/login.js"></script>
</body>

</html>
