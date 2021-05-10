<?php

/*
    INIT_DATABASE:
    Activez ce fichier ou ouvrez sa page si vous souhaitez reinitialiser la base donnée.
*/


// INTIALISATION DE LA BASE DE DONNEE

if (true) { 
    // PAR DEFAUT ON NE VEUT PAS QUE QUELQU'UN PUISSE LANCER CE FICHIER PAR ERREUR
    exit();
}


$global_params = [
    "root"        => "../",
    "root_public" => "",
];

require($global_params["root"] . "assets/script/php/constants.php");
require($global_params["root"] . "assets/script/php/functions.php");

// CONNEXION BASE DE DONNEE

$connexion = mysqli_connect (
    $db_conf["DB_URL"],
    $db_conf["DB_ACCOUNT"],
    $db_conf["DB_PASSWORD"],
    $db_conf["DB_NAME"]
);

if (!$connexion) { 
    // data base error
    echo "data base error";
    exit(); 
}

// CREATION DES TABLES



// REMPLISSAGE DES TABLES

$nBots = 20; // nombre de bots auto générés
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
foreach($users as $user) {
    addUser();
}

// CREATION DE PAGES PUBLICS
for ($i = 0; $i < $nBots; $i++) {
    addBot();
}

mysqli_close($connexion);

?>

<?php ////////////////////////////////////////////////////////////////////////////////////////////////////////

function addUser() {
    $connexion = $GLOBALS["connexion"];


}

function addBot() {
    $connexion = $GLOBALS["connexion"];

    addUser();

    $public_page = generateRandomPublicData();

    $connexion->query(
        "UPDATE `users` SET " . 
        "`enable_public`=TRUE, " .
        "`public_name`=\""  . $connexion->real_escape_string($public_page["public_name"]) . "\", " .
        "`public_image`=" . $public_page["public_image"] . ", " .
        "`last_reroll`="  . time() . ", " .

        "`specie`=\"" . $connexion->real_escape_string($public_page["specie"]) . "\", " .
        "`class`=\""  . $connexion->real_escape_string($public_page["class"])  . "\", " .
        "`title`=\""  . $connexion->real_escape_string($public_page["title"])  . "\" " .

        " WHERE `id`=" . "id" . " ;" // oublie pas de mettre le bonne id du bot en cours
    );
}

?>