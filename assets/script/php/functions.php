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

    //////////////////////////////////
    // Fonctions d'action utilisateur
    // exemple : Connection, Deconnection, Verification Connection, etc ...

?>