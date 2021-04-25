<?php

    // ATTENTION
    // LE FICHIER QUI ACTIONNE CELUI CI SE TROUVE DANS : root_public/assets/script/php/
    $global_params = [
        "root"        => "../../../../",
        "root_public" => "../../../../root_public/",
    ];

    require($global_params["root"] . "assets/script/php/constants.php");
    require($global_params["root"] . "assets/script/php/functions.php");
    require($global_params["root"] . "assets/script/php/security.php");
    
    ////////////////////////////////////////////////////////////////////
    // ETABLISSEMENT DE LA CONNECTION

    session_start();

    if (
        !isset($_POST["remove_public"]) || !isset($_SESSION["remove_public"]) ||
              ($_POST["remove_public"]  !=        $_SESSION["remove_public"])
               
               /*
                    quelqu'un qui veut utiliser ce fichier doit obligatoirement
                    recevoir un code attribué sur la page de paramètre
               */
        )
    {
        unset($_SESSION["remove_public"]);
        echo json_encode([
            "success" => false,
            "error"   => "Requête incorrecte."
        ]); exit();
    } unset($_SESSION["remove_public"]);

    $connexion = mysqli_connect (
        $db_conf["DB_URL"],
        $db_conf["DB_ACCOUNT"],
        $db_conf["DB_PASSWORD"],
        $db_conf["DB_NAME"]
    );

    if (!$connexion) { 
        // data base error
        echo json_encode([
            "success" => false,
            "error"   => "Base de donnée hors d'accès."
        ]); exit(); 
    }

    ///////////////////////////////////////////////////////////////////////////

    removePublicPage();

    ///////////////////////////////////////////////////////////////////////////

    echo json_encode([
        "success" => true,
        "error"   => ""
    ]); exit(); 

    mysqli_close($connexion);

?>