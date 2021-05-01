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
    !isset($_POST["remove_post"]) || !isset($_SESSION["remove_post"]) ||
    ($_POST["remove_post"]  !=        $_SESSION["remove_post"])

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

if (!isset($_POST["post_id"])) {
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

$post = $connexion->query(
    "SELECT * FROM posts WHERE id=\"" . $connexion->real_escape_string($_POST["post_id"]) . "\";"
)->fetch_assoc();

if($post["user_id"] !== $_SESSION["id"]){
    echo json_encode([
        "success" => false,
        "error"   => "Base de donnée hors d'accès."
    ]); exit();
}


$connexion->query(
    "DELETE FROM `reports` WHERE `message_id`=" . $post["id"] . " ;"
);

$connexion->query(
    "DELETE FROM `likes` WHERE `message_id`=" . $post["id"] . " ;"
);

$connexion->query(
    "DELETE FROM posts WHERE (id=\"".$post["id"]."\");"
);

////////////////////////////////////////////////////////////////////

echo json_encode([
    "success" => true
]);

mysqli_close($connexion);
exit();
?>