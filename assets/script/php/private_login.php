<?php // SORTIE : echo true|false; | true si connection réussis, false sinon
    require($global_params["root"] . "assets/script/php/functions.php");
    session_start();

    // GET LES INFOS DE CONNECTION
    $username = $_POST["username"];
    $password = $_POST["password"];
    $remember = $_POST["remember"];

    // PARSE
    if (!isValideName($login) || !isValidePassword($password)) {
        echo "false";
        exit();
    }

    // CONNECTION A LA BASE DE DONNEE
    $db_conf = json_decode( file_get_contents("../sql/db_config.json") , true );
    $connexion = mysqli_connect (
        $db_conf["DB_URL"],
        $db_conf["DB_ACCOUNT"],
        $db_conf["DB_PASSWORD"],
        $db_conf["DB_NAME"]
    );

    if (!$connexion) { 
        echo "false"; exit(); 
    }

    // COMPARER A LA BASE DE DONNEE

    $hashed_password = hashPassword($password, $row);




    // SI GOOD => CONNECTION
    

    // SINON => RIP

?>