<?php

$global_params = [
    "root"        => "../../../../",
    "root_public" => "../../../../root_public/",
];

require_once($global_params["root"] . "assets/script/php/constants.php");
require_once($global_params["root"] . "assets/script/php/functions.php");

////////////////////////////////////////////////////////////////////
// ETABLISSEMENT DE LA CONNECTION

session_start();

if (
    !isset($_POST["toggle_like"]) || !isset($_SESSION["toggle_like"]) ||
          ($_POST["toggle_like"]  !=        $_SESSION["toggle_like"])
)
{
    echo json_encode([
        "success" => false,
        "error"   => "token_error"
    ]); exit();
}

if (!isset($_POST["public_name"])) {
    echo json_encode([
        "success" => false,
        "error"   => "Requête incorrecte."
    ]); exit();
}

/// SQL
$connexion = mysqli_connect (
    $db_conf["DB_URL"],
    $db_conf["DB_ACCOUNT"],
    $db_conf["DB_PASSWORD"],
    $db_conf["DB_NAME"]
); 
if (!$connexion) {
    echo json_encode([
        "success" => false,
        "error"   => "Base de donnée hors d'accès."
    ]); exit();
}
////////////////////////////////////////////////////////////////////

$profile    = $connexion->query(
    "SELECT id, likes FROM users WHERE public_name=\"".$connexion->real_escape_string($_POST["public_name"])."\""
);
if ($profile->num_rows==0) {
    echo json_encode([
        "success" => false,
        "error"   => "Page inexistante."
    ]); exit();
} $profile = $profile->fetch_assoc();

// on check est ce que la page a été like
$page_liked = $connexion->query(
    "SELECT id FROM pages_liked WHERE user_id=".$_SESSION["id"]." AND like_id=".$profile["id"] 
    )->num_rows != 0;

if ($page_liked) { // si elle a été like on la dislike
    $connexion->query(
        "DELETE FROM pages_liked WHERE user_id=".$_SESSION["id"]." AND like_id=".$profile["id"]
    );
    $connexion->query(
        "UPDATE users SET likes=(likes-1) WHERE id=".$profile["id"]." ;"
    );

    $profile["likes"]--;

} else { // si elle n'a pas été like on la like
    
    $connexion->query(
        "INSERT INTO pages_liked (user_id, like_id) VALUES (".$_SESSION["id"].", ".$profile["id"].")"
    );
    $connexion->query(
        "UPDATE users SET likes=(likes+1) WHERE id=".$profile["id"]." ;"
    );

    $profile["likes"]++;
}

////////////////////////////////////////////////////////////////////

echo json_encode([
    "success"   => true,
    "isLiked"   => !$page_liked,
    "nLikes"     => $profile["likes"]
]);

mysqli_close($connexion);
exit();
?>