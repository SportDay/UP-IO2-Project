<?php

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
                    src=<?php echo getImagePath($_SESSION["public_name"]) ?> width="60"
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

            function openProfile() {
                openPage('public/public_page.php?user=<?php $_SESSION["public_name"] ?>');
            }

            function disconnect() {
                let data = new FormData();
                let xmlhttp = new XMLHttpRequest();
                
                xmlhttp.open('POST', 
                "<?php echo $GLOBALS["global_params"]["root_public"]?>/assets/script/php/disconnect.php");
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
                                    window.open(window.location.href.split('?')[0], "_self"); // SI PAGE PUBLIC
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

?>