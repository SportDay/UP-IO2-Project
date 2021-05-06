<?php 

/*

    MODULES:
    Les modules correspondent à un ensemble de fonctions qui générent des élements html procéduraux.
    Ces fonctions produisent donc effets de bords sur les pages ou elles sont appelés.

    A noter que les scripts (javascript) prennent beaucoup de place.
    Il est à envisager de créer des fichiers .js dans root_public/assets/script/js/*.js
    Et d'y mettre tout ces scripts.
    Pour les variables de pages, on pourrait utiliser des var qui serait set dans un <script></script> au préalable

*/

////////////////////////////////////////////////
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
                                { // SI PAGE PUBLIC
                                    let url = window.location.href.split('?');

                                    if (url.length == 1)
                                        window.open(url[0] + '?' +           'to_connect&q=' + encodeURIComponent(window.location.href)
                                        , "_self");
                                    else
                                        window.open(url[0] + '?' + url[1] + '&to_connect&q=' + encodeURIComponent(window.location.href)
                                        , "_self");
                                }
                            }
                        }
                }
            }
        </script>

    <?php
}

////////////////////////////////////////////////
// BARRE DE RECHERCHE
function search($query) { // WIP
    return [ 1, 2, 3, 4 ]; // retourner une liste d'id de message
}

////////////////////////////////////////////////
// FRIENDS

function add_friend_bloc($friend) {

    $public_page = $friend["enable_public"] ? $friend["public_name"] : "";
    $public_page = $GLOBALS["global_params"]["root_public"] . "page/public/public_page.php?user=" . urlencode ($public_page);

    ?>
        <div class="request_friend_list" id="friend_bloc_<?=htmlentities($friend["username"])?>">

            <?php if ( $friend["enable_public"] ) { ?>
                <a href="<?= $public_page ?>">
                <img class="request_profile_img" src="<?= getImagePath( $friend["public_image"])  ?>">
                </a>
            <?php } else { ?>
                <img class="request_profile_img" src="<?= getImagePath("") ?>">
            <?php } ?>

            <div class="request_profile_content border" >
                <p class="nick_name_friend"><?= htmlentities($friend["username"])." vous a ajouté!" ?></p>
                <button class="btn_button_btn acceptbtn_low_size btn_accept_friend_btn" onclick='acceptFriend("<?=htmlentities($friend["username"])?>")'
                >Accepter</button>
                <button class="btn_button_btn cancelbtn_low_size btn_reject_friend_btn" onclick='removeFriend("<?=htmlentities($friend["username"])?>")'
                >Refuser</button>
            </div>

        </div>
    <?php
}

function add_friend_js_bloc() {
    ?> <script>
        function acceptFriend(friend) {
            let friendBlocs = document.getElementById("friend_blocs_area");
            let requestBloc = document.getElementById("friend_bloc_" + friend);

            let data = new FormData();
            data.append("username", friend);
            data.append("from_root", "<?=$GLOBALS["global_params"]["root_public"]?>");
            data.append("accept_friend", "<?= $_SESSION["accept_friend"] = randomString() ?>");

            let xmlhttp = new XMLHttpRequest();
            xmlhttp.open('POST',
            "<?php echo $GLOBALS["global_params"]["root_public"] ?>assets/script/php/accept_friend.php");
            xmlhttp.send( data );

            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState === 4) // request done
                    if (xmlhttp.status === 200) // successful return
                    {
                        //alert(xmlhttp.responseText);
                        const feedback = JSON.parse(xmlhttp.responseText);
                        
                        if (feedback["success"])
                        {
                            parentBloc = requestBloc.parentNode;

                            if (parentBloc.childElementCount < 3)
                                parentBloc.parentNode.removeChild(parentBloc);
                            else
                                parentBloc.removeChild(requestBloc);

                            friendBlocs.innerHTML = feedback["html"] + friendBlocs.innerHTML;
                        }
                    }
            }
        }

        function refuseFriend(friend) {
            let requestBloc = document.getElementById("friend_bloc_" + friend);

            let data = new FormData();
            data.append("username", $friend);
            data.append("refuse_friend", "<?= $_SESSION["refuse_friend"] = randomString() ?>");

            let xmlhttp = new XMLHttpRequest();
            xmlhttp.open('POST',
            "<?php echo $GLOBALS["global_params"]["root_public"] ?>assets/script/php/refuse_friend.php");
            xmlhttp.send( data );

            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState === 4) // request done
                    if (xmlhttp.status === 200) // successful return
                    {
                        //alert(xmlhttp.responseText);
                        const feedback = JSON.parse(xmlhttp.responseText);
                        
                        if (feedback["success"])
                        {
                            requestBloc.parentNode.removeChild(requestBloc);
                            let elements_list = document.getElementById("friends_request_list");
                            if(elements_list.children.length === 1){
                                elements_list.parentNode.removeChild(elements_list);;
                            }
                        }
                    }
            }
        }
    </script><?php
}

function friend_bloc($friend, $specific_root=FALSE, $root_public="") { // necessite un friend_js_bloc sur la même page

    if (!$specific_root)
        $root_public = $GLOBALS["global_params"]["root_public"];

    ?>

        <div id="friend_bloc_<?=htmlentities($friend["username"])?>" class="mid_content" style="text-align: initial;">
            <div id = "profile">


                <?php if($friend["enable_public"]) { ?>
                <a href="<?=$root_public?>page/public/public_page.php?user=<?=urlencode($friend["public_name"])?>">
                    <img class="profile_img_profile" src="<?= getImagePath( $friend["enable_public"] ? $friend["public_image"] : "none", true, $root_public)  ?>">
                </a>
                <?php } else { ?>
                    <img class="profile_img_profile" src="<?= getImagePath( $friend["enable_public"] ? $friend["public_image"] : "none", true, $root_public)  ?>">
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
                            <button class="btn_ignr_user" class="btn_ignr_user" onclick='removeFriend(<?=json_encode($friend["username"])?>);'>Supprimer</button>
                        </div>
                    </div>
                    <div class="friend_porfile_espace"></div>
                    <a href="dm.php?private=true&user=<?=urlencode($friend["username"])?>">
                        <img class="msg_img" width="32" height="32" src="<?=$root_public?>assets/image/msg.png">
                    </a>
                </div>


            </div>
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
                        //alert(xmlhttp.responseText);
                        const feedback = JSON.parse(xmlhttp.responseText);

                        if (feedback["success"])
                            friendBloc.parentNode.removeChild(friendBloc);

                    }
            }
        }
    </script><?php
}

////////////////////////////////////////////////
// PROFILES BLOC
function profile_bloc($profile, $friend = null){
    ?>
        <div class = "mid_content" style="text-align: initial;">
        <div id = "profile">
            <a href= "<?= $GLOBALS['global_params']['root_public'] ?>page/public/public_page.php?user=<?= urlencode($profile["public_name"]) ?>">
            <img class="profile_img_profile" src="<?= getImagePath( $profile["public_image"])  ?>">
            </a>
            <div class="info_profile">
                <span class="profile_nickname" >Nom: <?= htmlentities($profile["public_name"])?></span>
                <span class="profile_titre"    >Titre: <?= htmlentities($profile["title"])?></span>
                <span class="profile_espece"   >Espece: <?= htmlentities($profile["specie"])?></span>
                <span class="profile_classe"   >Classe: <?= htmlentities($profile["class"])?></span>
                <span class="profile_nlikes"   >Likes: <?= htmlentities($profile["likes"])?></span>
                <?php if(isset($friend) && !$friend){ ?>
                <button id="friend_add_btn" class="btn_friend_porfile_add btn_button_btn" style="background-color: #41bb41;" onclick='ajouterAmis(<?= "à corriger" ?>);'>Liker la page</button>
                <?php }else if(isset($friend) && $friend){ ?>
                <button id="friend_add_btn" class="btn_friend_porfile_add btn_button_btn" style="background-color: #bb4141;" onclick='supprimerAmis(<?= json_encode($profile["username"]) ?>);'>Unlike la page</button>
                <?php } ?>
            </div>
        </div>
            <?php
            if(isset($_SESSION["id"]) && $_SESSION["id"] === $profile["id"]){
                ?>
                    <div class="desc_container">
                        <textarea id="description" class="post_add" name="desc" style="font-size: 18px;" placeholder="<?= trim(htmlentities($profile["description"]))?>" rows="2" maxlength="50"></textarea><br>
                        <button class="submit_add" onclick="updateDesc(<?= json_encode($profile['description'])?>);">Changer</button>
                    </div>
                    <div id="container_add">
                        <textarea id="post_content" class="post_add" name="post_content" placeholder="Quel serait votre nouveau post?" rows="5" maxlength="735"></textarea><br>
                        <button class="submit_add" onclick="postAdd();">Poster</button>
                        <button id="inspirate" onclick="inspiration();">Inspiration</button>
                    </div>
        <?php
            }else{
        ?>
                <div class="container_desc border" style="border-radius: 15px">
                    <p style="color: white; font-size: 18px; margin-top: 0px; margin-bottom: 0px;"><?= htmlentities(trim($profile["description"]))?></p>
                </div>
        <?php
            }
        ?>

    </div>
        <?php

}

function profile_js_bloc($me) {

            ?> <script>
            <?php
            if(isset($_SESSION["username"]) && $_SESSION["username"] === $me["username"]){
            ?>
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
            <?php
            }else{
            ?>
                function ajouterAmis(friend_id){
                    let textZone = document.getElementById("friend_add_btn");

                    let data = new FormData();
                    data.append("friend_id", friend_id);
                    data.append("friend", "<?= $_SESSION["friend"] = randomString()?>");

                    let xmlhttp = new XMLHttpRequest();
                    xmlhttp.open('POST',
                        "<?php echo $GLOBALS["global_params"]["root_public"]?>assets/script/php/request_friend.php");
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
                function supprimerAmis(username){
                    let data = new FormData();
                    data.append("username", username);
                    data.append("remove_friend", "<?= $_SESSION["remove_friend"] = randomString() ?>");

                    let xmlhttp = new XMLHttpRequest();
                    xmlhttp.open('POST',
                        "<?php echo $GLOBALS["global_params"]["root_public"]?>assets/script/php/remove_friend.php");
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
            <?php
            }
            ?>
        </script><?php
}

////////////////////////////////////////////////
// POSTS
function post_add(){
    ?>
    <div class = "mid_content" style="text-align: initial;">
        <div id="container_add">
            <textarea id="post_content" class="post_add" name="post_content" placeholder="Quel serait votre nouveau post?" rows="5" maxlength="735"></textarea><br>
            <button class="submit_add" onclick="postAdd();">Poster</button>
            <button id="inspirate" onclick="inspiration();">Inspiration</button>
        </div>
    </div>
    <?php
}

function post_js_add(){
    ?>
    <script>
        function postAdd() {
            let textZone = document.getElementById("post_content");
            if(textZone.value === "") return;
            let data = new FormData();
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

                        if (feedback["success"]) {
                            document.location.reload();
                        }else{
                            textZone.value = feedback["error"];
                        }

                    }
            }
        }
        function inspiration() {
            let textZone = document.getElementById("post_content");
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.open('POST',
                "<?php echo $GLOBALS["global_params"]["root_public"]?>assets/script/php/inspiration.php");
            xmlhttp.send();

            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState === 4) // request done
                    if (xmlhttp.status === 200) // successful return
                    {
                        //alert(xmlhttp.responseText);
                        const feedback = JSON.parse(xmlhttp.responseText);

                        if (feedback["success"]) {
                            textZone.value = feedback["message"].trim();
                        }
                    }
            }
        }
    </script>
    <?php
}

function post_bloc($post, $like = false, $reported = false, $connected = false){

        $connected = isset($_SESSION["connected"]) && $_SESSION["connected"]; 

    ?>
    
    <div id = "post_id_<?= htmlentities(trim($post["id"])) ?>" class="mid_content" style="text-align: initial;">
    <div class="posts">

        <!-- USER -->
        <a href="<?= $GLOBALS['global_params']['root_public'] ?>page/public/public_page.php?user=<?= urlencode($post["public_name"]) ?>">

            <img class="profile_img_posts" src="<?= getImagePath( $post["public_image"])  ?>">
        
        </a>

        <div class="info_containt border" style="border-radius: 15px; padding: 10px 10px;">
            <a href="<?= $GLOBALS['global_params']['root_public'] ?>page/public/public_page.php?user=<?= urlencode($post["public_name"]) ?>">
                <span class="post_auteur" style="color: white; font-size: 20px"><?= htmlentities($post["public_name"]) ?></span><br>
                <span class="post_date" style="color: lightgray; font-size: 14px"><?= date('d/m/Y H:i', htmlentities(trim($post["creation_date"]))); ?></span>
            </a>
            <?php
                if(isset($_SESSION["id"]) && $_SESSION["id"] === $post["user_id"]){
            ?>
            <div class="post_menu">
                <button class="btn_menu_post">&#8226;&#8226;&#8226;</button>
                <div class="supp_post border">
                    <button class="btn_sup_post" onclick="removePost('<?= htmlentities($post['id']) ?>');">Supprimer</button>
                </div>
            </div>
                    <?php } ?>
        </div>


        <!-- CONTENT -->
        <div class="post_content border">
            <p style="color: white; font-size: 18px"><?= htmlentities($post["content"]) ?></p>
        </div>

        <!-- INTERACT -->
        <?php if($connected) {?>
                <button id="btn_like_id_<?= htmlentities($post["id"])?>" class="btn_like btn_button_btn" onclick="likeSystemPost('<?= $post['id']?>');">
                <?php
                if (!$like)
                {  ?>
                    <img id="img_like_<?= htmlentities($post["id"])?>" class="like_img" width="32" height="32" src="<?= $GLOBALS["global_params"]["root_public"]."/assets/image/like.png"?>"><span id="like_id_<?= htmlentities(trim($post["id"])) ?>" class="like_num"><?= htmlentities($post["like_num"]) ?></span>
                    <?php }else{?>
                    <img id="img_like_<?= htmlentities($post["id"])?>" class="like_img" width="32" height="32" src="<?= $GLOBALS["global_params"]["root_public"]."/assets/image/liked.png"?>"><span id="like_id_<?= htmlentities(trim($post["id"])) ?>" class="like_num"><?= htmlentities($post["like_num"]) ?></span>
                <?php } ?>
                </button>
            <?php
            } else { ?>
                <button id="btn_like_id_<?= htmlentities($post["id"])?>" class="btn_like btn_button_btn">
                    <img id="img_like_<?= htmlentities($post["id"])?>" class="like_img" width="32" height="32" src="<?= $GLOBALS["global_params"]["root_public"]."/assets/image/like.png"?>"><span id="like_id_<?= htmlentities(trim($post["id"])) ?>" class="like_num"><?= trim(htmlentities($post["like_num"])) ?></span>
                </button>
        <?php } ?>

        <div class="post_btn_espace" style="grid-area: post_btn_espace;"></div>

            <?php if($connected){?>
                <dfn title="<?php if(!$reported){ echo "Voulez-vous signaler?";}else{ echo "Vous avez deja signaler";}?>">
                    <div class="btn_report">
                        <button id="btn_report_id_<?= htmlentities(trim($post["id"]))?>" onclick="reportSystemPost('<?= $post['id']?>');" class="report_ref btn_button_btn">
                            <?php if (!$reported) {?>
                                <img id="img_report_like_<?= htmlentities(trim($post["id"]))?>" class="report_img" width="32" height="32" src="<?= $GLOBALS["global_params"]["root_public"]."/assets/image/report.png"?>">
                            <?php } else {?>
                                <img id="img_report_like_<?= htmlentities(trim($post["id"]))?>" class="report_img" width="32" height="32" src="<?= $GLOBALS["global_params"]["root_public"]."/assets/image/reported.png"?>">
                            <?php }?>
                    </button>
                    </div>
                </dfn>
                    <?php }else{?>
            <div class="btn_report">
                <button id="btn_report_id_<?= htmlentities(trim($post["id"]))?>" class="report_ref btn_button_btn">
                    <img id="img_report_like_<?= htmlentities(trim($post["id"]))?>" class="report_img" width="32" height="32" src="<?= $GLOBALS["global_params"]["root_public"]."/assets/image/report.png"?>">
                </button>
            </div>
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
        function likeSystemPost(post_id) {
            let data = new FormData();
            data.append("post_id", post_id);
            data.append("like_post", "<?= $_SESSION["like_post"] = randomString()?>");

            let xmlhttp = new XMLHttpRequest();
            xmlhttp.open('POST',
                "<?php echo $GLOBALS["global_params"]["root_public"] ?>assets/script/php/likeSystemPost.php");
            xmlhttp.send( data );

            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState === 4) // request done
                    if (xmlhttp.status === 200) // successful return
                    {
                        //alert(xmlhttp.responseText);
                        const feedback = JSON.parse(xmlhttp.responseText);

                        if (feedback["success"]) {
                            if(feedback["liked"]) {
                                document.getElementById("img_like_" + post_id).src = "<?= $GLOBALS["global_params"]["root_public"] . "assets/image/liked.png"?>";
                            }else{
                                document.getElementById("img_like_" + post_id).src = "<?= $GLOBALS["global_params"]["root_public"] . "assets/image/like.png"?>";
                            }
                            document.getElementById("like_id_" + post_id).textContent  = feedback["nbr_like"];
                        }
                    }
            }
        }

        function reportSystemPost(post_id) {
            let data = new FormData();
            data.append("post_id", post_id);
            data.append("report_post", "<?= $_SESSION["report_post"] = randomString()?>");

            let xmlhttp = new XMLHttpRequest();
            xmlhttp.open('POST',
                "<?php echo $GLOBALS["global_params"]["root_public"] ?>assets/script/php/reportSystemPost.php");
            xmlhttp.send( data );

            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState === 4) // request done
                    if (xmlhttp.status === 200) // successful return
                    {
                        //alert(xmlhttp.responseText);
                        const feedback = JSON.parse(xmlhttp.responseText);

                        if (feedback["success"]) {
                            if(feedback["report"]) {
                                document.getElementById("img_report_like_" + post_id).src = "<?= $GLOBALS["global_params"]["root_public"]."assets/image/reported.png"?>";
                            }else{
                                document.getElementById("img_report_like_" + post_id).src = "<?= $GLOBALS["global_params"]["root_public"]."assets/image/report.png"?>";
                            }
                        }

                    }
            }
        }
    </script><?php
}

function post_reported_bloc($post, $like = false, $reported = false){?>
    <div id = "post_id_<?= htmlentities(trim($post["id"])) ?>" class="mid_content" style="text-align: initial;">
    <div class="posts">

        <!-- USER -->
        <a href="<?= $GLOBALS['global_params']['root_public'] ?>page/public/public_page.php?user=<?= urlencode($post["public_name"]) ?>">
            <img class="profile_img_posts" src="<?= getImagePath( $post["public_image"])  ?>">
        </a>
        <div class="info_containt border" style="border-radius: 15px; padding: 10px 10px;">
            <a href="<?= $GLOBALS['global_params']['root_public'] ?>page/public/public_page.php?user=<?= urlencode($post["public_name"]) ?>">
                <span class="post_auteur"><?= htmlentities($post["public_name"]) ?></span><br>
                <span class="post_date"><?= date('d/m/Y H:i', htmlentities(trim($post["creation_date"]))); ?></span>
            </a>
            <div class="post_menu">
                <button class="btn_menu_post">&#8226;&#8226;&#8226;</button>
                <div class="supp_post border">
                        <button class="btn_ignr_post" onclick="ignoreReport('<?= htmlentities($post['id']) ?>');">Ignorer</button>
                        <button class="btn_sup_post" onclick="removeReportedPost('<?= htmlentities($post['id']) ?>');">Supprimer</button>
                        <button class="btn_def_ban_user" onclick="banDefinitif('<?= htmlentities($post['user_id']) ?>');">Ban définitif</button>
                        <button class="btn_tmp_ban_user" onclick="showTempBanBlock('<?= htmlentities($post['user_id']) ?>');">Ban temporaire</button>
                </div>
            </div>
        </div>
        <!-- CONTENT -->
        <div class="post_content border">
            <p style="color: white; font-size: 18px"><?= htmlentities($post["content"]) ?></p>
        </div>

        <!-- INTERACT -->
                <button id="btn_like_id_<?= htmlentities($post["id"])?>" class="btn_like btn_button_btn">
                <?php
                if (!$like)
                {  ?>
                    <img id="img_like_<?= htmlentities($post["id"])?>" class="like_img" width="32" height="32" src="<?= $GLOBALS["global_params"]["root_public"]."/assets/image/like.png"?>"><span id="like_id_<?= htmlentities(trim($post["id"])) ?>" class="like_num"><?= htmlentities($post["like_num"]) ?></span>
                    <?php }else{?>
                    <img id="img_like_<?= htmlentities($post["id"])?>" class="like_img" width="32" height="32" src="<?= $GLOBALS["global_params"]["root_public"]."/assets/image/liked.png"?>"><span id="like_id_<?= htmlentities(trim($post["id"])) ?>" class="like_num"><?= htmlentities($post["like_num"]) ?></span>
                <?php } ?>
                </button>

        <div class="post_btn_espace" style="grid-area: post_btn_espace;"></div>
                    <div class="btn_report">
                        <button id="btn_report_id_<?= htmlentities(trim($post["id"]))?>" class="report_ref btn_button_btn">
                            <?php if (!$reported) {?>
                                <img id="img_report_like_<?= htmlentities(trim($post["id"]))?>" class="report_img" width="32" height="32" src="<?= $GLOBALS["global_params"]["root_public"]."/assets/image/report.png"?>">
                            <?php } else {?>
                                <img id="img_report_like_<?= htmlentities(trim($post["id"]))?>" class="report_img" width="32" height="32" src="<?= $GLOBALS["global_params"]["root_public"]."/assets/image/reported.png"?>">
                            <?php }?>
                    </button>
                    </div>
        </div>
    </div>



<?php }

function post_reported_js_bloc(){?>
    <script>
        function ignoreReport(post_id) {
            let postBlock = document.getElementById("post_id_"+post_id);

            let data = new FormData();
            data.append("post_id", post_id);
            data.append("ignore_post", "<?= $_SESSION["ignore_post"] = randomString() ?>");

            let xmlhttp = new XMLHttpRequest();
            xmlhttp.open('POST',
                "<?php echo $GLOBALS["global_params"]["root_public"] ?>assets/script/php/ignore_post.php");
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

        function removeReportedPost(post_id) {
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

        function banDefinitif(user_id) {

            let data = new FormData();
            data.append("user_id", user_id);
            data.append("bandef", "<?= $_SESSION["bandef"] = randomString() ?>");

            let xmlhttp = new XMLHttpRequest();
            xmlhttp.open('POST',
                "<?php echo $GLOBALS["global_params"]["root_public"] ?>assets/script/php/ban_def_user.php");
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

        function showTempBanBlock(user_id){
            document.getElementById('tmp_ban').style.display='block';
            document.getElementById('ban_btn').setAttribute( "onClick", "banTemporaire("+user_id+");");
        }

        function hideTempBanBlock(){
            document.getElementById('tmp_ban').style.display='none'
            document.getElementById("ban_btn").removeAttribute("onClick");
        }

        function banTemporaire(user_id) {

            let checked;
            let radios = document.getElementsByName('time');

            for (let i = 0; i < radios.length; i++) {
                if (radios[i].checked) {
                    checked = radios[i].value;
                    break;
                }
            }

            let data = new FormData();
            data.append("user_id", user_id);
            data.append("time", document.getElementById("time_input").value);
            data.append("type", checked);
            data.append("bantemp", "<?= $_SESSION["bantemp"] = randomString() ?>");

            let xmlhttp = new XMLHttpRequest();
            xmlhttp.open('POST',
                "<?php echo $GLOBALS["global_params"]["root_public"] ?>assets/script/php/ban_tmp_user.php");
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

        window.onclick = function(event) {
            if (event.target == document.getElementById('tmp_ban')) {
                document.getElementById('tmp_ban').style.display = "none";
                document.getElementById("ban_btn").removeAttribute("onClick");
            }
        }

    </script>

    <?php
}

?>