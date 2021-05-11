<?php $global_params = [
  "root"        => "../../../",
  "root_public" => "../../",
  "title"       => "Matchs",
  "css"         => "all.css",
  "css_add"     => [
      "posts.css", "public_page.css","admin.css",
      "friends.css","login.css"
    ],
  "redirect"    => TRUE
];?>
<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/functions.php"  ); ?>
<?php require($global_params["root"] . "assets/script/php/header.php"); ?>
<!-- ------------------------------------------ -->

    <div class="mid_content"><p>Work in progress</p></div>

    <!-- Liste de matchs -->

    <div id="friend_blocs_area" >
    <?php
        $connexion = mysqli_connect (
            $GLOBALS["DB_URL"],
            $GLOBALS["DB_ACCOUNT"],
            $GLOBALS["DB_PASSWORD"],
            $GLOBALS["DB_NAME"]
        );
    
        if (!$connexion) { 
            echo "connection_error"; exit(); 
        }

        $matchs = $connexion->query( 
            "select *
            from
            (
                (SELECT user_id_1 as friend FROM `friends` WHERE (user_id_0=".$_SESSION["id"]." AND accepted))
                    UNION 
                (SELECT user_id_0 as friend FROM `friends` WHERE (user_id_1=".$_SESSION["id"]." AND accepted))
            ) as t1
            inner join users
            on t1.friend=users.id
            
            ORDER BY last_join DESC
            "
        );

        if ($matchs->num_rows==0)
        { ?>
            <div class="mid_content">
                <p>Aucun match.</p>
            </div>
        <?php }

        while($match=$matchs->fetch_assoc())
            friend_bloc($match);

        mysqli_close($connexion);
    ?>
    </div>

<?php friend_js_bloc(); add_friend_js_bloc(); ?>

<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/footer.php"); ?>