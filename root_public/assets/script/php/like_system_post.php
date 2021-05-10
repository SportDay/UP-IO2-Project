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
    !isset($_POST["like_post"]) || !isset($_SESSION["like_post"]) ||
    ($_POST["like_post"]  !=        $_SESSION["like_post"])

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



$check_like = $connexion->query(
    "SELECT * FROM likes WHERE message_id=\"" . $connexion->real_escape_string($_POST["post_id"]) . "\" AND user_id=\"" . $connexion->real_escape_string($_SESSION["id"]) . "\";"
);

$post = $connexion->query(
    "SELECT * FROM posts WHERE id=\"" . $connexion->real_escape_string($_POST["post_id"]) . "\";"
)->fetch_assoc();


if($check_like->num_rows == 0){
    $connexion->query(
        "UPDATE posts set like_num =\"". $connexion->real_escape_string($post["like_num"]+1) . "\" WHERE id=\"" . $connexion->real_escape_string($_POST["post_id"]) . "\";"
    );

    $connexion->query(
        "INSERT INTO `likes` (`message_id`,`user_id`) VALUES (\"" . $connexion->real_escape_string($post["id"]) . "\", \"" . $connexion->real_escape_string($_SESSION["id"]) . "\");"
    );
    $liked = true;
}else{
    $connexion->query(
        "UPDATE posts set like_num =\"". $connexion->real_escape_string($post["like_num"]-1) . "\" WHERE id=\"" . $connexion->real_escape_string($_POST["post_id"]) . "\";"
    );

    $connexion->query(
        "DELETE FROM likes WHERE (message_id=\"".$post["id"]."\") AND (user_id=\"".$_SESSION["id"]."\");"

    );
    $liked = false;
}
////////////////////////////////////////////////////////////////////

$post = $connexion->query(
    "SELECT * FROM posts WHERE id=\"" . $connexion->real_escape_string($_POST["post_id"]) . "\";"
)->fetch_assoc();



////////////////////////////////////////////////////////////////////

echo json_encode([
    "success" => true,
    "nbr_like" => $post["like_num"],
    "liked" => $liked
]);

mysqli_close($connexion);
exit();
?>