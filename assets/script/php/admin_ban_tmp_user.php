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
    !isset($_POST["bantemp"]) || !isset($_SESSION["bantemp"]) ||
    ($_POST["bantemp"]  !=        $_SESSION["bantemp"])

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

if (!isset($_POST["type"]) || !isset($_POST["user_id"])) {
    echo json_encode([
        "success" => false,
        "error"   => "Requête incorrecte."
    ]); exit();
}

if (!isset($_POST["time"])) {
    echo json_encode([
        "success" => false,
        "error"   => "Le champs ne peut etre vide."
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
if($_POST["type"] === "min"){
    $ban_to = $_POST["time"]*60;
}
if($_POST["type"] === "hour"){
    $ban_to = $_POST["time"]*3600;
}
if($_POST["type"] === "day"){
    $ban_to = $_POST["time"]*86400;
}
if($_POST["type"] === "month"){
    $ban_to = $_POST["time"]*2628000;
}

removePublicPage(false, $_POST["user_id"]);

$connexion->query(
    "UPDATE users set banned_to =\"". $connexion->real_escape_string(time()+$ban_to) . "\" WHERE id=\"" . $connexion->real_escape_string($_POST["user_id"]) . "\";"
);

////////////////////////////////////////////////////////////////////

echo json_encode([
    "success" => true
]);

mysqli_close($connexion);
exit();
?>