<?php
  if (isset($global_params["redirect"]) && $global_params["redirect"])
  {
    // si l'utilisateur n'est pas connecté on head vers la page de connection
    // header($global_params["root_public"] + "page/public/login")
    // eventuellement avec un q?= de la page actuel pour pouvoir être redirigé ici ensuite
  }

?>

<!DOCTYPE html>
<html>
<head>
    
    <meta charset="utf-8">
    <title>
      <?php
        if (isset($global_params["title"])) 
          echo $global_params["title"];
        else
          echo "unknown page";
      ?>
    </title>
    <!-- global css -->
    <link rel="stylesheet" type="text/css" href=<?php
      echo $global_params["root_public"] . "assets/css/";
      if (isset($global_params["css"])) echo $global_params["css"];
      else                              echo "all.css";
    ?>>
    <!-- additionnal css and style -->
    <?php 
      //if (isset($global_params["css_add"]))
      //if (isset($global_params["style"]))
    ?>

  </head>
  <body>
    <header> <div id = "up_panel">
    <!-- HEADER -->
    <!-- ------------------------------------------ -->

      <h1>Page : <?php echo $global_params["title"]; ?></h1>
      
    <!-- ------------------------------------------ -->
    </div> </header>
    <?php padding(3); ?>
    <div id = "mid_panel">