<div class="dropdown">
    <a href="index.php" class="logo"></a>
    <button class="hambtn btn btn-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
    <span class="material-symbols-outlined icono">menu</span>
    <span class="material-symbols-outlined icono">account_circle</span>

    <?php 
        require_once "../datos/DAOUsuario.php";
        $id =(new DAOUsuario())->obtenerId($_SESSION["correo"]);
    ?>

    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="verReservaciones.php">Mis reservaciones</a></li>
        <hr>
        <li><a class="dropdown-item" href="crearCuenta.php?id=<?=$id?>">Editar mi cuenta</a></li>
        
    <hr>
        <li><a class="dropdown-item" href="cerrarSesion.php">Salir</a></li>
        <li><a class="dropdown-item" href="notFound.php">Help</a></li>
    </ul>
</div>