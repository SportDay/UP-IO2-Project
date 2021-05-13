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
    !isset($_POST["update_desc"]) || !isset($_SESSION["update_desc"]) ||
    ($_POST["update_desc"]  !=        $_SESSION["update_desc"])

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

if (!isset($_POST["user_id"]) || !isset($_POST["new_desc"])) {
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

$old_desc = $connexion->query("SELECT description FROM users WHERE id=\"" . $connexion->real_escape_string($_POST["user_id"]) . "\";")->fetch_assoc();

if(($old_desc["description"] === $_POST["new_desc"]) || $_POST["new_desc"] === "" || strlen($_POST["new_desc"]) > 50){
    echo json_encode([
        "success" => false,
        "error"   => "Requête incorrecte."
    ]); exit();
}


////////////////////////////////////////////////////////////////////

$connexion->query(
    "UPDATE users set description = \"" . $connexion->real_escape_string($_POST["new_desc"]) . "\" WHERE id=\"" . $connexion->real_escape_string($_POST["user_id"]) . "\";"
);


////////////////////////////////////////////////////////////////////

echo json_encode([
    "success" => true,
    "test" => $old_desc
]);

mysqli_close($connexion);
exit();
?>