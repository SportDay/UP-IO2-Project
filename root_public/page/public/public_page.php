<?php $global_params = [
  "root"        => "../../../",
  "root_public" => "../../",
  "title"       => "Page Public",
  "css"         => "all.css",
  "css_add"     => ["public_page.css", "posts.css"],
  "redirect"    => FALSE // J'hésite à mettre ça en true
];?>
<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/functions.php"  ); ?>
<?php require($global_params["root"] . "assets/script/php/header.php"); ?>
<!-- ------------------------------------------ -->

<!-- ------------------------------------------ -->
    <!-- Faire des focntions qui verifie si la page a partient a l'utilisateur -->
    <div style="text-align: center; margin-bottom: 1em;">
    <div id="search_container">
        <form action="/search.php" method="get">
            <input id="search_input" type="search" autocomplete="off" placeholder="Recherche">
        </form>
    </div>
    </div>
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

    if(!isset($_GET["user"])){
        ?>
            <script>window.location.href = "<?="home_page.php"?>";</script>
        <?php
    }

    $user_query = "SELECT * FROM users WHERE public_name=\"". $connexion->real_escape_string(trim(htmlentities($_GET["user"]))) . "\";";
    $me = $connexion->query($user_query)->fetch_assoc();
    profile_bloc($me);

    $post_query = "SELECT * FROM posts WHERE user_id=\"". $connexion->real_escape_string($_SESSION["id"]) . "\" ORDER BY creation_date DESC;";
    $my_posts = $connexion->query($post_query);
    while($my_post=$my_posts->fetch_assoc()) {
        $like = false;
        $reported = false;
        $like_query = "SELECT * FROM likes WHERE user_id=\"" . $connexion->real_escape_string($my_post["user_id"]) . "\" AND message_id=\"" . $connexion->real_escape_string($my_post["id"]) . "\";";
        if ($connexion->query($like_query)->num_rows != 0) {
            $like = true;
        }

        $report_query = "SELECT * FROM reports WHERE user_id=\"" . $connexion->real_escape_string($my_post["user_id"]) . "\" AND message_id=\"" . $connexion->real_escape_string($my_post["id"]) . "\";";
        if ($connexion->query($report_query)->num_rows != 0) {
            $reported = true;
        }

        post_bloc($my_post, $like, $reported);
    }

    mysqli_close($connexion);

    profile_js_bloc();
    post_js_bloc()
?>


<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/footer.php"); ?>