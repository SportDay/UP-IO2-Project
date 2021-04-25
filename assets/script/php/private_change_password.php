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
        !isset($_POST["old_password"]) || 
        !isset($_POST["new_password"]) || 
        !isset($_POST["new_password2"])
        )
    {
        echo json_encode([
            "success" => false,
            "error"   => "Requête incorrecte."
        ]); exit();
    }

    $old_password  = $_POST["old_password"];
    $new_password  = $_POST["new_password"];
    $new_password2 = $_POST["new_password2"];

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

    if ($new_password != $new_password2) {
        echo json_encode([
            "success" => false,
            "error"   => "Les deux mots de passent ne correspondent pas."
        ]); exit();
    }

    if (!isValidePassword($new_password)) {
        echo json_encode([
            "success" => false,
            "error"   => "Problème de formattage."
        ]); exit();
    }


    $result = $connexion->query( 
        "SELECT * FROM users WHERE id=\"". $connexion->real_escape_string($_SESSION["id"]) . "\";" 
        )->fetch_assoc();

    $hashed_old_password = hashPassword($old_password, $result);
    $hashed_new_password = hashPassword($new_password, $result);

    if ($hashed_old_password != $result["password"] ) {
        echo json_encode([
            "success" => false,
            "error"   => "Entrées incorrectes."
        ]); exit(); 
    }

    ////////////////////////////////////////////////////////////////////
    // CHANGEMENT MOT DE PASSE

    mysqli_query($connexion, 
            "UPDATE users SET " . 
            "password=\""   . $connexion->real_escape_string($hashed_new_password) . "\" " .
            "WHERE `id`=\"" . $result["id"] . "\" ;"
        );

    echo json_encode([
        "success" => true,
        "error"   => ""
    ]); exit(); 

    mysqli_close($connexion);
?>