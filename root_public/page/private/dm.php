<?php $global_params = [
  "root"        => "../../../",
  "root_public" => "../../",
  "title"       => "Messages Directs",
  "css"         => "all.css",
  "css_add"     => ["posts.css", "public_page.css", "dm.css"],
  "redirect"    => TRUE
];?>
<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/functions.php"  ); ?>
<?php require($global_params["root"] . "assets/script/php/header.php"); ?>
<!-- ------------------------------------------ -->
<?php 
    
    function invalidPage($debug) {
        if (isset($debug)) {
            write($debug);
        }

        require($GLOBALS["global_params"]["root"] . "assets/script/php/footer.php");
        exit();
    }

    $friend  = isset($_GET["user"])    ? $_GET["user"]    : invalidPage("Requête invalide.");
    $private = isset($_GET["private"]) ? $_GET["private"] : invalidPage("Requête invalide.");

    $connexion = mysqli_connect (
        $GLOBALS["DB_URL"],
        $GLOBALS["DB_ACCOUNT"],
        $GLOBALS["DB_PASSWORD"],
        $GLOBALS["DB_NAME"]
    );

    if (!$connexion) invalidPage("connection_error"); 

    //////////////////////////////
    // CONTENU MODULABLE DE PAGE
    function pageContent($friend, $load_script, $post_script, $private) {
        
        $root_public = $GLOBALS["global_params"]["root_public"];
        $root        = $GLOBALS["global_params"]["root"];

        ?>
            <div id="mid_container_mid">
            <div class="mid_content container_message" style="margin-top: 0; margin-bottom: 20px; text-align: initial; height: 100%;">
                
                <!-- Description -->
                <div class="pofile_container_dm">
                    <?php if($friend["enable_public"]) { ?>
                    <a href="<?=$root_public?>page/public/public_page.php?user=<?=htmlentities($friend["public_name"])?>">
                        <img class="profile_img_posts" src="<?= getImagePath( $friend["enable_public"] ? $friend["public_image"] : "none", true, $root_public)  ?>">
                    </a>
                    <?php } else { ?>
                        <img class="profile_img_posts" src="<?= getImagePath( $friend["enable_public"] ? $friend["public_image"] : "none", true, $root_public)  ?>">
                    <?php } ?>

                    <div class="info_containt border" style="border-radius: 15px; padding: 10px 10px;">
                        <span class="post_auteur" style="color: white; font-size: 32px"><?= $private ? $friend["username"] : $friend["public_name"] ?></span><br>
                    </div>
                
                </div>
                
                <!-- Messages -->
                <div class="all_message_container border" id="all_message_container">
                    <div class="message_container">
                        <p class="date" style="color: white; font-size: 14px">Test Test<br>23:06<br>21/04/2021</p>
                        <p class="message" style="color: white; font-size: 16px">test</p>
                    </div>
                </div>

                <!-- Send Messages -->
                <div class="send_container">
                    <textarea id="msg_send_content" name="message" form="msg_form" placeholder="Votre Message" rows="3"></textarea>
                    <button class="btn_send btn_button_btn" onclick='sendMessage()'>
                        <img height="32" width="32" src="<?= $root_public ?>assets/image/send.png">
                    </button>
                </div>

                <!-- Scripts -->
                <script>
                    // scroll en bas de la bar des messages par défaut
                    let messagesContainer = document.getElementById("all_message_container");
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                </script>

                <?= $load_script ?>
                <?= $post_script ?>

            </div>
            </div>

        <?php
    }

?>
<!-- ------------------------------------------ -->
<?php

    if ($private) { // check private friend   

        // transformer friend en id
        $friend = $connexion->query(
            "SELECT id FROM users WHERE username=\"" . $connexion->real_escape_string($friend) . "\";"
        );

        if ($friend->num_rows == 0) 
            invalidPage("Ami invalide.");
        $friend = $friend->fetch_assoc();

        // verifier si l'id est amis
        $friend = $connexion->query( 
            "select *
            from
            (
                (SELECT user_id_1 as friend FROM `friends` WHERE (user_id_0=".$_SESSION["id"]." AND user_id_1=".$friend["id"]." AND accepted))
                    UNION 
                (SELECT user_id_0 as friend FROM `friends` WHERE (user_id_1=".$_SESSION["id"]." AND user_id_0=".$friend["id"]." AND accepted))
            ) as t1
            inner join users
            on t1.friend=users.id
            "
        );

        if ($friend->num_rows == 0) 
            invalidPage("Ami invalide.");
        $friend = $friend->fetch_assoc();

        // afficher la page
        pageContent(
            $friend,
            "",
            "",
            true
        );

    } 
    else {        // check public  friend
        // transformer friend en id
        $friend = $connexion->query(
            "SELECT id FROM users WHERE public_name=\"" . $connexion->real_escape_string($friend) . "\";"
        );

        if ($friend->num_rows == 0) 
            invalidPage("Compte invalide.");
        $friend = $friend->fetch_assoc();

        // verifier si l'id est amis
        $link = $connexion->query(
            "
                (SELECT id FROM `pages_liked` WHERE (user_id=".$_SESSION["id"]." AND like_id=".$friend["id"]." ))
                    UNION 
                (SELECT id FROM `pages_liked` WHERE (like_id=".$_SESSION["id"]." AND user_id=".$friend["id"]." ))
            "
        );

        if ($link->num_rows != 2) 
            invalidPage("Compte invalide.");

        $friend = $friend->fetch_assoc();

        // afficher la page
        pageContent(
            $friend,
            "",
            "",
            false
        );
    }

?>

<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/footer.php"); ?>