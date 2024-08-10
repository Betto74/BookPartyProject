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
    <link rel="stylesheet" href="css/menuTop.css">
    <link rel="stylesheet" href="css/styleCrearCuenta.css">
    <link rel="stylesheet" href="css/styleRegistroSalon.css">
    

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


    <form action="" autocomplete="off">
        <div id="principal">
            <br>
            <div>
                <a>Registro de Salón</a>
            </div>
            <br>
            <div>
                <span class="material-symbols-outlined" id="iconos">
                    person
                </span>
                <input type="text" id="txtNombre" placeholder="Nombre del propietario" required maxlength="50"
                    minlength="1" name="txtNombreP">
            </div>

            <br>

            <div>
                <span class="material-symbols-outlined" id="iconos">
                    mail
                </span>
                <input type="email" id="txtCorreo" placeholder="Correo" required maxlength="50" minlength="1"
                    name="txtCorreo">
            </div>

            <br>

            <div>
                <span class="material-symbols-outlined" id="iconos">
                    call
                </span>
                <input type="tel" id="txtTelefono" placeholder="Telefono" required maxlength="10" minlength="10"
                    name="txtTelefono">
            </div>

            <br>

            <div>
                <span class="material-symbols-outlined" id="iconos">
                    location_on
                </span>
                <input type="text" id="txtDireccion" placeholder="Dirección del Salón" required maxlength="50"
                    minlength="10" name="txtDireccion">
            </div>

            <br>

            <div>
                <span class="material-symbols-outlined" id="iconos">
                    home
                </span>
                <input type="text" id="txtNombreSalon" placeholder="Nombre del Salón" required maxlength="40"
                    minlength="3" name="txtNombreSalon">
            </div>

            <br>

            <div>
                <textarea name="txtDescripcion" id="txtDescripcion" placeholder="Descripción" cols="30"
                    rows="10"></textarea>
            </div>

            <br>

            <div class="center">
                <input type="file" name="fotos" id="fotos" accept=".jpg,.jpeg,.png" hidden>
                <label for="fotos" class="lblfotos">
                    <span class="material-symbols-outlined">cloud_upload</span>
                    <p>Click para subir</p>
                </label>
            </div>

            <br>

            <div>
                <label for="cbxAdicionales">Extras</label>
                <div>
                    <select name="cbxAdicionales" id="cbxAdicionales" multiple>
                        <option value="Jardin">Jardin</option>
                        <option value="Instalaciones accesibles">Instalaciones accesibles</option>
                        <option value="Servicio de limpieza">Servicio de limpieza</option>
                        <option value="Sistema de sonido y luces">Sistema de sonido y luces</option>
                        <option value="Pista de baile">Pista de baile</option>
                        <option value="Servicio de renta de sillas y mesas">Servicio de renta de sillas y mesas</option>
                        <option value="Brincolín">Brincolín</option>
                        <option value="Cocina">Cocina</option>
                        <option value="Servicio de meseros">Servicio de meseros</option>

                    </select>
                </div>

            </div>

            <br>

            <div id="caracteristicas">
                <div>

                    <div>
                        <label for="m2">m²</label>
                    </div>
                    <div>
                        <input type="number" name="m2" class="inpt">

                    </div>
                </div>

                <div>

                    <div>
                        <label for="m2">personas</label>
                    </div>
                    <div>
                        <input type="number" name="m2" class="inpt">

                    </div>
                </div>

                <div>

                    <div>
                        <label for="m2">mesas</label>
                    </div>
                    <div>
                        <input type="number" name="m2" class="inpt">
                    </div>
                </div>

            </div>

            <div id="botones">
                <a href="index.php"><button type="button">Cancelar</button></a>
                <a href="index.php"><button type="submit">Crear</button></a>
            </div>
            <br>
        </div>


    </form>
    <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@2.0.1/dist/js/multi-select-tag.js"></script>
    <script>

        new MultiSelectTag('cbxAdicionales', {
            rounded: true,
            shadow: true,
            placeholder: 'Extras',
            tagColor: {
                textColor: '#327b2c',
                borderColor: '#92e681',
                bgColor: '#b8e9ec',
            },
            onChange: function (values) {
                console.log(values)
            }
        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>