<?php

// MENU
function menu_when_not_connected () {
    // POUR LE BOUTTON SE CONNECTER
    // redirige vers cette page avec le POST : try_connect
    // ensuite => connection réussite = rien ou header( vers q )
    //         => connection échoué   = rien de particulier

    // POUR LE BUTTON LOGIN
    // redirige vers cette page avec le POST : try_register
    // ensuite => creation réussite  = rien ou header( vers q )
    //         => creation échoué    = rien de particulier

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
                <input id="register_name" type="text" placeholder="Pseudo" name="pseudo" >

                <label class="popup_form_title" for="password"><b>Mot de passe</b></label>
                <input id="register_password" type="password" placeholder="Mot de passe" name="password">
                
                <p id="register_error" class="popup_text" style="display:none"> ERROR </p>

                <button type="submit" onclick="register();">S'inscrire</button>
                <button type="button" onclick="document.getElementById('register').style.display='none'" class="cancelbtn">Annuler</button>
            </div> 
            </div>
        </div>

        <script>
            ////////////////////////

            function login() {
                
                nickname = document.getElementById("login_name");
                password = document.getElementById("login_password");
                remember = document.getElementById("login_remember");
                debug    = document.getElementById("login_error");

                var data = new FormData();
                data.append("username", nickname.value);
                data.append("password", password.value);
                data.append("remember", remember.value);
                //////////

                var xmlhttp = new XMLHttpRequest();
                
                xmlhttp.open('POST', '../../assets/script/php/login.php');
                xmlhttp.send( data );

                xmlhttp.addEventListener('load', (e) => {
                    alert(e.reponseText);
	                //var o = JSON.parse(e.responseText);

                    if (true) {
                        password.value = "";
                        debug.innerHTML = "La connection a échoué.";
                        debug.style.display = "block";
                    }
                    else {
                        debug.innerHTML = "Connection réussis.";
                        debug.style.display = "block";
                        redirection();
                    }

                }, false);
            }

            function register() {
                nickname = document.getELementById("register_name");
                password = document.getElementById("register_password");
                debug    = document.getElementById("register_error");

                // RATE
                password.value = "";
                debug   .innerHTML = "La création de compte à échoué.";
                debug.style.display = "block";

                // REUSSIS
                redirection();
            }

            ////////////////////////

            function redirection() { // en reference au GET : q=
                url = GET("q");
                if (url == null) return;
                url = decodeURIComponent(url.replace(/\+/g, ' '));
                window.open(url,"_self");
            }

            function GET (param) {
                var otp = {};

                window.location.href.replace( location.hash, '' ).replace( 
                    /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
                    function( m, key, value ) { // callback
                        otp[key] = value !== undefined ? value : '';
                    }
                );

                if (param) return otp[param] ? otp[param] : null;
                return otp;
            }

        </script>

    <?php
    
    if (isset($_GET["to_connect"])) {
        ?> <script type="text/javascript">
            document.getElementById('login').style.display='block';
        </script> <?php
    }
}

function menu_when_connected () {
    ?>
        
    <?php
}

// BARRE DE RECHERCHE
function search($query) { // WIP
    return [ 1, 2, 3, 4 ]; // retourner une liste d'id de message
}

// MESSAGE
function public_message($id, $user_id, $content) { // WIP

}

// MESSAGE PRIVEE
function private_message($id) { // WIP

}

?>