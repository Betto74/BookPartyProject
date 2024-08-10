<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/css2?family=Krub:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/menuTop.css">
    <title>Reservaciones=</title> 
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
      require_once ("../datos/DAOReservas.php");
      require_once ("../datos/DAOUsuario.php");

      if(isset($_SESSION["msg"])){
        $msgInfo=explode("--",$_SESSION["msg"]);
        echo "<div class='alert $msgInfo[0]'>$msgInfo[1]</div>";
        unset($_SESSION["msg"]);
      }

      if(!isset($_SESSION["correo"])){
          $_SESSION["msg"]="alert-danger--Necesitas iniciar sesión";
          header("Location: index.php");
      }

      if(isset($_POST["id"]) && is_numeric($_POST["id"])){
          $dao = new DAOReservas();   
          if($dao->eliminar((int)$_POST["id"])){
              $_SESSION["msg"]="alert-success--Reserva eliminada exitósamente";
          }else{
            $_SESSION["msg"]="alert-danger--Ha ocurrido un error al trata de eliminar una reserva";
          }
          header("Location: verReservacionesDuenio.php");     
      }
  ?>

  <table class="table table-striped">
    <thead>
        <th>Salón</th>
        <th>Fecha inicio</th>
        <th>Fecha fin</th>
        <th>Días</th>
        <th>Usuario</th>
        <th>Teléfono</th>
        <th>Ver</th>
        <th>Cancelar</th>
    </thead>
    <tbody>
      <?php
      $correo = $_SESSION["correo"];
      $daoUsuarios = new DAOUsuario();
      $id = $daoUsuarios->obtenerId($correo);
      $reserva = new DAOReservas();

        $obtenerReservas = $reserva->obtenerReservasByDuenio($id);

      foreach($obtenerReservas as $fila){
        $fechainicio = new DateTime($fila->fechainicio);
        $fechafin = new DateTime($fila->fechafin);
        $dias = $fechafin->diff($fechainicio)->d;
        if ($dias == 0) {
            $dias = 1;
        }

        echo 
        "<tr>
            <td>".$fila->nombresalon."</td>
            <td>".$fila->fechainicio."</td>
            <td>".$fila->fechafin."</td>
            <td>$dias</td>
            <td>".$fila->usuario."</td>
            <td>".$fila->usuarioTel."</td>
            <td><a href='salo.php?id=".$fila->idsalon."'>Ver salón</a></td>
            <td><button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#mdlEliminar' value='$fila->idreserva' nombre='$fila->nombresalon'>Eliminar</button></td>
        </tr>";
      }
      ?>
    </tbody>
  </table>
  <div class="modal" tabindex="-1" id="mdlEliminar" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Confirmar eliminación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Está a punto de eliminar al usuario <b id="UsuarioEliminar"></b>, ¿Desea continuar?
        </div>
        <div class="modal-footer">
          <form action="verReservacionesDuenio.php" method="post">
          <button type="submit" class="btn btn-danger" id="btnEliminar" name="id">Eliminar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          </form>
        </div>
      </div>
    </div>
  </div>


  <a href="index.php" class="btn btn-primary">Regresar</a>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js" integrity="sha512-GWzVrcGlo0TxTRvz9ttioyYJ+Wwk9Ck0G81D+eO63BaqHaJ3YZX9wuqjwgfcV/MrB2PhaVX9DkYVhbFpStnqpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>   
  <script src="js/listaReservas.js"></script>
</body>
</html>