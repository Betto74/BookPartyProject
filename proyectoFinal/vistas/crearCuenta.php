<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/css2?family=Krub:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/styleCrearCuenta.css">
<link rel="stylesheet" href="css/menuTop.css">
</head>

<body>

<div class="fixed" >
    <?php 
    session_start();
   
    if((isset($_SESSION["correo"]) && strlen($_SESSION["correo"])>0)){
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
    <?php
        require_once "../datos/DAOUsuario.php";
        require_once "../modelos/Usuario.php";
    
        $error = "";
        

        $usuario = new Usuario();

        if (isset($_GET["id"]) && is_numeric($_GET["id"])) {

            //Cuando se recibe el id entonces hay que obtener los datos del usuario
            $usuario = (new DAOUsuario())->obtenerUno((int) $_GET["id"]);
           
            if($usuario==null){
              $_SESSION["msg"]="alert-warning--Usuario no encontrado";
              header("Location: index.php");
            }
          } 
          if(count($_POST)>0) {
            //Verificar si llegaron datos por POST es porque se está agregando o editando
            $usuario->idusuario=$_POST["txtId"];
            $usuario->nombre=$_POST["txtNombre"];
            $usuario->correo=$_POST["txtCorreo"];
            $usuario->telefono=$_POST["txtTelefono"];
            if($usuario->idusuario==0){
              $usuario->contrasenia=$_POST["txtContrasenia"];
              $usuario->contrasenia2=$_POST["txtConfirmarContrasenia"];
              $usuario->rol=$_POST["opcion"];
            }    
            
          
            //Validar los datos
            if(strlen($usuario->nombre)>=2 && strlen($usuario->nombre)<=50 &&
                preg_match("/^[1-9][0-9]{9}$/",$usuario->telefono) &&
              filter_var($usuario->correo, FILTER_VALIDATE_EMAIL)!=false  ){ 
               
              $dao=new DAOUsuario();
              
              if($usuario->idusuario==0){
                
                //Revisar la contraseña
                if(strlen($usuario->contrasenia)>=8 && strlen($usuario->contrasenia)<=50 &&
                 $usuario->contrasenia === $usuario->contrasenia2 &&
                ( $usuario->rol=='usuario' || $usuario->rol=='duenio')){
                    
                
                  if(($dao->agregar($usuario))>0){
                    $_SESSION["msg"]="alert-success--Usuario almacenado correctamente";
                    header("Location: index.php");
                  }else{
                    $_SESSION["msg"]="alert-warning--Ha ocurrido un error";
                    header("Location: index.php");
                    
                  }
                }else{
                    $error = '<div class="msg" style="color: red">Usuario y/o contraseña incorrectos</div>';
                  
                }
              }else{
               
                //var_dumb($usuario);
                if($dao->editar($usuario)){
                    $_SESSION["msg"]="alert-success--Usuario almacenado correctamente";
                    header("Location: index.php");
                }else{
                    $_SESSION["msg"]="alert-warning--Ha ocurrido un error";
                    header("Location: index.php");
                }
              }
            }else{
               
                $validado="validado";
            }
           }
          

    ?>
    <form action="" autocomplete="off" method="POST">
        <div id="principal">
            <input type="hidden" id="txtId" name="txtId"
            value="<?=$usuario->idusuario?>">
            <br>
            <div>
                <a>Registro</a>
            </div>
            <br>
            <div>
                <span class="material-symbols-outlined" id="iconos">
                    person
                </span>
                <input type="text" id="txtNombre" placeholder="Nombre" required maxlength="50" minlength="2"
                    name="txtNombre" class="valid" value="<?=$usuario->nombre?>">
                <div class="clsError">El nombre es obligatorio y no cumple con los parametros establecidos (entre 2 y 50)</div>

            </div>
            <br>
            <div>
                <span class="material-symbols-outlined" id="iconos">
                    mail
                </span>
                <input type="email" id="txtCorreo" placeholder="Correo" required maxlength="50" minlength="1"
                    name="txtCorreo" value="<?=$usuario->correo?>">
                <div class="clsError">El email es obligatorio y debe tener un formato válido</div>

            </div>
            <br>
            
            <?php 
            if($usuario->idusuario==0){
            ?>
            <div>
                <span class="material-symbols-outlined" id="iconos">
                    lock
                </span>
                <input type="password" id="txtContrasenia" placeholder="Contraseña" required maxlength="50"
                    minlength="1" name="txtContrasenia" value="<?=$usuario->contrasenia?>">
                <div class="clsError">La contraseña es obligatoria y no cumple con los parametros requeridos (mínimo 8 caracteres)</div>
            </div>
            <br>
            <div>
                <span class="material-symbols-outlined" id="iconos">
                    lock
                </span>
                <input type="password" id="txtConfirmarContrasenia" placeholder="Confirmar contraseña" required
                    maxlength="50" minlength="1" name="txtConfirmarContrasenia" value="<?=$usuario->contrasenia2?>">
                    <div class="clsError">La contraseña es obligatoria y deben coincidir</div>
                </div>
            <br>
            <?php
            }
            ?>
            <div>
                <span class="material-symbols-outlined" id="iconos">
                    call
                </span>
                <input type="tel" id="txtTelefono" placeholder="Telefono" required maxlength="10" minlength="10"
                    name="txtTelefono" value="<?=$usuario->telefono?>">
                <div class="clsError">El teléfono debe tener 10 dígitos</div>
            </div>
            <br>
            <?php 
            if($usuario->idusuario==0){
            ?>
            <div>
            <div class="ContenedorChecks">
                <input type="radio" required id="opUsuario" name="opcion" class="chekOps" value="usuario" checked>
                <label for="opUsuario">Usuario</label>
                <input type="radio" required id="opDuenio" name="opcion" class="chekOps" value="duenio" <?= $usuario->rol=='duenio'?'checked':''  ?>>
                <label for="opDuenio">Dueño</label>
            </div>
            <br>
            <?php
            }

            ?>
            <?= $error ?>
            <div id="botones">
                <a href="index.php"><button type="button">Cancelar</button></a>
                <a href="index.php"><button type="submit" id="btnAceptar">Aceptar</button></a>
            </div>
            <br>
        </div>
    </form>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
    <script src="js/crearCuenta.js" ></script>
</body>

</html>