<?php

/*
    INIT_DATABASE:
    Activez ce fichier ou ouvrez sa page si vous souhaitez reinitialiser la base donnée.
*/


// INTIALISATION DE LA BASE DE DONNEE

if (false) { 
    // PAR DEFAUT ON NE VEUT PAS QUE QUELQU'UN PUISSE LANCER CE FICHIER PAR ERREUR
    
    // Normalement ce fichier devrait être executé uniqument depuis la commande du serveur,
    // dans notre cas de figure c'est un peu compliqué, donc on l'execute depuis un navigateur
    // et on le place comme une page
    exit();
}


$global_params = [
    "root"        => "../",
    "root_public" => "",
];

require($global_params["root"] . "assets/script/php/constants.php");
require($global_params["root"] . "assets/script/php/functions.php");

//////////////////////////////////////////////
// CREATION DES TABLES

$connexion = mysqli_connect( $db_conf["DB_URL"], $db_conf["DB_ACCOUNT"], $db_conf["DB_PASSWORD"] );
if (!$connexion) {
    echo "data base error";
    exit();
}

$table_create = file_get_contents($global_params["root"] . "assets/script/sql/table_create.sql");
$connexion->multi_query($table_create);

$connexion = mysqli_connect ( $db_conf["DB_URL"], $db_conf["DB_ACCOUNT"], $db_conf["DB_PASSWORD"], $db_conf["DB_NAME"] );
if (!$connexion) {
    echo "data base error";
    exit(); 
}

//////////////////////////////////////////////

// REMPLISSAGE DES TABLES

$nBots = 10; // nombre de bots auto générés
$users = [   // vrais comptes
    [
        "username"  =>  "root",
        "password"  =>  "Vanille1", 
        "admin"     =>  TRUE 
    ],[
        "username"  =>  "Carl",
        "password"  =>  "Vanille2", 
        "admin"     =>  TRUE 
    ],[
        "username"  =>  "SportDay",
        "password"  =>  "Vanille3", 
        "admin"     =>  TRUE 
    ],[
        "username"  =>  "Wilfrid",
        "password"  =>  "Vanille4", 
        "admin"     =>  FALSE 
    ],[
        "username"  =>  "Leila",
        "password"  =>  "Vanille5", 
        "admin"     =>  FALSE
    ],[
        "username"  =>  "Fred",
        "password"  =>  "Vanille6", 
        "admin"     =>  FALSE 
    ]
];


// CREATION DE COMPTES CLASSIQUES (sans page publique par défaut)
foreach($users as &$user) {
    $user = addUser($user);
}

// CREATION DE PAGES PUBLICS
$bots = [];
for ($i = 0; $i < $nBots; $i++) {
    $bots += addBot($i);
}

// DEMANDE D'AMIS
foreach($users as $user) {
    // faire en sorte que tout les comptes par défaut soient amis
    foreach($users as $user2)
        if (
                $user["id"]!=$user2["id"] &&
                $connexion->query("SELECT id FROM friends " . 
                "WHERE (user_id_0=".$user ["id"]." AND user_id_1=".$user2["id"].") ".
                "OR    (user_id_0=".$user2["id"]." AND user_id_1=".$user ["id"].")"
                )->num_rows == 0 
        )
            $connexion->query(
                "INSERT INTO friends (user_id_0, user_id_1, accepted) VALUES ( ".
                $user["id"].", ".$user2["id"].", 1".
                ")"
            );

    // ajouter quelque bots

}

// PAGE LIKED
for ($i = 0; $i < $nBots; $i++) {
    // chaque bot doit liker au moins une page


}

// SUPPRIMER TOUTE LES SESSIONS :
foreach(glob(ini_get("session.save_path") . "/*") as $sessionFile)
    unlink($sessionFile);

mysqli_close($connexion);
write("La base de donnée a été réinitialisé");

////////////////////////////////////////////////////////////////////////////////////////////////////////

function addUser($user) {
    
    $connexion = $GLOBALS["connexion"];
    $connexion->query(
        "INSERT INTO `users` (`username`,    `password`, `admin`) VALUES " .
        "(\"" . $connexion->real_escape_string($user["username"])                                   . "\", " .
         "\"" . $connexion->real_escape_string(password_hash($user["password"], PASSWORD_DEFAULT))  . "\", " .
         "\"" . $connexion->real_escape_string($user["admin"])                                      . "\");"
    );

    return $user + 
        [
            "id"=> $connexion->query(
                                        "SELECT id FROM users WHERE username=\"".$connexion->real_escape_string($user["username"])."\""
                                    )->fetch_assoc()["id"]
        ];

}

function addBot($index) {
    
    $username = "Bot_" . str_pad($index, 4, "0", STR_PAD_LEFT);
    $bot = addUser([
        "username"  =>  $username,
        "password"  =>  "BotPassword", 
        "admin"     =>  FALSE 
    ]);

    $public_page = generateRandomPublicData();

    $connexion = $GLOBALS["connexion"];
    $connexion->query(
        "UPDATE `users` SET " . 
        "`enable_public`=TRUE, " .
        "`public_name`=\""  . $connexion->real_escape_string($public_page["public_name"]) . "\", " .
        "`public_image`=" . $public_page["public_image"] . ", " .
        "`last_reroll`="  . time() . ", " .
        "`description`=\""  . $connexion->real_escape_string(inspirate($public_page["class"])) . "\", " .

        "`specie`=\"" . $connexion->real_escape_string($public_page["specie"]) . "\", " .
        "`class`=\""  . $connexion->real_escape_string($public_page["class"])  . "\", " .
        "`title`=\""  . $connexion->real_escape_string($public_page["title"])  . "\" " .


        " WHERE `id`=" . $bot["id"] . " ;"
    );

    return $bot;
}

?>