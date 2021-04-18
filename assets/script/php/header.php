<?php
  if (!isset($global_params["root_public"]))
  {
    echo "ERROR: public root is not set";
    separator();
    quit();
  }

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
    <link rel="icon" href=<?php echo $global_params["root_public"] . "/assets/image/logo-rond.ico" ; ?> />
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
      if (isset($global_params["css_add"]))
        foreach($global_params["css_add"] as $css)
          echo "<link rel=\"stylesheet\" type=\"text/css\" href=" . $global_params["root_public"] . "assets/css/" . $css;

      //if (isset($global_params["style"]))
    ?>

  </head>
  <body>
    <header> <div id = "up_panel">
    <!-- HEADER -->
    <!-- ------------------------------------------ -->

    <div class="containers_three_left_00">
      <div style="padding-left:10px">
      <img 
        class="image_001"
        src=<?php echo $global_params["root_public"] . "assets/image/logo.jpg" ?> 
        alt="Harry Play, Role Potter"
        width="80" height="80"
        ></div>
        
    </div>

    <div class="containers_three_mid_00">
      <h1><?php echo $global_params["title"]; ?></h1>
    </div>

    <div class="containers_three_right_00">
      <p>Icone Utilisateur + Menu deroulant</p>
    </div>

    <!-- ------------------------------------------ -->
    </div> </header>
    <div id = "mid_panel">
        <div id = "mid_container">