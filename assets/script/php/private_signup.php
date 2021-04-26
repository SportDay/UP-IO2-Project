<?php 

    // ATTENTION
    // LE FICHIER QUI ACTIONNE CELUI CI SE TROUVE DANS : root_public/assets/script/php/
    $global_params = [
        "root"        => "../../../../",
        "root_public" => "../../../../root_public/",
    ];

    require($global_params["root"] . "assets/script/php/constants.php");
    require($global_params["root"] . "assets/script/php/functions.php");
    session_start();

    if (
        !isset($_POST["username"]) || 
        !isset($_POST["password"])
        )
    {
        echo json_encode([
            "success" => false,
            "error"   => "Requête incorrecte."
        ]); exit();
    }

    // GET LES INFOS DE SIGNUP
    $username = $_POST["username"];
    $password = $_POST["password"];

    // PARSE
    if ($username == $password)
    {
        echo json_encode([
            "success" => false,
            "error"   => "Veuillez ne pas utiliser votre pseudo comme mot de passe."
        ]); exit();
    }

    if (!isValideName($username) || !isValidePassword($password)) {
        // invalide name / password
        echo json_encode([
            "success" => false,
            "error"   => "Problème de formattage."
        ]); exit();
    }

    // CONNECTION A LA BASE DE DONNEE
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

    // VERIFIER QUE LE COMPTE EST LIBRE

    $user_query = "SELECT * FROM users WHERE username=\"". $connexion->real_escape_string($username) . "\";";

    if ($connexion->query($user_query)->num_rows != 0)
    {
        echo json_encode([
            "success" => false,
            "error"   => "Nom d'utilisateur indisponible."
        ]); exit(); 
    }

    // CREER LE COMPTE
    
    $connexion->query(
                        "INSERT INTO `users` (`username`,    `password`) VALUES " .
                        "(\"" . $connexion->real_escape_string($username) . "\", \"none\");"
                    );

    $result = $connexion->query($user_query)->fetch_assoc();
    $hashed_password = hashPassword($password, $result);

    mysqli_query($connexion, 
            "UPDATE users SET " . 
            "password=\""   . $connexion->real_escape_string($hashed_password) . "\" " .
            "WHERE `id`=\"" . $result["id"] . "\" ;"
        );


    // SIGN IN
    $_SESSION["id"]             = $result["id"];
    $_SESSION["username"]       = $result["username"];
    $_SESSION["admin"]          = $result["admin"];

    $_SESSION["enable_public"]  = $result["enable_public"];
    $_SESSION["public_name"]    = $result["public_name"];
    $_SESSION["public_image"]   = $result["public_image"];

    $_SESSION["init_time"]      = time();
    $_SESSION["last_time"]      = time();
    $_SESSION["inactive_time"]  = time() + $TIME_SESS_INACTIVE;
    $_SESSION["max_time"]       = time() + $TIME_SESS_END;

    $_SESSION["connected"]      = true;

    mysqli_query($connexion, 
                "UPDATE users SET last_join=" . $_SESSION["last_time"] 
                . " WHERE `id`=" . $_SESSION["id"]
            );

    // remove old cookie

    setcookie("cookie_id",      "", time() - 3600, $COOKIE_PATH);
    setcookie("cookie_pass",    "", time() - 3600, $COOKIE_PATH);
    setcookie("cookie_expire",  "", time() - 3600, $COOKIE_PATH);

    // try to generate unused cookie id
    $cookie_id       = randomString();
    $cookie_password = randomString();

    for ($i = 0; $i < 21; $i++) { // pas plus de 20x pour eviter une boucle infinie
        if ($i==20) {
            echo json_encode([
                "success" => true,
                "error"   => "Cookie indisponible."
            ]);
            mysqli_close($connexion);
            exit();
        }
        
        if ( $connexion->query(
                "SELECT * FROM users WHERE cookie_id=\"". $connexion->real_escape_string($cookie_id) . "\";"
                )->num_rows != 0
            ) 
        {
            $cookie_id = randomString();
            continue;
        }

        $i = 20; // break (cookie_id dispo trouvé)
    }

    // store cookie_id and pass
    $cookie_expire = time() + $TIME_COOKIE_CONNECT;

    setcookie("cookie_id",      $cookie_id,       $cookie_expire, $COOKIE_PATH);
    setcookie("cookie_pass",    $cookie_password, $cookie_expire, $COOKIE_PATH);
    setcookie("cookie_expire",  $cookie_expire,   $cookie_expire, $COOKIE_PATH);

    $connexion->query( 
            "UPDATE users SET " . 
            " cookie_id=\""       . $cookie_id . "\"," .
            " cookie_enabled="   . "TRUE, " .
            " cookie_pass=\"" . $cookie_password . "\"," .
            " cookie_expire="     . $cookie_expire .
        
            " WHERE `id`=\"" . $_SESSION["id"] . "\" ;"
        );

    ///////////
    echo json_encode([
        "success" => true,
        "error"   => ""
    ]);

    mysqli_close($connexion);
?>