<?php

  /*

      CONSTANTS :
      Ce fichier contient un ensemble de constantes utiles à chaque endroit du site.

  */

  $db_conf = json_decode( file_get_contents($global_params["root"] . "assets/script/sql/db_config.json") , true );
  $DB_URL       = $db_conf["DB_URL"];
  $DB_ACCOUNT   = $db_conf["DB_ACCOUNT"];
  $DB_NAME      = $db_conf["DB_NAME"];
  $DB_PASSWORD  = $db_conf["DB_PASSWORD"];

  $TIME_SESS_END        = 1 * 60 * 120;       // session prends fin au bout de 120 minutes (=> connection par cookie)
  $TIME_SESS_INACTIVE   = 1 * 60 * 15;        // session prends fin au bout de 15 minutes  (=> connection par cookie)
  $TIME_COOKIE_CONNECT  = 1 * (24*3600) * 7;  // cookie de connection expire au bout de 7 jours

  $COOKIE_PATH = "/"; 
  // Je me prendrai la tête plus tard sur pourquoi ça ne fonctionne pas avec un relative_path
  //str_replace('\\', '/', dirname(__FILE__)) . "/" . $global_params["root_public"];
  //$debuging = true;
  //debug("=> " . dirname(__FILE__));

  $TIME_REROLL = 1 * (24*3600) * 1;


?>