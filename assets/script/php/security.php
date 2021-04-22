<?php

    function simple_disconnect () {
        session_unset();

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
            { // CONNECT
                $_SESSION["inactive_time"] = min(
                    $_SESSION["max_time"], 
                    time() + $TIME_SESS_INACTIVE
                );

                $_SESSION["last_time"] = time();
                mysqli_query($connexion, 
                    "UPDATE users SET last_join=" . $_SESSION["last_time"] 
                    . " WHERE `id`=" . $_SESSION["id"]
                );

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
            "SELECT * FROM users WHERE cookie_id=". $connexion->real_escape_string($_COOKIE["cookie_id"]) . ";"
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

        $_SESSION["init_time"]      = time();
        $_SESSION["last_time"]      = time();
        $_SESSION["inactive_time"]  = time() + $TIME_SESS_INACTIVE;
        $_SESSION["max_time"]       = time() + $TIME_SESS_END;

        $_SESSION["connected"]      = true;
        
        mysqli_close($connexion);
    }

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
?>