<?php $global_params = [
    "root"        => "../../../",
    "root_public" => "../../",
    "title"       => "Coeur de poudlard",
    "css"         => "all.css",
    "css_add"     => ["public_page.css", "search.css"],
    "redirect"    => TRUE
];?>
    <!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/functions.php"  ); ?>
<?php require($global_params["root"] . "assets/script/php/header.php"); ?>
    <!-- ------------------------------------------ -->
<?php // FUNCTIONS (specific à cette page)
search_bar();

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

if(!isset($_GET["search"])){
    ?>
    <div class="mid_content">
        <p>Un problème est survenue.</p>
    </div>
<?php
    exit();
}

/*$search_profiles = $connexion->query("SELECT * FROM `users` WHERE (( public_name LIKE \"%".$connexion->real_escape_string($_GET["search"])."%\" ) OR ( description LIKE \"%".$connexion->real_escape_string($_GET["search"])."%\" ))".
    "  EXCEPT (SELECT like_id FROM `pages_liked` WHERE (user_id=".$connexion->real_escape_string($_SESSION["id"])."))".
    "  EXCEPT (SELECT user_id_1 FROM `friends` WHERE (user_id_0=".$connexion->real_escape_string($_SESSION["id"])." AND accepted))".
    "  EXCEPT (SELECT user_id_0 FROM `friends` WHERE (user_id_1=".$connexion->real_escape_string($_SESSION["id"])." AND accepted)) ".
    " ORDER BY likes DESC;");*/


$search_profiles = $connexion->query("select * from ( ".
    "          (SELECT id as poster FROM `users` WHERE (( public_name LIKE \"%".$connexion->real_escape_string($_GET["search"])."%\" ) OR ( description LIKE \"%".$connexion->real_escape_string($_GET["search"])."%\" )))".
    " EXCEPT (SELECT user_id_1 as poster FROM `friends` WHERE (user_id_0=".$connexion->real_escape_string($_SESSION["id"])." AND accepted))".
    " EXCEPT (SELECT user_id_0 as poster FROM `friends` WHERE (user_id_1=".$connexion->real_escape_string($_SESSION["id"])." AND accepted))".
    " EXCEPT (SELECT id as poster FROM `users` WHERE (id=".$connexion->real_escape_string($_SESSION["id"])."))".
    " EXCEPT (SELECT user_id as poster FROM `pages_liked` WHERE (user_id=".$connexion->real_escape_string($_SESSION["id"]).")) )".
    " as t1 inner join users on (t1.poster=users.id)".
    " ORDER BY likes DESC;");

//$search_profiles = $connexion->query("SELECT * FROM users WHERE (( public_name LIKE \"%".$connexion->real_escape_string($_GET["search"])."%\" ) OR ( description LIKE \"%".$connexion->real_escape_string($_GET["search"])."%\" )) ORDER BY likes DESC LIMIT 30;");

if ($search_profiles->num_rows==0)
{ ?>
    <div class="mid_content">
        <p>Je suis désolé mais, je n'ai rien pu trouver</p>
    </div>
<?php }

while($search_profile=$search_profiles->fetch_assoc()) {
    search_profil($search_profile);
}

mysqli_close($connexion);

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