<?php
    /*

    SECURITY:
    Ce fichier repertorie des fonction de verification relative à la gestion des compte et la connexion.
    Ces fonctions ont donc naturellement des effets de bords sur la base de donnée réseau et les cookies/session.

    */

    // ACTUALISATION DE LA SESSION (s'execute avant l'execution de chaque page)

    function simple_disconnect () { // reset de session
        /*
        session_unset();
        session_destroy();
        session_start();
        */
        $_SESSION["connected"] = false;
        $_SESSION["admin"]     = false;
    }

    function tryConnect () {
        $connexion = mysqli_connect (
            $GLOBALS["DB_URL"],
            $GLOBALS["DB_ACCOUNT"],
            $GLOBALS["DB_PASSWORD"],
            $GLOBALS["DB_NAME"]
        );

        if (!$connexion) { 
            echo "connection_error"; exit(); 
        }

        mysqli_set_charset($connexion, "utf8");

        //////////////////////////////////////
        // TRY TO CONNECT USING SESSION
        if (isset($_SESSION["connected"]) && $_SESSION["connected"]) {
            if (isset($_SESSION["inactive_time"]) && $_SESSION["inactive_time"] > time() )
            {
                $result = $connexion->query(
                    "SELECT * FROM users WHERE id=". $_SESSION["id"] . " ;"
                )->fetch_assoc();

                // CONNECT
                $_SESSION["inactive_time"] = min(
                    $_SESSION["max_time"], 
                    (time() + $GLOBALS["TIME_SESS_INACTIVE"])
                );

                $_SESSION["last_time"]     = time();
                $_SESSION["banned"]        = $result["banned_to"] > time();
                $_SESSION["banned_to"]      = $result["banned_to"];

                mysqli_query($connexion, 
                    "UPDATE users SET " .
                    "last_join=" . $_SESSION["last_time"] . ", " .
                    "banned=" . $_SESSION["banned"]       . " "  .
                    "WHERE `id`=" . $_SESSION["id"]       . " ;"
                );

                $_SESSION["memory_public"] = $result["memory_public"];
                
                mysqli_close($connexion);
                return;
            }

        }

        simple_disconnect();

        //////////////////////////////////////
        // TRY TO CONNECT USING COOKIES
        
        if (!isset($_COOKIE["cookie_id"])  || !isset($_COOKIE["cookie_pass"])) {
            mysqli_close($connexion);
            return;
        }
        
        $result = $connexion->query(
            "SELECT * FROM users WHERE cookie_id=\"". $connexion->real_escape_string($_COOKIE["cookie_id"]) . "\" ;"
        );

        if (
            $result->num_rows == 0 ||
            !($result = $result->fetch_assoc())["cookie_enabled"] ||
            $_COOKIE["cookie_pass"] != $result["cookie_pass"] ||
            $_COOKIE["cookie_expire"] < time()            
            ) { mysqli_close($connexion); return; }

        //////////////////////////////////////////////
        // NOW CONNECT

        $_SESSION["id"]             = $result["id"];
        $_SESSION["username"]       = $result["username"];
        $_SESSION["admin"]          = $result["admin"];

        $_SESSION["enable_public"]  = $result["enable_public"];
        $_SESSION["memory_public"]  = $result["memory_public"];
        $_SESSION["public_name"]    = $result["public_name"];
        $_SESSION["public_image"]   = $result["public_image"];
        $_SESSION["banned"]         = $result["banned_to"] > time();
        $_SESSION["banned_to"]      = $result["banned_to"];

        $_SESSION["init_time"]      = time();
        $_SESSION["last_time"]      = time();
        $_SESSION["inactive_time"]  = time() + $GLOBALS["TIME_SESS_INACTIVE"];
        $_SESSION["max_time"]       = time() + $GLOBALS["TIME_SESS_END"];

        $_SESSION["connected"]      = true;

        mysqli_query($connexion, 
            "UPDATE users SET " .
            "last_join=" . $_SESSION["last_time"] . ", " .
            "banned=" . $_SESSION["banned"]       . " "  .
            "WHERE `id`=" . $_SESSION["id"]       . " ;"
        );
        
        mysqli_close($connexion);
    }

    // SUPPRESSION DES DONNEES

    function removeAccount($currentAccount=TRUE, $id=0) {
    if ($currentAccount) $id = $_SESSION["id"];

    if (!( ($error = removePublicPage($currentAccount, $id)) === FALSE))
        return $error;

    //////////////
    $connexion = mysqli_connect (
        $GLOBALS["DB_URL"],
        $GLOBALS["DB_ACCOUNT"],
        $GLOBALS["DB_PASSWORD"],
        $GLOBALS["DB_NAME"]
    );
    if (!$connexion) {
        return "Can't connect to database.";
    }
    mysqli_set_charset($connexion, "utf8");


    // supprimer: friends direct_messages
    $connexion->query( // pas besoin de verifier que c'est privé
        "DELETE FROM `friends` WHERE `user_id_0`=" . $id . " OR `user_id_1`=" . $id . " ;"
    );
    $connexion->query(
        "DELETE FROM `direct_messages` WHERE `from_id`=" . $id . " OR `to_id`=" . $id . " ;"
    );

    // supprimer le compte: (users)
    $connexion->query(
        "DELETE FROM `users` WHERE `id`=" . $id . " ;"
    );

    if ($currentAccount)
    {
        // mettre fin aux cookies/session
        setcookie("cookie_id",      "", time() - 3600, $GLOBALS["COOKIE_PATH"]);
        setcookie("cookie_pass",    "", time() - 3600, $GLOBALS["COOKIE_PATH"]);
        setcookie("cookie_expire",  "", time() - 3600, $GLOBALS["COOKIE_PATH"]);

        session_unset();
        session_destroy();
        session_start();

    }

    mysqli_close($connexion);
    return false;
}

    function removePublicPage($currentAccount=TRUE, $id=0) {
        if ($currentAccount) $id = $_SESSION["id"];

        //////////////
        $connexion = mysqli_connect (
            $GLOBALS["DB_URL"],
            $GLOBALS["DB_ACCOUNT"],
            $GLOBALS["DB_PASSWORD"],
            $GLOBALS["DB_NAME"]
        );
        if (!$connexion) {
            return "Can't connect to database.";
        }
        mysqli_set_charset($connexion, "utf8");

        $userData = $connexion->query("SELECT * FROM `users` WHERE id=" . $id . " ;")->fetch_assoc();

        if (!$userData["enable_public"]) {
            mysqli_close($connexion);
            return false;
        }

        /////////////
        // supprimer: reports, posts, pages_liked likes direct_messages
        // attention, il y a un ordre de suppression

        // pages_liked | parents : | enfants : reports et likes
        $connexion->query(
            "DELETE FROM `pages_liked` WHERE `user_id`=" . $id . " ;"
        );

        // posts | parents : | enfants : reports et likes
        $connexion->query(
            "DELETE FROM `reports` WHERE `user_id`=" . $id . " ;"
        );
        $connexion->query(
            "DELETE FROM `likes` WHERE `user_id`=" . $id . " ;"
        );
        $connexion->query(
            "DELETE FROM `posts` WHERE `user_id`=" . $id . " ;"
        );

        // direct_messages
        $connexion->query(
            "DELETE FROM `direct_messages` WHERE `(from_id`=" . $id . " OR `to_id`=" . $id . ") AND NOT private;"
        );

        /////////////
        // desactiver la page publique (dans users)
        $connexion->query(
            "UPDATE users SET " .
            "enable_public=FALSE, " .
            "memory_public=TRUE  " .
            // etant donné qu'on ne peut pas reroll n'importe quand,
            // un utilisateur de réactiver sa page supprimé
            // du coup je ne supprime pas
            // les données rudimentaires d'une page publique

            " WHERE id=" . $id . " ;"
        );

        /////////////
        // update la session
        if ($currentAccount) {
            $_SESSION["enable_public"] = FALSE;
            $_SESSION["public_name" ] = "";
            $_SESSION["public_image"] = -1;
        }

        mysqli_close($connexion);
        return false;
    }

    // UTILITIES

    function redirectHomeConnect () {
        if ( !$_SESSION["connected"] ) {

            if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
                $url = "https://";   
            else  
                $url = "http://";   
            
            $url = urlencode($url . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);

            header("Location: " . 
                $GLOBALS["global_params"]["root_public"] ."page/public/home_page.php?to_connect&q=" . $url
            );

            exit();

            /* SI BESOIN D'UN POST
            ?>
                <html><header></header><body>
                    <form id="myForm" action="URL.php" method="post">
                    <?php
                        echo '<input type="hidden" name="'.htmlentities(KEY).'" value="'.htmlentities(VALEUR)).'">';
                    ?>
                    </form>
                </body></html>
                <script type="text/javascript">
                    document.getElementById('myForm').submit();
                </script>      
            <?php
            */
        }
    }

    function redirectNotAdmin() {
        if (!isset($_SESSION["admin"]) || !$_SESSION["admin"])
        {
            header("Location: " . $GLOBALS["global_params"]["root_public"] . "page/public/home_page.php");
            exit();
        }
    }

//////////////////////////////////////////////////////////////////////
?>