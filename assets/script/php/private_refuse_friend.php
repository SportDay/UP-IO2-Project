<?php

    // ATTENTION
    // LE FICHIER QUI ACTIONNE CELUI CI SE TROUVE DANS : root_public/assets/script/php/
    $global_params = [
        "root"        => "../../../../",
        "root_public" => "../../../../root_public/",
    ];

    require($global_params["root"] . "assets/script/php/constants.php");
    require($global_params["root"] . "assets/script/php/functions.php");
    
    ////////////////////////////////////////////////////////////////////
    // ETABLISSEMENT DE LA CONNECTION

    session_start();

    if (
        !isset($_POST["refuse_friend"]) || !isset($_SESSION["refuse_friend"]) ||
              ($_POST["refuse_friend"]  !=        $_SESSION["refuse_friend"])
               
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

    $id = $connexion->query(
        "SELECT id FROM users WHERE username=\"" . $connexion->real_escape_string($username) . "\""
    )

    if ($id->num_rows = 0) { 
        // data base error
        echo json_encode([
            "success" => false,
            "error"   => "Cet utilisateur n'existe pas."
        ]); exit(); 
    }
    
    $id = $id->fetch_assoc()["id"];

    $connexion->query(
        "DELETE friends " .
        "WHERE (user_id_0=".$id." AND user_id_1=".$_SESSION["id"].") OR (".$_SESSION["id"]." AND".$id.")"
    );

    ///////////////////////////////////////////////////////////////////////////

    echo json_encode([
        "success" => true,
        "error"   => ""
    ]);

    mysqli_close($connexion);
    exit();
?>