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
    !isset($_POST["report_post"]) || !isset($_SESSION["report_post"]) ||
    ($_POST["report_post"]  !=        $_SESSION["report_post"])

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


$check_report = $connexion->query(
    "SELECT * FROM reports WHERE message_id=\"" . $connexion->real_escape_string($_POST["post_id"]) . "\" AND user_id=\"" . $connexion->real_escape_string($_SESSION["id"]) . "\";"
);

$post = $connexion->query(
    "SELECT * FROM posts WHERE id=\"" . $connexion->real_escape_string($_POST["post_id"]) . "\";"
)->fetch_assoc();


if($check_report->num_rows == 0){

$connexion->query(
    "UPDATE posts set reportnum =\"". $connexion->real_escape_string($post["reportnum"]+1) . "\",  last_report =\"". $connexion->real_escape_string(time()) . "\"WHERE id=\"" . $connexion->real_escape_string($_POST["post_id"]) . "\";"
);

$post = $connexion->query(
    "SELECT * FROM posts WHERE id=\"" . $connexion->real_escape_string($_POST["post_id"]) . "\";"
)->fetch_assoc();

if($post["reportnum"] >= 1){
    $connexion->query(
        "UPDATE posts set reported =\"". $connexion->real_escape_string(1) . "\" WHERE id=\"" . $connexion->real_escape_string($_POST["post_id"]) . "\";"
    );
}
$connexion->query(
    "INSERT INTO `reports` (`message_id`,`user_id`) VALUES (\"" . $connexion->real_escape_string($post["id"]) . "\", \"" . $connexion->real_escape_string($_SESSION["id"]) . "\");"
);
    $report= true;
}else{
    $connexion->query(
        "UPDATE posts set reportnum =\"". $connexion->real_escape_string($post["reportnum"]-1) . "\" WHERE id=\"" . $connexion->real_escape_string($_POST["post_id"]) . "\";"
    );
    $post = $connexion->query(
        "SELECT * FROM posts WHERE id=\"" . $connexion->real_escape_string($_POST["post_id"]) . "\";"
    )->fetch_assoc();

    if($post["reportnum"] <=0){
        $connexion->query(
            "UPDATE posts set reported =\"". $connexion->real_escape_string(0) . "\" WHERE id=\"" . $connexion->real_escape_string($_POST["post_id"]) . "\";"
        );
    }
    $connexion->query(
        "DELETE FROM reports WHERE (message_id=\"".$post["id"]."\") AND (user_id=\"".$_SESSION["id"]."\");"
    );
    $report= false;
}


////////////////////////////////////////////////////////////////////

echo json_encode([
    "success" => true,
    "report" => $report
]);

mysqli_close($connexion);
exit();
?>