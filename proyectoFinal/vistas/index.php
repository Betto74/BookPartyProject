<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/css2?family=Krub:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/menuTop.css">
    <link rel="stylesheet" href="css/style.css">
  

  <title>Bootstrap demo</title>
</head>

<body>

  <div class="fixed">

<!-- -------------------------- LOGIN -------------------------- -->
  <?php   
    require_once("../datos/DAOSalon.php");
    require_once("../datos/DAODetallesSalon.php");
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
<!-- -------------------------- LOGIN -------------------------- -->

  <div class="bar-nav">


    <div class="nav-grid">
      <div class="grupo_input gT"> <span class="material-symbols-outlined">search</span><input type="text"
          placeholder="Buscar"> </div>
      <div class="grupo_input gT"> <span class="material-symbols-outlined">search</span><input type="text"
          placeholder="Fecha"></div>
      <div class="grupo_input gT"> <span class="material-symbols-outlined">search</span><input type="text"
          placeholder="Cantidad"></div>
      <div class="grupo_input"> <span class="material-symbols-outlined">tune</span><input type="button"
          value="Filtrar"></div>


      </div>
      

      <hr class="divide">

      <?php 
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

  </div>

  

  <div class="salones">

    <?php 

      

      $dao = new DAOSalon();
      $salones=$dao->obtenerTodos();


      $dao = new DAODetallesSalon();
      $detalles=$dao->obtenerTodos();

      for ($i = 0; $i <count($salones); $i++) {
        // Imprimir el objeto completo usando var_dump()
        
        
        ?>
        <div class="grupo_input">
          <span class="material-symbols-outlined fav">favorite</span>
          <a href="salon.php?id=<?php echo $salones[$i]->idsalon?>">
            <div class="imgs" style="background-image: url('<?php echo $salones[$i]->imagenprincipal; ?>')"></div>
          </a>
          <span class="font-name"><?php echo $salones[$i]->nombresalon?></span><br>
          <span>$<?php echo $detalles[$i]->precio?></span><br>
          <div class="rate">
            <span>
              <span class="material-symbols-outlined star">star</span>
            </span><?php echo $detalles[$i]->estrellas?>
          </div>
        </div>

    <?php   
      }
    ?>




  </div>


  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>