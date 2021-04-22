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

    require($global_params["root"] . "assets/script/php/security.php");
    require($global_params["root"] . "assets/script/php/modules.php");
?>