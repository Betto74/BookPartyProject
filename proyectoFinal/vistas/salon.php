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
    
    <!--  Bootstrap-datepicker -->
    <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet">
    <!--  Bootstrap-datepicker -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="css/salones.css"> -->
    <link rel="stylesheet" href="css/salones.css">     
    <link rel="stylesheet" href="css/menuTop.css">       
    <title>Salon</title>
</head>

<body>
    <div class="fixed">
        <!-- -------------------------- LOGIN -------------------------- -->
        <?php
            require_once("../datos/DAOSalon.php");
            require_once("../datos/DAODetallesSalon.php");
            require_once("../datos/DAOUsuario.php");
            require_once("../datos/DAOServicio.php");
            require_once("../datos/DAOReservas.php");
  
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

            if(isset($_SESSION["msg"])) {
                $msgInfo = explode("--", $_SESSION["msg"]);
                echo "<div class='alert $msgInfo[0] alert-dismissible fade show' role='alert'>
                        $msgInfo[1]
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                unset($_SESSION["msg"]);
            }
        ?>
    </div>
    <?php
        if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
            $salon = (new DAOSalon())->obtenerUno((int) $_GET["id"]);
            if($salon==null){
                $_SESSION["msg"]="alert-warning--Salon no encontrado";
                header("Location: index.php");
            }
            $detalles = (new DAODetallesSalon())->obtenerUno((int) $_GET["id"]);
            $duenio = (new DAOUsuario())->obtenerUno((int)$salon->idusuario);
            $servicios = (new DAOServicio())->obtenerServicios((int)$salon->idusuario);
            $fechasOcupadas = (new DAOReservas())->obtenerReservas($salon->idsalon);
        
        }
    ?>
    

    <div class="titulo">
        <div class="nombre">
            <h1><b><?php echo $salon->nombresalon ?></b></h1>
        </div>
        <br>
        <div class="acciones"><span class="material-symbols-outlined" id="upfa">upload</span><a href="">Compartir </a>
            <span class="material-symbols-outlined" id="upfa">favorite</span><a href="">Guardar</a>
        </div>
    </div>

    <div class="imagenes">
        <div class="col" style="background-image: url('img/default.png')"><br></div>
        <div class="col">
            <div class="subCol" style="background-image: url('img/default.png')"><br></div>
            <div class="subCol" style="background-image: url('img/default.png')"><br></div>
        </div>
        <div class="col" style="background-image: url('img/default.png')"><br></div>
    </div>

    <div class="information">
        <div class="inf-salon t80">
            <h4><b>Salon de fiestas ubicado en la ciudad de <?php echo $detalles->municipio ?>,<?php echo $detalles->estado ?></b></h4>
            <p><?php echo $detalles->area ?>m² - <?php echo $detalles->capacidad ?> personas - <?php echo $detalles->mesas   ?> mesas - <?php echo $detalles->sillas ?> sillas</p>
            <hr>
            <div class="flex">
                <div>
                    <span class="material-symbols-outlined size-icon">account_circle</span>

                </div>
                <div>
                    <h6>Propietario : <?php echo $duenio->nombre ?></h6>
                    <span>
                        <?php 
                            $fechaActual = date('Y-m-d'); 
                            $antiguedad = date_diff(date_create($duenio->iniciocuenta), date_create($fechaActual));
                            if ($antiguedad->y == 0) {
                                echo $antiguedad->m . " meses";
                            } else {
                                echo $antiguedad->y . " años";
                            }
                        ?>
                    </span>
                    <p>telefono: <?php echo $duenio->telefono ?></p>
                </div>

            </div>
            <hr>
            <div class="description">
                <p>
                    <?php echo $salon->descripcion ?>
                </p>
                <hr>
            </div>
        </div>
        <!--Boton Calendario!-->
        <div class="calendar t20">
            <p id="precio" data-precio="<?php echo $detalles->precio ?>">$<?php echo $detalles->precio ?> <b>MXN</b></p>
            <input type="text" class="form-control" id="calendario" name="calendario" readonly >
            <div id="reservar">
                <a id="aReservar" href="#" onclick="enviarReserva()">Reservar</a>
            </div>
            <hr>
            <p id="precioTotal"></p>
        </div>
        <!--Boton Calendario!-->


    </div>
    <div class="ben">
        <h5><b>Lo que ofrecemos</b></h5>
        <div class="benefits">
            
            <?php 
                for ($i = 0; $i <count($servicios); $i++) {           
            ?>
                     <div class="divBen">
                        <div>
                            <span class="material-symbols-outlined" id="iconoBen">radio_button_checked</span>
                            <span><?php echo $servicios[$i]->nombre?></span>
                        </div>
                    </div>
                    <br>
            <?php
                }
            ?>
        </div>
    </div>
    <hr style="margin: 2.5rem;">

    <div class="evaluaciones">
        <div class="evaluacionesTitulo">
            <span class="material-symbols-outlined">
                star
            </span>
            <h5><b>Evaluaciones</b></h5>
        </div>
        <br>
        <div class="panel">
            <div class="divPanel">
                <div>Calificación general</div>
                <br>
                <div><?php echo $detalles->estrellas ?></div>
            </div>
            <div class="divPanel">
                <div>Limpieza</div>
                <br>
                <div><span class="material-symbols-outlined" id="iconosPanel">
                        household_supplies
                    </span></div>
            </div>
            <div class="divPanel">
                <div>Veracidad</div>
                <br>
                <div><span class="material-symbols-outlined" id="iconosPanel">
                        check_circle
                    </span></div>
            </div>
            <div class="divPanel">
                <div>Llegada</div>
                <br>
                <div><span class="material-symbols-outlined" id="iconosPanel">
                        vpn_key
                    </span></div>
            </div>
            <div class="divPanel">
                <div>Comunicación</div>
                <br>
                <div><span class="material-symbols-outlined" id="iconosPanel">
                        mode_comment
                    </span></div>
            </div>
            <div class="divPanel">
                <div>Ubicación</div>
                <br>
                <div><span class="material-symbols-outlined" id="iconosPanel">
                        map
                    </span></div>
            </div>
            <div class="divPanel">
                <div>Calidad-precio</div>
                <br>
                <div><span class="material-symbols-outlined" id="iconosPanel">
                        sell
                    </span></div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <br>
    <hr style="margin: 2.5rem;">
    <div class="ubicacion">
        <h4><b>Dónde vas a estar</b></h4>
        <h5>Ubicación del salón</h5>
        <iframe id="iframeUbicacion"
            src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d14984.72519518718!2d-101.1941376!3d20.126482600000003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses-419!2smx!4v1710811465687!5m2!1ses-419!2smx"
            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <br>
    <br>
    <br>

    <footer class="ft">
        <div>
            <span>@2024 BookParty Inc. - Privacidad - Terminos - Mapa del Sitio - Informacion de la Compañia</span>
        </div>
        <div class="flex">
            <div class="flex">
                <span class="material-symbols-outlined size">language</span>
            </div>
            <div>
                <span>Español(MXN)</span>
            </div>

        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>

    <!-- Script de boostrap para el calendario -->
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        //pasaar la lista de php a js
        let reservas = <?php echo json_encode(array_column($fechasOcupadas, 'fecha')); ?>;
        console.log(reservas);
    </script>
    <!-- Script de boostrap para el calendario -->
    <script src="js/salon.js"></script>
</body>

</html>