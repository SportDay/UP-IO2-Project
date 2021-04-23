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


    function consoleLog($succes = null, $code = null){
        if($code != null && $succes != null){
            $name = "HRP";
            if($succes === true){
                ?>
                    <script>console.log("<? echo $name . ": $code!";?>");</script>
                <?php
            }else if ($succes === false){
                 ?>
                    <script>console.log("<?echo $name . " ERROR: ". " $code!";?>");</script>
                <?php
            }
        }
    }

?>