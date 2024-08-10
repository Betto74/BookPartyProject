<?php
    session_start();
    unset($_SESSION["correo"]);
    header("Location: index.php");
?>