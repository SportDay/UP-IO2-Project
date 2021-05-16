<?php

// ATTENTION
// LE FICHIER QUI ACTIONNE CELUI CI SE TROUVE DANS : root_public/assets/script/php/
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
    !isset($_POST["bandef"]) || !isset($_SESSION["bandef"]) &&
          ($_POST["bandef"]  !=        $_SESSION["bandef"])
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

$id = $connexion->query("SELECT id FROM users WHERE id=\"".$connexion->real_escape_string($_POST["user_id"])."\"");

if ($id->num_rows == 0) {
    echo json_encode([
        "success" => false,
        "error"   => "Utilisateur incorrect."
    ]); exit();
}

removeAccount(false, $id->fetch_assoc()["id"]);

////////////////////////////////////////////////////////////////////

echo json_encode([
    "success" => true
]);

mysqli_close($connexion);
exit();
?>