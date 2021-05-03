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
        !isset($_POST["dm_token"]) || !isset($_SESSION["dm_token"]) ||
              ($_POST["dm_token"]  !=        $_SESSION["dm_token"])
               
               /*
                    quelqu'un qui veut utiliser ce fichier doit obligatoirement
                    recevoir un code attribué sur la page de paramètre
               */
        )
    {
        echo json_encode([
            "success" => false,
            "error"   => "token_error"
        ]); exit();
    }

    if (
        !isset($_POST["private"]) || !isset($_POST["last"]) || !isset($_POST["username"]) || !isset($_POST["message"])
    )
    {
        $private     = $_POST["private"];
        $last        = $_POST["last"];
        $friend      = $_POST["username"];
        $message     = $_POST["message"];
    }

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

    ////////////////////////////////////////////////////////////////////




    ////////////////////////////////////////////////////////////////////

    echo json_encode([
        "success"     => true
    ]);
    
    mysqli_close($connexion);
    exit();
?>