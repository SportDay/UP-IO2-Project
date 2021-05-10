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
        !isset($_POST["remove_friend"]) || !isset($_SESSION["remove_friend"]) ||
              ($_POST["remove_friend"]  !=        $_SESSION["remove_friend"])
               
               /*
                    quelqu'un qui veut utiliser ce fichier doit obligatoirement
                    recevoir un code attribué sur la page de paramètre
               */
        )
    {
        echo json_encode([
            "success" => false,
            "error"   => "Requête incorrecte."
        ]); exit();
    }

    if (!isset($_POST["username"])) {
        echo json_encode([
            "success" => false,
            "error"   => "Requête incorrecte."
        ]); exit();
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

    $fid = $connexion->query(
        "SELECT id FROM users WHERE username=\"" . $connexion->real_escape_string($_POST["username"]) . "\""
    )->fetch_assoc()["id"];

    $connexion->query(
        "DELETE FROM friends WHERE (user_id_0=".$_SESSION["id"]." AND user_id_1=".$fid.") OR (user_id_0=".$fid." AND user_id_1=".$_SESSION["id"].")"
    );

    ////////////////////////////////////////////////////////////////////

    echo json_encode([
        "success" => true 
    ]);

    mysqli_close($connexion);
    exit();
?>