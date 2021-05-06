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
    !isset($_POST["post"]) || !isset($_SESSION["post"]) ||
    ($_POST["post"]  !=        $_SESSION["post"])

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

if ((!isset($_POST["post_content"])) || $_POST["post_content"] === "" || strlen($_POST["post_content"]) >= 735) {
    echo json_encode([
        "success" => false,
        "error"   => "Requête incorrecte."
    ]); exit();
}

if(!isset($_SESSION["connected"])){
    exit();
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

if(isset($_SESSION["banned_to"]) && $_SESSION["banned_to"] > time()){
    echo json_encode([
        "success" => false,
        "error"   => "Vous etes bannis."
    ]); exit();
}

$connexion->query(
    "INSERT INTO `posts` (`user_id`, `public_image`, `public_name`, `creation_date`, `content`) VALUES " .
    "(\"" . $connexion->real_escape_string($_SESSION["id"]) . "\", \"" . $connexion->real_escape_string($_SESSION["public_image"]) . "\", \"" . $connexion->real_escape_string($_SESSION["public_name"]) . "\", \"" . $connexion->real_escape_string(time()) . "\", \"" . $connexion->real_escape_string($_POST["post_content"]) . "\");"
);


////////////////////////////////////////////////////////////////////

echo json_encode([
    "success" => true
]);

mysqli_close($connexion);
exit();
?>