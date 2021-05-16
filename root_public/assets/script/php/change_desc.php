<?php

$global_params = [
    "root"        => "../../../../",
    "root_public" => "../../../../root_public/",
];

require_once($global_params["root"] . "assets/script/php/constants.php");
require_once($global_params["root"] . "assets/script/php/functions.php");
require_once($global_params["root"] . "assets/script/php/security.php");

////////////////////////////////////////////////////////////////////
// ETABLISSEMENT DE LA CONNECTION

session_start();

if (
    !isset($_POST["update_desc"]) || !isset($_SESSION["update_desc"]) ||
          ($_POST["update_desc"]  !=        $_SESSION["update_desc"])
)
{
    echo json_encode([
        "success" => false,
        "error"   => "token_error."
    ]); exit();
}

if (!isset($_POST["new_desc"])) {
    echo json_encode([
        "success" => false,
        "error"   => "Requête incorrecte."
    ]); exit();
}

if( $_POST["new_desc"] === "") {
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

$connexion->query(
    "UPDATE users SET description = \"" . $connexion->real_escape_string($_POST["new_desc"]) . "\" WHERE id=\"" . $_SESSION["id"] . "\";"
);

////////////////////////////////////////////////////////////////////

echo json_encode([
    "success" => true
]);

mysqli_close($connexion);
exit();
?>