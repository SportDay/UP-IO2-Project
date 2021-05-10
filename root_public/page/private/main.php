<?php $global_params = [
  "root"        => "../../../",
  "root_public" => "../../",
  "title"       => "Coeur de poudlard",
  "css"         => "all.css",
  "css_add"     => ["posts.css","search.css"],
  "redirect"    => TRUE
];?>
<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/functions.php"  ); ?>
<?php require($global_params["root"] . "assets/script/php/header.php"); ?>
<!-- ------------------------------------------ -->
<?php // FUNCTIONS (specific à cette page)
search_bar();
?>
<!-- ------------------------------------------ -->







<?php // FUNCTIONS (specific à cette page)
$connexion = mysqli_connect (
    $db_conf["DB_URL"],
    $db_conf["DB_ACCOUNT"],
    $db_conf["DB_PASSWORD"],
    $db_conf["DB_NAME"]
);

if (!$connexion) {
    echo "connection_error"; exit();
}

if(!isset($_SESSION["id"])){
    ?>
    <script>window.location.href = "<?="home_page.php"?>";</script>
    <?php
}


$my_posts = $connexion->query("select * from ( ".
    "          (SELECT like_id as poster FROM `pages_liked` WHERE (user_id=".$connexion->real_escape_string($_SESSION["id"])."))".
    " UNION (SELECT user_id_1 as poster FROM `friends` WHERE (user_id_0=".$connexion->real_escape_string($_SESSION["id"])." AND accepted))".
    " UNION (SELECT user_id_0 as poster FROM `friends` WHERE (user_id_1=".$connexion->real_escape_string($_SESSION["id"])." AND accepted))".
    " UNION (SELECT user_id as poster FROM `posts` WHERE (user_id=".$connexion->real_escape_string($_SESSION["id"]).")) )".
    " as t1 inner join posts on (t1.poster=posts.user_id)".
    " ORDER BY creation_date DESC;");

post_add();

    if ($my_posts->num_rows==0)
    { ?>
        <div class="mid_content">
            <p>C'est vide par ici.</p>
        </div>
    <?php }
while($my_post=$my_posts->fetch_assoc()) {

    $like_query = 
        "SELECT id FROM likes WHERE ".
        " user_id=\"" . $connexion->real_escape_string($_SESSION["id"]) . 
        "\" AND message_id=\"" . $connexion->real_escape_string($my_post["id"]) . "\";";
    $like = $connexion->query($like_query)->num_rows != 0;

    $report_query = 
        "SELECT id FROM reports WHERE ".
        " user_id=\"" . $connexion->real_escape_string($_SESSION["id"]) . 
        "\" AND message_id=\"" . $connexion->real_escape_string($my_post["id"]) . "\";";
    $reported = $connexion->query($report_query)->num_rows != 0;

    post_bloc($my_post, $like, $reported, true);
}

mysqli_close($connexion);
if(isset($_SESSION["id"])){
    post_js_bloc();
    post_js_add();
}
?>

    <?php

        // APPELLER LE SERVEUR

        // CHERCHER TOUT LES PROFILES ABBONNEE (+ MON PROFILE)

        // CHERCHER TOUT LES MESSAGES DE PROFILE CIS DESSUS

        // TRIER CES MESSAGES PAR DATE
        
        // FOR EACH MESSAGE => call ($message_id)

    ?>

<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/footer.php"); ?>