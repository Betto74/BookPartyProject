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
    <title>Mis resevaciones</title>
</head>
<body>
    <main>
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
            require_once("../datos/DAOReservas.php");
            require_once("../datos/DAOUsuario.php");

            if (isset($_SESSION["msg"])) {
                $msgInfo = explode("--", $_SESSION["msg"]);
                echo "<div class='alert $msgInfo[0]'>$msgInfo[1]</div>";
                unset($_SESSION["msg"]);
            }   


            if (isset($_POST["id"]) && is_numeric($_POST["id"])) {
                $dao = new DAOReservas();
                if ($dao->eliminar($_POST["id"])) {
                    $_SESSION["msg"] = "alert-success--Reserva eliminada exitósamente";
                } else {
                    $_SESSION["msg"] = "alert-danger--Ha ocurrido un error al trata de eliminar una reserva";
                }
                header("Location: verReservaciones.php");   
            }    
        ?>


        <div>Mis Reservaciones</div>
        <table id="example" class="table table-striped">
            <thead>
                <tr>
                    <th>Salón</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Días</th>
                    <th>Dueño</th>
                    <th>Teléfono</th>
                    <th>Ver</th>
                    <th>Cancelar</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if (!(isset($_SESSION["correo"]) && strlen($_SESSION["correo"]) > 0)) {
                        $_SESSION["msg"] = "alert-success--Necesitas estar logueado para realizar esta accion";
                        header("Location: index.php");
                        exit();
                    }
                    
                    $correo = $_SESSION["correo"];
                    $id = (new DAOUsuario())->obtenerId($correo);
                    $lista = (new DAOReservas())->obtenerReservasByUsuario($id);
                    
                    foreach ($lista as $reserva) {
                        $fecha1 = new DateTime($reserva->fechainicio);
                        $fecha2 = new DateTime($reserva->fechafin);
                        $diferencia = $fecha2->diff($fecha1)->d;
                        if( $diferencia == 0 ) $diferencia = 1;
                        echo "<tr>
                            <td>{$reserva->nombresalon}</td>
                            <td>{$reserva->fechainicio}</td>
                            <td>{$reserva->fechafin}</td>
                            <td>{$diferencia}</td>
                            <td>{$reserva->duenio}</td>
                            <td>{$reserva->dueniotel}</td>
                            <td><a href='salon.php?id={$reserva->idsalon}'> Ver Salón </a></td>
                            <td><button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#mdlEliminar' value='{$reserva->idreserva}' nombre='{$reserva->nombresalon}'>Eliminar</button></td>
                        </tr>";
                    }
                ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-primary">Regresar</a>
    </main>

    <div class="modal" tabindex="-1" id="mdlEliminar" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Está a punto de eliminar la reserva <b id="UsuarioEliminar"></b>, ¿Desea continuar?
                </div>
                <div class="modal-footer">
                    <form action="verReservaciones.php" method="post">
                        <button type="submit" class="btn btn-danger" id="btnEliminar" name="id">Eliminar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"
    integrity="sha512-GWzVrcGlo0TxTRvz9ttioyYJ+Wwk9Ck0G81D+eO63BaqHaJ3YZX9wuqjwgfcV/MrB2PhaVX9DkYVhbFpStnqpQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>    
    <script src="js/listaReservas.js"></script>
</body>
</html>
