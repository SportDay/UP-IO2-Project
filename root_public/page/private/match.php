<?php $global_params = [
  "root"        => "../../../",
  "root_public" => "../../",
  "title"       => "Vos rencontres",
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
            "
            select *
            from
            (
                SELECT user_id FROM pages_liked WHERE like_id=".$_SESSION["id"]." AND user_id IN (
                    SELECT like_id FROM pages_liked WHERE (user_id=".$_SESSION["id"].")
                )
            ) as t1
            inner join users
            on t1.user_id=users.id
            
            ORDER BY last_join DESC
            "
        );

        if ($matchs->num_rows==0)
        { ?>
            <div class="mid_content">
                <p>Aucune rencontre.</p>
            </div>
        <?php }

        while($match=$matchs->fetch_assoc())
        {
            match_bloc($match); // remplacer par match bloc
        }

        mysqli_close($connexion);
    ?>
    </div>

<?php match_js_bloc(); ?>

<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/footer.php"); ?>