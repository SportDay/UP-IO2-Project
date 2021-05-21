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
        if (isset($debug)) { ?>
            <div class="mid_content">
            <?php write($debug); ?>
            </div>
        <?php }

        require($GLOBALS["global_params"]["root"] . "assets/script/php/footer.php");
        exit();
    }

    $friend  = isset($_GET["user"])    ? $_GET["user"]    : invalidPage("Requête invalide.");
    $private = isset($_GET["private"]) ? $_GET["private"] == "true" : invalidPage("Requête invalide.");

    $connexion = mysqli_connect (
        $GLOBALS["DB_URL"],
        $GLOBALS["DB_ACCOUNT"],
        $GLOBALS["DB_PASSWORD"],
        $GLOBALS["DB_NAME"]
    );

    if (!$connexion) invalidPage("connection_error");

    //////////////////////////////
    // CONTENU MODULABLE DE PAGE
    function pageContent($friend, $private) {
        
        $root_public = $GLOBALS["global_params"]["root_public"];
        $root        = $GLOBALS["global_params"]["root"];

        ?>
            <div id="mid_container_mid">
            <div class="mid_content container_message">
                
                <!-- Description -->
                <div class="pofile_container_dm dm_text_setting">
                    <?php if($friend["enable_public"]) { ?>
                    <a href="<?=$root_public?>page/public/public_page.php?user=<?=htmlentities($friend["public_name"])?>">
                        <img class="profile_img_posts" src="<?= getImagePath( $friend["enable_public"] ? $friend["public_image"] : "none", true, $root_public)  ?>">
                    </a>
                    <?php } else { ?>
                        <img class="profile_img_posts" src="<?= getImagePath( $friend["enable_public"] ? $friend["public_image"] : "none", true, $root_public)  ?>">
                    <?php } ?>

                    <div class="border">
                        <span class="post_auteur"><?= $private ? $friend["username"] : $friend["public_name"] ?></span><br>
                    </div>
                
                </div>
                
                <!-- Messages -->
                <div class="all_message_container border" id="all_message_container">

                </div>

                <!-- Send Messages -->
                <div class="send_container">

                    <textarea id="msg_send_content" placeholder="Votre Message" rows="3"></textarea>
                    <button class="btn_send btn_button_btn" onclick='sendMessage()'>
                        <img height="32" width="32" src="<?= $root_public ?>assets/image/send.png">
                    </button>

                </div>

                <!-- Scripts -->
                <script>

                    // scroll en bas de la bar des messages par défaut
                    var messagesArea = document.getElementById("all_message_container");

                    //persistents data
                    const friend        = <?= json_encode($private ? $friend["username"] : $friend["public_name"]) ?>;
                    const private       = <?= $private ? "true" :  "false" ?>;
                    var   lastUpdate    = 0;

                    // auto refresh (5s)
                    // j'ai verifié, js est single threaded, 
                    // donc pas de problème d'effets de bords à refresh 2x en même temps
                    window.setInterval( refreshMessages, 5000 );
                    refreshMessages(); // first iter

                    //////////////////
                    // fonctions 
                    
                    function sendMessage() {
                        let sender = document.getElementById("msg_send_content");

                        let data = new FormData();
                        data.append("token_id",     token_id);
                        data.append("private",      private);
                        data.append("friend",       friend);
                        data.append("message",      sender.value);

                        let xmlhttp = new XMLHttpRequest();
                        xmlhttp.open('POST',
                        "<?php echo $GLOBALS["global_params"]["root_public"] ?>assets/script/php/direct_message_send.php");
                        xmlhttp.send( data );

                        xmlhttp.onreadystatechange = function () {
                            if (xmlhttp.readyState === 4) // request done
                                if (xmlhttp.status === 200) // successful return
                                {
                                    //alert(xmlhttp.responseText);
                                    const feedback = JSON.parse(xmlhttp.responseText);
                                    
                                    if (feedback["success"])
                                    {
                                        refreshMessages();
                                        sender.value = "";
                                        sender.focus();

                                    }
                                    else
                                    {
                                        if (feedback["error"] == "token_error")
                                        {
                                            window.open(window.location.href, "_self");
                                        }
                                    }
                                }
                        }
                    }

                    //var cnt = 0;
                    function refreshMessages() {
                        //cnt++; if (cnt>5) return;
                        
                        let data = new FormData();
                        data.append("token_id", token_id);
                        data.append("private",      private);
                        data.append("friend",       friend);
                        data.append("last",         lastUpdate);

                        let xmlhttp = new XMLHttpRequest();
                        xmlhttp.open('POST',
                        "<?php echo $GLOBALS["global_params"]["root_public"] ?>assets/script/php/direct_message_refresh.php");
                        xmlhttp.send( data );

                        xmlhttp.onreadystatechange = function () {
                            if (xmlhttp.readyState === 4) // request done
                                if (xmlhttp.status === 200) // successful return
                                {
                                    //alert(xmlhttp.responseText);
                                    const feedback = JSON.parse(xmlhttp.responseText);
                                    
                                    if (feedback["success"])
                                    {
                                        lastUpdate = feedback["last"];
                                        messagesArea.innerHTML = messagesArea.innerHTML + feedback["html"];
                                    }

                                    if (feedback["error"] == "token_error")
                                    {
                                        window.open(window.location.href, "_self");
                                    }
                                }
                            
                            messagesArea.scrollTop = messagesArea.scrollHeight;
                        }
                    }

                </script>

            </div>
            </div>

        <?php
    }

?>
<!-- ------------------------------------------ -->
</br>

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
        pageContent( $friend, true );

    } 
    else { // check public  friend
        // transformer friend en id

        $friend = $connexion->query(
            "SELECT * FROM users WHERE public_name=\"" . $connexion->real_escape_string($friend) . "\";"
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


        // afficher la page
        pageContent( $friend, false );
    }

?>
<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/footer.php"); ?>