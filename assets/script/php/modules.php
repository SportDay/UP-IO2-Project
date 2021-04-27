<?php 

/*

    MODULES:
    Les modules correspondent à un ensemble de fonctions qui générent des élements html procéduraux.
    Ces fonctions produisent donc effets de bords sur les pages ou elles sont appelés.


*/


// MENU
function menu_when_not_connected () {
    // bouton de connection et d'enregistrement

    ?>
        <!-- DECLANCHEURS DE POP UP -->
        
        <div class="login_right_button">
            <button class="login_right_button" onclick="
                        document.getElementById('register').style.display='block';
                        document.getElementById('login')   .style.display='none';
                        " 
                    style="width:auto;"
                    >S'inscrire</button>
            <br>
            <button class="login_right_button" onclick="
                        document.getElementById('login')   .style.display='block';
                        document.getElementById('register').style.display='none';
                        " 
                    style="width:auto;"
                    >Se connecter</button>
        </div>

        <!-- POP UPS -->

        <div id="login" class="reg_log_model">
            <div class="modal-content animate">
            <div class="reg_log_form_container">
                <span onclick="document.getElementById('login').style.display='none'" class="close" title="Fermer">&times;</span>
                <label class="popup_form_title" for="pseudo"><b>Pseudo</b></label>
                <input id="login_name" type="text" placeholder="Pseudo" name="pseudo" >

                <label class="popup_form_title" for="password"><b>Mot de passe</b></label>
                <input id="login_password" type="password" placeholder="Mot de passe" name="password">

                <p id="login_error" class="popup_text" style="display:none"> ERROR </p>
                <button type="submit" onclick="login();">Se connecter</button>
                
                <label class="popup_text">Se souvenir de moi?
                    <input id="login_remember" type="checkbox" checked="checked" name="remember" >
                </label>

                <br>

                <button type="button" onclick="document.getElementById('login').style.display='none'" class="cancelbtn">Annuler</button>
            </div>
            </div>
        </div>

        <div id="register" class="reg_log_model">
            <div class="modal-content animate" class="reg_log_form_container">
            <div class="reg_log_form_container">
                <span onclick="document.getElementById('register').style.display='none'" class="close" title="Fermer">&times;</span>
                <label class="popup_form_title" for="pseudo"><b>Pseudo</b></label>
                <input id="register_name" type="text" placeholder="Pseudo | 2-16 charactères : A-z et 0-9 et tiret et tiret bas" name="pseudo" >

                <label class="popup_form_title" for="password"><b>Mot de passe</b></label>
                <input id="register_password" type="password" placeholder="Mot de passe | 6-26 charactères : A-z et 0-9 et _*+-()[]" name="password">
                
                <p id="register_error" class="popup_text" style="display:none"> ERROR </p>

                <button type="submit" onclick="register();">S'inscrire</button>
                <button type="button" onclick="document.getElementById('register').style.display='none'" class="cancelbtn">Annuler</button>
            </div> 
            </div>
        </div>

        <script>
            window.onclick = function(event) {
                if (event.target == document.getElementById('login')) {
                    document.getElementById('login').style.display = "none";
                }
                if (event.target == document.getElementById('register')) {
                    document.getElementById('register').style.display = "none";
                }
            }

            ////////////////////////

            function login() {
                
                nickname = document.getElementById("login_name");
                password = document.getElementById("login_password");
                remember = document.getElementById("login_remember");
                debug    = document.getElementById("login_error");

                let data = new FormData();
                data.append("username", nickname.value);
                data.append("password", password.value);
                data.append("remember", remember.checked);
                //////////

                let xmlhttp = new XMLHttpRequest();
                
                xmlhttp.open('POST', 
                "<?php echo $GLOBALS["global_params"]["root_public"] ?>assets/script/php/login.php");
                xmlhttp.send( data );

                xmlhttp.onreadystatechange = function () {
                    let DONE = 4; // readyState 4 means the request is done.
                    let OK = 200; // status 200 is a successful return.
                    
                    if (xmlhttp.readyState === DONE)
                        if (xmlhttp.status === OK)
                        {
                            const feedback = JSON.parse(xmlhttp.responseText);

                            if (feedback["success"]) {
                                debug.innerHTML = "Connection réussie.";
                                debug.style.display = "block";
                                redirection();
                            }
                            else {
                                password.value = "";
                                debug.innerHTML = feedback["error"];
                                debug.style.display = "block";
                            }
                        }
                        else
                        {
                            debug.innerHTML = "Erreur de connection serveur: " + xmlhttp.status;
                            debug.style.display = "block";
                        }
                }
            }

            function register() {
                nickname = document.getElementById("register_name");
                password = document.getElementById("register_password");
                debug    = document.getElementById("register_error");

                let data = new FormData();
                data.append("username", nickname.value);
                data.append("password", password.value);
                //////////

                let xmlhttp = new XMLHttpRequest();
                
                xmlhttp.open('POST', 
                "<?php echo $GLOBALS["global_params"]["root_public"] ?>assets/script/php/signup.php");
                xmlhttp.send( data );

                xmlhttp.onreadystatechange = function () {
                    let DONE = 4; // readyState 4 means the request is done.
                    let OK = 200; // status 200 is a successful return.

                    if (xmlhttp.readyState === DONE)
                        if (xmlhttp.status === OK)
                        {
                            const feedback = JSON.parse(xmlhttp.responseText);

                            if (feedback["success"]) {
                                debug.innerHTML = "Création de compte réussie.";
                                debug.style.display = "block";
                                redirection();
                            }
                            else {
                                password.value = "";
                                debug   .innerHTML = feedback["error"];
                                debug.style.display = "block";
                            }
                        }
                        else
                        {
                            debug.innerHTML = "Erreur de connection serveur: " + xmlhttp.status;
                            debug.style.display = "block";
                        }
                }
            }

            ////////////////////////
            // ouvrir la petite fenetre de connection
            <?php if (isset($_GET["to_connect"])){
                ?>document.getElementById('login').style.display='block';<?php
            } ?>

        </script>
    <?php
}

function menu_when_connected () {
    // SOMMAIRE des choses à faire
    // 
    // main_page | my_profile | params | likes | match | disconnect

    ?> 
        <div id="menu" class="menu_close">
            <div class="menu_contain_button">
                <input
                    id="open_menu" type="image" 
                    src="<?= getImagePath( $_SESSION["enable_public"] ? $_SESSION["public_image"] : "none")  ?>" width="60"
                    name ="menu" alt  ="menu" onclick="toggleMenu();"
                >
            </div>
            <br>
            <div class="menu_contain" id="menu_contain" style="display:none;">
                
                <div id=is_public_menu>
                <p>- publique -</p>

                <button class="menu_button" id="btn_home"       onclick="openPage('private/main.php')"
                        >Mon Fil</button> <br>
                <button class="menu_button" id="btn_profile"    onclick="openProfile();"
                        >Mon Profile</button> <br>
                <button class="menu_button" id="btn_likes"      onclick="openPage('private/like.php');"
                        >Nouvelles Rencontres</button> <br>
                <button class="menu_button" id="btn_matchs"     onclick="openPage('private/match.php');"
                        >Mes Rencontres</button> <br>

                <p>- privé -</p>
                </div>

                <button class="menu_button" id="btn_friends"   onclick="openPage('private/friends.php');"
                        >Amis</button> <br>
                <div id=btn_admin_div>
                <button class="menu_button" id="btn_admin"     onclick="openPage('admin/admin.php');"
                        >Admin</button> <br> </div>

                <button class="menu_button" id="btn_params"     onclick="openPage('private/params.php');"
                        >Paramètres</button> <br>
                <button class="menu_button" id="btn_disconnect" onclick="disconnect();"
                        >Deconnection</button> <br>

            </div>
        </div>

        <script>
            <?php if (!$_SESSION["enable_public"]) { ?>
                document.getElementById('is_public_menu').style.display='none';
            <?php } ?>
            <?php if (!$_SESSION["admin"]) { ?>
                document.getElementById('btn_admin_div').style.display='none';
            <?php } ?>

            /////////

            function toggleMenu() {
                let menu      = document.getElementById('menu');
                let open_menu = document.getElementById('menu_contain');

                if (open_menu.style.display=='block') {
                    open_menu.style.display='none';
                    menu     .className = "menu_close";
                } else { 
                    open_menu.style.display='block';
                    menu     .className = "menu_open";
                }
            }

            function openProfile(profile_name="<?= htmlentities( $_SESSION["public_name"] ) ?>") {
                openPage('public/public_page.php?user=' + profile_name);
            }

            function disconnect() {
                let data = new FormData();
                let xmlhttp = new XMLHttpRequest();
                
                xmlhttp.open('POST', 
                "<?php echo $GLOBALS["global_params"]["root_public"]?>assets/script/php/disconnect.php");
                xmlhttp.send( data );

                xmlhttp.onreadystatechange = function () {
                    let DONE = 4; // readyState 4 means the request is done.
                    let OK = 200; // status 200 is a successful return.

                    if (xmlhttp.readyState === DONE)
                        if (xmlhttp.status === OK)
                        {
                            const feedback = JSON.parse(xmlhttp.responseText);

                            if (feedback["success"]) {
                                if (<?php echo $GLOBALS["global_params"]["redirect"] ? "true" : "false" ; ?>)
                                    openPage('public/home_page.php?to_connect&q=' + encodeURIComponent(window.location.href)); // SI PAGE PRIVE
                                else
                                    window.open(window.location.href, "_self"); // SI PAGE PUBLIC
                            }
                        }
                }
            }
        </script>

    <?php
}

// BARRE DE RECHERCHE
function search($query) { // WIP
    return [ 1, 2, 3, 4 ]; // retourner une liste d'id de message
}

// MESSAGE
function public_message($message) { // WIP

    ?>
        <div id = "mid_content" style="margin-top: 0px; text-align: initial;">
        <div class="posts">
            <a href="/UP-IO2-Project/root_public/page/public/public_page.php?id=">
                <img class="profile_img_posts" src="<?= $global_params["root"] . "assets/profile/default.png" ?>">
            </a>
            <div class="info_containt border" style="border-radius: 15px; padding: 10px 10px;">
                <a href="/UP-IO2-Project/root_public/page/public/public_page.php?id=">
                    <span class="post_auteur" style="color: white; font-size: 20px">Test Test</span><br>
                    <span class="post_date" style="color: lightgray; font-size: 14px">19/04/2021 19:24</span>
                </a>
                <div class="post_menu">
                    <button class="btn_menu_post">&#8226;&#8226;&#8226;</button>
                    <div class="supp_post border">
                        <form action="/supp_post.php" method="post">
                            <input type="hidden" name="sup_post" value="post_id">
                            <button class="btn_sup_post" type="submit">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="post_content border">
                <p style="color: white; font-size: 18px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi aliquet fermentum odio. Nulla sed venenatis nulla. Pellentesque interdum ligula ac venenatis mattis. Nam nec lectus urna. Vestibulum finibus tellus a auctor feugiat. Morbi vel cursus orci, eu efficitur nisl. Vivamus congue mi sed metus condimentum aliquet. Aliquam tempus ante vel viverra vulputate. Phasellus eros lorem, imperdiet in ante vel, malesuada viverra orci. Curabitur laoreet porta quam nec rhoncus. Donec aliquet dui in rhoncus eleifend.

                    Donec eleifend elementum bibendum. Quisque porta, lacus eget vehicula aliquam, augue ante dignissim lectus, eu porta neque magna sit amet odio. Morbi gravida quam a libero blandit, nec laoreet tortor finibus. In facilisis augue sed ante interdum, nec consequat arcu feugiat. Morbi sagittis justo non ligula luctus imperdiet. Integer ultrices diam vel venenatis sodales. Praesent nisl est, vulputate ut viverra quis, rhoncus et libero.</p>
            </div>
            <a href="#" class="btn_like">
                <img class="like_img" width="32" height="32" src="<?= $global_params["root_public"] . "assets/image/like.png"?>"><span class="like_num"">0</span>
            </a>
            <div class="espace" style="grid-area: espace;"></div>
            <dfn title="Voulez-vous signaler?">
                <div class="btn_report">
                    <a href="#" class="report_ref">
                        <img class="report_img" width="32" height="32" src="<?= $global_params["root_public"] . "assets/image/report.png"?>">
                    </a>
                </div>
            </dfn>
        </div>
    </div>

    <?php
}

// MESSAGE PRIVEE
function private_message($message) { // WIP
}

// FRIENDS
function friend_bloc($friend) { // necessite un friend_js_bloc sur la même page
    ?>
        <div class = "mid_sub_content" id="friend_bloc_<?=htmlentities($friend["username"])?>" class="posts_and_user">
            <div id = "profile">
                <?php if($friend["enable_public"]) { ?>
                <a href="<?=$GLOBALS["global_params"]["root_public"]?>page/public/public_page.php?user=<?=htmlentities($friend["public_name"])?>">
                  <img 
                        class="profile_img_profile" 
                        src="<?= getImagePath( $friend["enable_public"] ? $friend["public_image"] : "none")  ?>"
                    >
                </a>
                <?php } else { ?>
                    <img 
                        class="profile_img_profile" 
                        src="<?= getImagePath( $friend["enable_public"] ? $friend["public_image"] : "none")  ?>"
                    >
                <?php } ?>

                <div class="info_profile">
                    <span class="profile_private_name">Pseudo: <?=htmlentities($friend["username"])?></span>
                    <?php if($friend["enable_public"]) { ?>
                    <span class="profile_public_name" >Nom: <?=   htmlentities($friend["public_name"])?></span>
                    <span class="profile_title"       >Titre: <?= htmlentities($friend["title"])?></span>
                    <span class="profile_specie"      >Espece: <?=htmlentities($friend["specie"])?></span>
                    <span class="profile_class"       >Classe: <?=htmlentities($friend["class"])?></span>
                    <?php } else { ?>
                    <span></span> <span></span> <span></span> <span></span>
                    <?php } ?>
                    

                    <div class="user_menu">
                        <button class="btn_menu_user">&#8226;&#8226;&#8226;</button>
                        <div class="user_menu_content border">
                            <button class="btn_ignr_user" onclick='removeFriend("<?=htmlentities($friend["username"])?>");'
                            >Supprimer</button>
                        </div>
                    </div>
                    
                    <span></span>
                    <a href="dm.php?private=true&user=<?=htmlentities($friend["username"])?>">
                        <img class="msg_img" width="32" height="32" src="<?=$GLOBALS["global_params"]["root_public"]?>assets/image/msg.png">
                    </a>

                </div> <br><br>
            </div>

            <br>
        </div>
    <?php

}

    function friend_js_bloc() {
        ?> <script>
            function removeFriend(username) {
                let friendBloc = document.getElementById("friend_bloc_"+username);

                let data = new FormData();
                data.append("username", username);
                data.append("remove_friend", "<?= $_SESSION["remove_friend"] = randomString() ?>");

                let xmlhttp = new XMLHttpRequest();
                xmlhttp.open('POST',
                "<?php echo $GLOBALS["global_params"]["root_public"] ?>assets/script/php/remove_friend.php");
                xmlhttp.send( data );

                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState === 4) // request done
                        if (xmlhttp.status === 200) // successful return
                        {
                            alert(xmlhttp.responseText);
                            const feedback = JSON.parse(xmlhttp.responseText);

                            if (feedback["success"])
                                friendBloc.parentNode.removeChild(friendBloc);

                        }
                }
            }
        </script><?php
    }

    function profile_bloc($profile){
    ?>
        <div id = "mid_content" style="margin-top: 0px; text-align: initial;">
        <div id = "profile">
            <a href="public_page.php?user=<?= htmlentities(trim($profile["public_name"])) ?>">
            <img class="profile_img_profile" src="<?= getImagePath( $profile["public_image"])  ?>">
            </a>
            <div class="info_profile">
                <span class="profile_nickname" style="color: white; font-size: 20px">Nom:     <?= htmlentities($profile["public_name"])?></span>
                <span class="profile_titre"    style="color: white; font-size: 20px">Titre:   <?= htmlentities($profile["title"])?></span>
                <span class="profile_espece"   style="color: white; font-size: 20px">Espece:  <?= htmlentities($profile["specie"])?></span>
                <span class="profile_classe"   style="color: white; font-size: 20px">Classe:  <?= htmlentities($profile["class"])?></span>
                <span class="profile_nlikes"   style="color: white; font-size: 20px">Likes:   <?= htmlentities($profile["likes"])?></span>
            </div>
        </div>
            <?php
            if($_SESSION["id"] === $profile["id"]){
                ?>
                    <div class="desc_container">
                        <textarea id="description" class="post_add" name="desc" style="font-size: 18px;" placeholder="<?= trim(htmlentities($profile["description"]))?>" rows="2" maxlength="50"></textarea><br>
                        <button class="submit_add" onclick="updateDesc('<?= trim(htmlentities($profile["description"]))?>');">Changer</button>
                    </div>
                    <div id="container_add">
                        <textarea id="post_content" class="post_add" name="post_content" placeholder="Quel serait votre nouveau post?" rows="5" maxlength="735"></textarea><br>
                        <button class="submit_add" onclick="postAdd();">Poster</button>
                        <button id="inspirate" onclick="inspiration();">inspiration</button>
                    </div>
        <?php
            }else{
        ?>
                <div class="container_desc border" style="border-radius: 15px">
                    <p style="color: white; font-size: 18px; margin-top: 0px; margin-bottom: 0px;"><?= trim(htmlentities($profile["description"]))?></p>
                </div>
        <?php
            }
        ?>

    </div>
        <?php
            }

    function profile_js_bloc() {
        ?> <script>
            function updateDesc(old_desc) {
                let textZone = document.getElementById("description");

                let data = new FormData();
                data.append("user_id", <?= $_SESSION["id"] ?>);
                data.append("old_desc", old_desc);
                data.append("new_desc", textZone.value);
                data.append("update_desc", "<?= $_SESSION["update_desc"] = randomString()?>");

                let xmlhttp = new XMLHttpRequest();
                xmlhttp.open('POST',
                    "<?php echo $GLOBALS["global_params"]["root_public"]?>assets/script/php/change_desc.php");
                xmlhttp.send( data );

                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState === 4) // request done
                        if (xmlhttp.status === 200) // successful return
                        {
                            //alert(xmlhttp.responseText);
                            const feedback = JSON.parse(xmlhttp.responseText);

                            if (feedback["success"])
                                document.location.reload();

                        }
                }
            }

            function postAdd(content) {
                let textZone = document.getElementById("post_content");

                let data = new FormData();
                data.append("user_id", <?= $_SESSION["id"] ?>);
                data.append("post_content", textZone.value);
                data.append("post", "<?= $_SESSION["post"] = randomString()?>");

                let xmlhttp = new XMLHttpRequest();
                xmlhttp.open('POST',
                    "<?php echo $GLOBALS["global_params"]["root_public"]?>assets/script/php/add_posts.php");
                xmlhttp.send( data );

                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState === 4) // request done
                        if (xmlhttp.status === 200) // successful return
                        {
                            //alert(xmlhttp.responseText);
                            const feedback = JSON.parse(xmlhttp.responseText);

                            if (feedback["success"])
                                document.location.reload();

                        }
                }
            }

            function inspiration() {
                let textZone = document.getElementById("post_content");
                textZone.value = "<?= inspirate()?>";

            }
        </script><?php
    }

    function post_bloc($post, $like = false, $reported = false){
            ?>
            <div id = "post_id_<?= htmlentities(trim($post["id"])) ?>" class="mid_content" style="text-align: initial;">
                <div class="posts">
                    <a href="public_page.php?user=<?= htmlentities(trim($post["public_name"])) ?>">
                        <img class="profile_img_posts" src="<?= getImagePath( $post["public_image"])  ?>">
                    </a>
                    <div class="info_containt border" style="border-radius: 15px; padding: 10px 10px;">
                        <a href="public_page.php?user=<?= htmlentities($post["public_name"]) ?>">
                            <span class="post_auteur" style="color: white; font-size: 20px"><?= htmlentities($post["public_name"]) ?></span><br>
                            <span class="post_date" style="color: lightgray; font-size: 14px"><?= date('d/m/Y H:i', htmlentities(trim($post["creation_date"]))); ?></span>
                        </a>
                        <?php
                            if($_SESSION["id"] === $post["user_id"]){
                        ?>
                        <div class="post_menu">
                            <button class="btn_menu_post">&#8226;&#8226;&#8226;</button>
                            <div class="supp_post border">
                                <button class="btn_sup_post" onclick="removePost('<?= htmlentities(trim($post["id"])) ?>');">Supprimer</button>
                            </div>
                        </div>
                                <?php } ?>
                    </div>

                    <div class="post_content border">
                        <p style="color: white; font-size: 18px"><?= trim(htmlentities($post["content"])) ?></p>
                    </div>
                    <?php


                    if (!$like)
                    {?>
                        <button id="btn_like_id_<?= htmlentities(trim($post["id"]))?>" class="btn_like btn_button_btn" onclick="likePost('<?= htmlentities(trim($post["id"]))?>');">
                            <img id="img_like_<?= htmlentities(trim($post["id"]))?>" class="like_img" width="32" height="32" src="<?= $GLOBALS["global_params"]["root_public"]."/assets/image/like.png"?>"><span id="like_id_<?= htmlentities(trim($post["id"])) ?>" class="like_num"><?= trim(htmlentities($post["like_num"])) ?></span>
                        </button>
                    <?php
                    } else {
                    ?>
                        <button id="btn_like_id_<?= htmlentities(trim($post["id"]))?>" class="btn_like btn_button_btn" onclick="unlikePost('<?= htmlentities(trim($post["id"]))?>');">
                            <img id="img_like_<?= htmlentities(trim($post["id"]))?>" class="like_img" width="32" height="32" src="<?= $GLOBALS["global_params"]["root_public"]."/assets/image/liked.png"?>"><span id="like_id_<?= htmlentities(trim($post["id"])) ?>" class="like_num"><?= trim(htmlentities($post["like_num"])) ?></span>
                        </button>
                    <?php
                    }
                    ?>
                    <div class="espace" style="grid-area: espace;"></div>
                    <?php

                        if (!$reported)
                        {?>
                    <dfn title="Voulez-vous signaler?">
                        <div class="btn_report">
                            <button id="btn_report_id_<?= htmlentities(trim($post["id"]))?>" onclick="reportPost('<?= htmlentities(trim($post["id"]))?>');" class="report_ref btn_button_btn">
                                <img id="img_report_like_<?= htmlentities(trim($post["id"]))?>" class="report_img" width="32" height="32" src="<?= $GLOBALS["global_params"]["root_public"]."/assets/image/report.png"?>">
                            </button>
                        </div>
                    </dfn>
                            <?php
                        } else {
                            ?>
                            <dfn title="Vous avez deja signaler">
                                <div class="btn_report">
                                    <button id="btn_report_id_<?= htmlentities(trim($post["id"]))?>"  onclick="unreportPost('<?= htmlentities(trim($post["id"]))?>');" class="report_ref btn_button_btn">
                                        <img id="img_report_like_<?= htmlentities(trim($post["id"]))?>" class="report_img" width="32" height="32" src="<?= $GLOBALS["global_params"]["root_public"]."/assets/image/reported.png"?>">
                                    </button>
                                </div>
                            </dfn>
                            <?php
                        }
                    ?>
                </div>
            </div>

            <?php
        }

    function post_js_bloc() {
        ?> <script>
            function removePost(post_id) {
                let postBlock = document.getElementById("post_id_"+post_id);

                let data = new FormData();
                data.append("post_id", post_id);
                data.append("remove_post", "<?= $_SESSION["remove_post"] = randomString() ?>");

                let xmlhttp = new XMLHttpRequest();
                xmlhttp.open('POST',
                    "<?php echo $GLOBALS["global_params"]["root_public"] ?>assets/script/php/remove_post.php");
                xmlhttp.send( data );

                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState === 4) // request done
                        if (xmlhttp.status === 200) // successful return
                        {
                            //alert(xmlhttp.responseText);
                            const feedback = JSON.parse(xmlhttp.responseText);

                            if (feedback["success"])
                                postBlock.parentNode.removeChild(postBlock);

                        }
                }
            }
            function likePost(post_id) {
                let likeBlock = document.getElementById("post_id_"+post_id);

                let data = new FormData();
                data.append("post_id", post_id);
                data.append("like_post", "<?= $_SESSION["like_post"] = randomString()?>");

                let xmlhttp = new XMLHttpRequest();
                xmlhttp.open('POST',
                    "<?php echo $GLOBALS["global_params"]["root_public"] ?>assets/script/php/like_post.php");
                xmlhttp.send( data );

                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState === 4) // request done
                        if (xmlhttp.status === 200) // successful return
                        {
                            //alert(xmlhttp.responseText);
                            const feedback = JSON.parse(xmlhttp.responseText);

                            if (feedback["success"]) {
                                document.getElementById("img_like_" + post_id).src = "<?= $GLOBALS["global_params"]["root_public"]."assets/image/liked.png"?>";
                                document.getElementById("like_id_" + post_id).textContent  = parseInt(document.getElementById("like_id_" + post_id).textContent,10)+ 1;
                                document.getElementById("btn_like_id_" + post_id).setAttribute( "onClick", "unlikePost("+post_id+");");
                            }

                        }
                }
            }
            function unlikePost(post_id) {
                let likeBlock = document.getElementById("post_id_"+post_id);

                let data = new FormData();
                data.append("post_id", post_id);
                data.append("unlike_post", "<?= $_SESSION["unlike_post"] = randomString()?>");

                let xmlhttp = new XMLHttpRequest();
                xmlhttp.open('POST',
                    "<?php echo $GLOBALS["global_params"]["root_public"] ?>assets/script/php/unlike_post.php");
                xmlhttp.send( data );

                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState === 4) // request done
                        if (xmlhttp.status === 200) // successful return
                        {
                            //alert(xmlhttp.responseText);
                            const feedback = JSON.parse(xmlhttp.responseText);

                            if (feedback["success"]) {
                                document.getElementById("img_like_" + post_id).src = "<?= $GLOBALS["global_params"]["root_public"]."assets/image/like.png"?>";
                                document.getElementById("like_id_" + post_id).textContent  = parseInt(document.getElementById("like_id_" + post_id).textContent,10)- 1;
                                document.getElementById("btn_like_id_" + post_id).setAttribute( "onClick", "likePost("+post_id+");");
                            }

                        }
                }
            }

            function reportPost(post_id) {
                let likeBlock = document.getElementById("post_id_"+post_id);

                let data = new FormData();
                data.append("post_id", post_id);
                data.append("report_post", "<?= $_SESSION["report_post"] = randomString()?>");

                let xmlhttp = new XMLHttpRequest();
                xmlhttp.open('POST',
                    "<?php echo $GLOBALS["global_params"]["root_public"] ?>assets/script/php/report_post.php");
                xmlhttp.send( data );

                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState === 4) // request done
                        if (xmlhttp.status === 200) // successful return
                        {
                            //alert(xmlhttp.responseText);
                            const feedback = JSON.parse(xmlhttp.responseText);

                            if (feedback["success"]) {
                                document.getElementById("img_report_like_" + post_id).src = "<?= $GLOBALS["global_params"]["root_public"]."assets/image/reported.png"?>";
                                document.getElementById("like_id_" + post_id).textContent  = parseInt(document.getElementById("like_id_" + post_id).textContent,10)+ 1;
                                document.getElementById("btn_report_id_" + post_id).setAttribute( "onClick", "unreportPost("+post_id+");");
                            }

                        }
                }
            }
            function unreportPost(post_id) {
                let likeBlock = document.getElementById("post_id_"+post_id);

                let data = new FormData();
                data.append("post_id", post_id);
                data.append("unreport_post", "<?= $_SESSION["unreport_post"] = randomString()?>");

                let xmlhttp = new XMLHttpRequest();
                xmlhttp.open('POST',
                    "<?php echo $GLOBALS["global_params"]["root_public"] ?>assets/script/php/unreport_post.php");
                xmlhttp.send( data );

                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState === 4) // request done
                        if (xmlhttp.status === 200) // successful return
                        {
                            //alert(xmlhttp.responseText);
                            const feedback = JSON.parse(xmlhttp.responseText);

                            if (feedback["success"]) {
                                document.getElementById("img_report_like_" + post_id).src = "<?= $GLOBALS["global_params"]["root_public"]."assets/image/report.png"?>";
                                document.getElementById("btn_report_id_" + post_id).setAttribute( "onClick", "reportPost("+post_id+");");
                            }

                        }
                }
            }
        </script><?php
    }

?>