<?php $global_params = [
  "root"        => "../../../",
  "root_public" => "../../",
  "title"       => "Coeur de poudlard",
  "css"         => "all.css",
  "css_add"     => ["posts.css","search.css"],
  "redirect"    => TRUE
];?>
<!-- ------------------------------------------ -->
<?php require_once($global_params["root"] . "assets/script/php/functions.php"  ); ?>
<?php require_once($global_params["root"] . "assets/script/php/header.php"); ?>
<!-- ------------------------------------------ -->
<?php
    
    search_bar();

    //////////////////////////////////////////////////////////
    // connexion sql
    $connexion = mysqli_connect (
        $db_conf["DB_URL"],
        $db_conf["DB_ACCOUNT"],
        $db_conf["DB_PASSWORD"],
        $db_conf["DB_NAME"]
    );
    if (!$connexion) {
        echo "connection_error"; exit();
    }

    
    //////////////////
    // ajout des posts

    $posts = $connexion->query(
        "select * from ( ".
        "          (SELECT like_id as poster FROM `pages_liked` WHERE (user_id=".$connexion->real_escape_string($_SESSION["id"])."))".
        " UNION (SELECT user_id_1 as poster FROM `friends` WHERE (user_id_0=".$connexion->real_escape_string($_SESSION["id"])." AND accepted))".
        " UNION (SELECT user_id_0 as poster FROM `friends` WHERE (user_id_1=".$connexion->real_escape_string($_SESSION["id"])." AND accepted))".
        " UNION (SELECT user_id as poster FROM `posts` WHERE (user_id=".$connexion->real_escape_string($_SESSION["id"]).")) )".
        " as t1 inner join posts on (t1.poster=posts.user_id)".
        " ORDER BY creation_date DESC;"
    );

    post_add();

    if ($posts->num_rows==0)
    { ?>
        <div class="mid_content">
            <p>Aucun post.</p>
        </div>
    <?php }
    
    while($post=$posts->fetch_assoc())
        post_bloc($post);

    mysqli_close($connexion);
    
    /////////////////////////////
    // fonctions en javascript

    if(isset($_SESSION["id"])){
        post_js_bloc();
        post_js_add();
    }
?>

<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/footer.php"); ?>