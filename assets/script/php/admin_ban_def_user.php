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
    !isset($_POST["bandef"]) || !isset($_SESSION["bandef"]) ||
    ($_POST["bandef"]  !=        $_SESSION["bandef"])

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

if (!isset($_POST["user_id"])) {
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
/*
if(isset($_SESSION["admin"]) && $_SESSION["admin"] === true){
    echo json_encode([
        "success" => false,
        "error"   => "Base de donnée hors d'accès."
    ]); exit();
}*/


// pages_liked | parents : | enfants : reports et likes
$connexion->query(
    "DELETE FROM `pages_liked` WHERE `user_id`=" . $connexion->real_escape_string($_POST["user_id"]) . " ;"
);

// posts | parents : | enfants : reports et likes
$connexion->query(
    "DELETE FROM `reports` WHERE `user_id`=" . $connexion->real_escape_string($_POST["user_id"]) . " ;"
);
$connexion->query(
    "DELETE FROM `likes` WHERE `user_id`=" . $connexion->real_escape_string($_POST["user_id"]) . " ;"
);
$connexion->query(
    "DELETE FROM `posts` WHERE `user_id`=" . $connexion->real_escape_string($_POST["user_id"]) . " ;"
);

// supprimer: friends direct_messages
$connexion->query( // pas besoin de verifier que c'est privé
    "DELETE FROM `friends` WHERE `user_id_0`=" . $connexion->real_escape_string($_POST["user_id"]) . " OR `user_id_1`=" . $connexion->real_escape_string($_POST["user_id"]) . " ;"
);
$connexion->query(
    "DELETE FROM `direct_messages` WHERE `from_id`=" . $connexion->real_escape_string($_POST["user_id"]) . " OR `to_id`=" . $connexion->real_escape_string($_POST["user_id"]) . " ;"
);

// supprimer le compte: (users)
$connexion->query(
    "DELETE FROM `users` WHERE `id`=" . $connexion->real_escape_string($_POST["user_id"]) . " ;"
);

////////////////////////////////////////////////////////////////////

echo json_encode([
    "success" => true
]);

mysqli_close($connexion);
exit();
?>