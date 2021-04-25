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
    
    session_start();

    ////////////////////////////////////////////////////////////////////
    // ETABLISSEMENT DE LA CONNECTION

    if (
        !isset($_POST["username"]) || 
        !isset($_POST["password"]) || 
        !isset($_POST["password2"])
        )
    {
        echo json_encode([
            "success" => false,
            "error"   => "Requête incorrecte."
        ]); exit();
    }

    $username  = $_POST["username"];
    $password  = $_POST["password"];
    $password2 = $_POST["password2"];

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
    // CHECKUP DE SECURITEE

    if ($password != $password2) {
        echo json_encode([
            "success" => false,
            "error"   => "Les deux mots de passent ne correspondent pas."
        ]); exit();
    }

    $result = $connexion->query( 
        "SELECT * FROM users WHERE id=\"". $connexion->real_escape_string($_SESSION["id"]) . "\";" 
        )->fetch_assoc();

    $hashed_password = hashPassword($password, $result);
    if ($hashed_password != $result["password"] || $username != $_SESSION["username"]) {
        echo json_encode([
            "success" => false,
            "error"   => "Entrées incorrectes."
        ]); exit(); 
    }

    ///////////////////////////////////////////////////////////////////
    // SUPPRESSION DU COMPTE

    removeAccount();

    ///////////////////////////////////////////////////////////////////

    echo json_encode([
        "success" => true,
        "error"   => ""
    ]); exit(); 

    mysqli_close($connexion);
?>