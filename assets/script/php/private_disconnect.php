<?php
    $global_params = [
        "root"        => "../../../../",
        "root_public" => "../../../../root_public/",
    ];

    require($global_params["root"] . "assets/script/php/constants.php");
    require($global_params["root"] . "assets/script/php/functions.php");
    
    session_start();

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

    $connexion->query( 
        "UPDATE users SET " . 
        " cookie_id=NULL," .
        " cookie_enabled=FALSE, " .
        " cookie_pass=NULL," .
        " cookie_expire="     . (time() - 3600) .
    
        " WHERE `id`=\"" . $_SESSION["id"] . "\" ;"
    );

    setcookie("cookie_id",      "", time() - 3600, $COOKIE_PATH);
    setcookie("cookie_pass",    "", time() - 3600, $COOKIE_PATH);
    setcookie("cookie_expire",  "", time() - 3600, $COOKIE_PATH);
    
    echo json_encode([
        "success" => true,
        "error"   => ""
    ]);

    session_unset();
    session_destroy();
    mysqli_close($connexion);
?>