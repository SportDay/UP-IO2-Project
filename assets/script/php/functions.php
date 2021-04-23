<?php

    ////////////////////////
    // Fonctions de mise en page

    function write($text){
        echo "<p>$text</p>";
    }

    function separator($length = 15, $char = '-'){
        echo "<p>";
        for ($i = 0; $i < $length; $i++)
            echo $char;
        echo "</p>";
    }

    function padding($length = 10) {
        echo "<p>";
        for ($i = 0; $i < $length; $i++)
            echo "<br>";
        echo "</p>";
    }

    function newline_split($str) {
        // utilise ça si tu dois split par ligne, trop de problème d'encodage sinon
        return preg_split("/\r\n|\n|\r/", $str);
    }

    function consoleLog($succes = null, $code = null){
        if($code != null && $succes != null){
            $name = "HRP";
            if($succes === true){
                ?>
                    <script>console.log("<? echo $name . ": $code!";?>");</script>
                <?php
            }else if ($succes === false){
                 ?>
                    <script>console.log("<? echo $name . " ERROR: ". " $code!";?>");</script>
                <?php
            }
        }
    }

    function debug($content) {
        if (isset($GLOBALS["debugging"]) && $GLOBALS["debugging"])
            file_put_contents('debug.txt', ($content).PHP_EOL , FILE_APPEND | LOCK_EX);
    }

    //////////////////
    // FONCTIONS DE SECURITE (sans effets de bords)

    function isValideName($str) {
        // longueur et charactère
        if (strlen($str) > 16 || strlen($str) < 2) 
            return false;
        
        return !(preg_match("/^[\w]*$/", $str) === 0); // alphanumérique et tiret underscore
    }

    function isValidePassword ($str) {
        // longueur et charactères
        if (strlen($str) > 26 || strlen($str) < 6) return false;

        return !(preg_match("/^[A-z!?\-\*\+\(\)\|\[\]@0-9]*$/" , $str) === 0); // alphanumérique et -_+()[]
        return // version qui force l'utilisateur à compliquer le mdp
            !(preg_match("/^[A-z!?\-\*\+\(\)\|\[\]@0-9]*$/" , $str) === 0) &&
            !(preg_match("/[A-z]/"                          , $str) === 0) &&
            !(preg_match("/[0-9]/"                          , $str) === 0) &&
            !(preg_match("/!?\-\*\+\(\)\|\[\]@/"            , $str) === 0) ;
    }

    function hashPassword ($pass, $row) {
        // concatener le mot de passe avec des informations de la base de donnée
        // utiliser le hash de php
        $pass .= $row["id"] . $row["creation_date"] . $pass . $row["username"]; // SALT
        return md5($pass); // changer ça en SHA
        //return password_hash($pass, PASSWORD_DEFAULT);
    }

    function randomString($length=20) { // mettre en 80 par défaut ??? var(128)
        //return bin2hex(random_bytes($length)); // faudrait peut être passer en base64??
        return base64_encode(random_bytes($length)) ;/* str_replace( 
            ["=" , "/"], ["-", "_"],
            base64_encode(random_bytes($length))
        );*/
    }

    function getImagePath($image) {
        $folder = $GLOBALS["global_params"]["root_public"] . "assets/profile/";
        $path   = $folder . "profile_" . $image . ".jpg";

        if (file_exists($path))
            return $path;

        return $folder . "default.png";
    }

?>