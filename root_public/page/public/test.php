<?php // DEBUG DES DIVERS FONCTIONS
    require( "../../../" . "assets/script/php/" . "functions.php");

    /*
    $rows = [
        "id"=>29113231422224322,
        "creation_date"=>2223122315332,
        "username"=>"Mazrazzaatin"
    ];

    separator();
    write(hashPassword("s1922aat_ca_va?", $rows));

    separator();

    $str = bin2hex(random_bytes(20));
    write($str);
    */

    $connexion = mysqli_connect("127.0.0.1", "root", "", "reseau");

    //$query = "INSERT INTO `users` (`username`,    `password`) VALUES " .
                    "(\"" . $connexion->real_escape_string("toast") . "\", \"none\");";

    //print_r($connexion->query($query));
?>