<?php

  // IMPERATIF POUR LE FONCTIONNEMENT DU HEADER
  if (!isset($global_params["root_public"]) || !isset($global_params["root"]))
  {
    echo "ERROR: public root is not set";
    separator();
    exit();
  }

  /*///////////////////////////////////////////
  
  Listes des paramètres de _SESSION: 
    id, username | admin
    init_time | last_time | inactive_time | max_time
    connected 
  
  Liste des cookies : 
    cookie_id | cookie_pass

  /*///////////////////////////////////////////
  // CONSTANTES :

  $db_conf = json_decode( file_get_contents($global_params["root"] . "assets/script/sql/db_config.json") , true );
  $DB_URL       = $db_conf["DB_URL"];
  $DB_ACCOUNT   = $db_conf["DB_ACCOUNT"];
  $DB_NAME      = $db_conf["DB_NAME"];
  $DB_PASSWORD  = $db_conf["DB_PASSWORD"];

  $TIME_SESS_END        = 60 * 120;       // session prends fin au bout de 120 minutes (=> connection par cookie)
  $TIME_SESS_INACTIVE   = 60 * 15;        // session prends fin au bout de 15 minutes  (=> connection par cookie)
  $TIME_COOKIE_CONNECT  = (24*3600) * 7;  // cookie de connection expire au bout de 7 jours

  //////////////////////

  session_start();
  tryConnect(); // update session

  /////////////////////

  if (isset($global_params["redirect"]) && $global_params["redirect"])
  {
    // si l'utilisateur n'est pas connecté on head vers la page de connection
    // header($global_params["root_public"] + "page/public/login")
    // eventuellement avec un q?= de la page actuel pour pouvoir être redirigé ici ensuite
    redirectHomeConnect();
  }

  if (isset($global_params["admin_req"]) && $global_params["admin_req"] === TRUE)
  {
    redirectNotAdmin();
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
        {
          echo "unknown page";
          $global_params["title"] = unknown;
        }
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
      if (!$_SESSION["connected"])
        echo "<link rel=\"stylesheet\" type=\"text/css\" href=" . $global_params["root_public"] . "assets/css/login.css>";

      if (isset($global_params["css_add"]))
        foreach($global_params["css_add"] as $css)
          echo "<link rel=\"stylesheet\" type=\"text/css\" href=" . $global_params["root_public"] . "assets/css/" . $css.">";
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
      <?php
        if ($_SESSION["connected"])
          menu_when_connected();
        else
          menu_when_not_connected();
      ?>
    </div>

    

    <!-- ------------------------------------------ -->
    </div> </header>
    <div id = "mid_panel">
        <div id = "mid_container">
            <div style="text-align: center; margin: 7em auto 1em;"></div>