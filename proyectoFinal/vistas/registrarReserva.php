<?php

    session_start();

    require_once("../datos/DAOReservas.php");
    require_once("../datos/DAOUsuario.php");
    require_once("../modelos/Reservas.php");


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
        $calendario = $_POST['calendario'];
        $salon = $_POST['nombreParametro'];

        if (!preg_match('/^(\d{4}-\d{2}-\d{2}) \/ (\d{4}-\d{2}-\d{2})$/', $calendario)) {
            $_SESSION["msg"]="alert-warning--Necesitas ingresar un rango de fechas valido";
            header("Location: index.php");
        }
        else{
            $fechasSeparadas = explode(" / ", $calendario);
            $fechaI=$fechasSeparadas[0];
            $fechaF=$fechasSeparadas[1];
            $fechasSeparadas[0] = new DateTime($fechasSeparadas[0]);
            $fechasSeparadas[1] = new DateTime($fechasSeparadas[1]);
            $fechaActual = new DateTime();
            $fechaActual->setTime(0, 0, 0, 0);

            if( !isset($_SESSION["correo"]) ){

                $_SESSION["msg"]="alert-warning--Necesitas iniciar sesion para reservar";
                header("Location: salon.php?id=".$salon);
            }
            else if((int)$salon <= 0){
                $_SESSION["msg"]="alert-warning--No se encontro el salon";
                header("Location: index.php");
            }
            else if( count( (new DAOReservas)->obtenerEntreFechas($salon,$fechaI,$fechaF) )>0 ){
                $_SESSION["msg"]="alert-warning--El rango no debe contener fechas ocupadas";
                header("Location: salon.php?id=".$salon);

            }
            else if ($fechasSeparadas[1] < $fechasSeparadas[0]) {
     
                $_SESSION["msg"]="alert-warning--El rango de fechas debe ser valido";
                header("Location: salon.php?id=".$salon);
            } 
            else if (  $fechasSeparadas[0]  < $fechaActual ){
                $_SESSION["msg"]="alert-warning--El rango de fechas debe ser valido";
                header("Location: salon.php?id=".$salon);
            }
            else{
                $fechasSeparadas[0]=$fechasSeparadas[0]->format("Y-m-d");
                $fechasSeparadas[1]=$fechasSeparadas[1]->format("Y-m-d");
                $reserva = new Reservas();
                
                $reserva -> idsalon=(int)$salon;
                $reserva -> idusuario= (new DAOUsuario())->obtenerId($_SESSION["correo"]);
                $reserva -> fechainicio= $fechasSeparadas[0];
                $reserva -> fechafin=$fechasSeparadas[1];
            
                if( (new DAOReservas())->insertarReserva($reserva) ){
                    $_SESSION["msg"]="alert-success--Se ha efectuado la reserva correctamente";
                    header("Location: salon.php?id=".$salon);
                }
                else{
                    $_SESSION["msg"]="alert-warning--Hubo un problema con la reserva";
                    header("Location: salon.php?id=".$salon);
                }
            }
        }
        
    } else {
    
        $_SESSION["msg"]="alert-warning--Hubo un problema con la reserva";
            header("Location: salon.php?id=1");
        
    }
?>