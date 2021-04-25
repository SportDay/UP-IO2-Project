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
        return hash('sha512', hash('md5', $pass, false) . $pass, false); 

        // NOT SAFE : hash1(hash2(hash3(...hashn(pass+salt)+salt)+salt)...)+salt)
        // SAFE     : hash1(hash2(hash3(...hashn(pass + salt) + pass + salt) + pass + salt)...) + pass + salt)
        // je me base sur les conseils de cette page :
        // https://softwareengineering.stackexchange.com/questions/115406/is-it-more-secure-to-hash-a-password-multiple-times
        
        //return md5($pass); // changer ça en SHA
        //return password_hash($pass, PASSWORD_DEFAULT);
    }

    function randomString($length=20) { // mettre en 80 par défaut ??? var(128)
        //return bin2hex(random_bytes($length)); // faudrait peut être passer en base64??
        return base64_encode(random_bytes($length)) ;
        /* str_replace( 
            ["=" , "/"], ["-", "_"],
            base64_encode(random_bytes($length))
        );*/
    }

    function getImagePath($image) {
        $folder = $GLOBALS["global_params"]["root_public"] . "assets/profile/";

        $path = "";

        if ($image < 36)
            $path = $folder . "profile_" . str_pad($image, 4, "0", STR_PAD_LEFT) . ".webp";
        else
            $path = $folder . "profile_" . str_pad($image, 4, "0", STR_PAD_LEFT) . ".jpg";        

        if (file_exists($path))
            return $path;

        return $folder . "default.png";
    }

    //////////////////////////////////
    // FONCTIONS RELATIVES AU LORE

    function generateRandomPublicData() {
        $roles = json_decode( file_get_contents(
            $GLOBALS["global_params"]["root_public"] . "assets/rp_data/procedural_role.json"
        , true) );

        //write("");
        //return $roles->{"public_name"}->{"FirstName"};

        // GENERATE NAME
        $firstname   = $roles->{"public_name"}->{"FirstName"}
        [rand(0, count($roles->{"public_name"}->{"FirstName"})-1 )];
        
        $lastname    = $roles->{"public_name"}->{"LastName" }
        [rand(0, count($roles->{"public_name"}->{"LastName" })-1 )];

        $public_name  = $firstname . " " . $lastname;

        // GENERATE SPECIE
        $specie =      $roles->{"specie"}
        [rand(0, count($roles->{"specie"})-1 )];

        // GENERATE TITLE
        $title = $roles->{"title"}
        [rand(0, count($roles->{"title"})-1 )];

        // GENERATE IMAGE
        $public_image = rand(
            $specie->{"images"}[0][0], 
            $specie->{"images"}[0][1]
        );

        for ($i = 0; $i < 100; $i++) { // euristic
            // GENERATE CLASS
            $class = $roles->{"class"}[ 
                rand(   0, 
                        count( $roles->{"class"}) - 1
                    )
            ];

            if (
                    ($class->{"images"}[0][0] <= $public_image && $public_image <= $class->{"images"}[0][1]) 
                    || $i == 99 
                ) 
            {
                return [
                    "public_name"   => $public_name,
                    "public_image"  => $public_image,
        
                    "specie"        => $specie->{"name"},
                    "class"         => $class->{"name"},
                    "title"         => $title,

                    "success"       => true
                ];
            }
        }
    }

?>