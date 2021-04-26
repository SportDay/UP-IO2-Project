      <?php $global_params = [
  "root"        => "../../../",
  "root_public" => "../../",
  "title"       => "Amis",
  "css"         => "all.css",
  "css_add"     => ["posts.css", "public_page.css","admin.css","friends.css"],
  "redirect"    => TRUE
];?>
<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/functions.php"  ); ?>
<?php require($global_params["root"] . "assets/script/php/header.php"); ?>
<!-- ------------------------------------------ -->
<?php // FUNCTIONS (specific à cette page)

    

?>
<!-- ------------------------------------------ -->

    <div style="text-align: center; margin-bottom: 1em;">
        <div id="search_container">
            <form action="/search_ami.php" method="get">
                <input id="search_input" type="search" autocomplete="off" placeholder="Recherche">
            </form>
        </div>
    </div>

    <div id="mid_content">

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

        $friends = $connexion->query( // enfin ça fonctionne !!
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

        while($friend=$friends->fetch_assoc())
            friend_bloc($friend);

        mysqli_close($connexion);
    ?>
    
    </div>

<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/footer.php"); ?>