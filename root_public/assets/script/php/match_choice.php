<?php
    
    $global_params = [
        "root"        => "../../../../",
        "root_public" => "../../../../root_public/",
    ];

    require($global_params["root"] . "assets/script/php/constants.php");
    require($global_params["root"] . "assets/script/php/functions.php");
    
    ////////////////////////////////////////////////////////////////////
    // ETABLISSEMENT DE LA CONNECTION

    session_start();

    if (
        !isset($_POST["like_token_0"]) || !isset($_SESSION["like_token_0"]) ||
              ($_POST["like_token_0"]  !=        $_SESSION["like_token_0"])
        )
    {
        echo json_encode([
            "success" => false,
            "error"   => "token_error"
        ]); exit();
    }

    $connexion = mysqli_connect (
        $db_conf["DB_URL"],
        $db_conf["DB_ACCOUNT"],
        $db_conf["DB_PASSWORD"],
        $db_conf["DB_NAME"]
    );

    if (!$connexion) { 
        // data base error
        echo json_encode([
            "success" => false,
            "error"   => "Base de donnée hors d'accès."
        ]); exit(); 
    }

    ////////////////////////////////////////////////////////////////////

    if ( // faire un like/dislike
        isset($_POST["like_token_1"]) && isset($_SESSION["like_token_1"]) 
        && ($_POST["like_token_1"]==$_SESSION["like_token_1"])
        && isset($_POST["isLike"])
    )
    {
        if ($_POST["isLike"] == "true") {
            
            if ($connexion->query(
                    "SELECT id FROM pages_liked WHERE user_id=".$_SESSION["id"]." AND like_id=".$_SESSION["like_cache_id"]
            )->num_rows == 0)
            {
                $connexion->query(
                    "INSERT INTO pages_liked (user_id, like_id) VALUES (".$_SESSION["id"].", ".$_SESSION["like_cache_id"].")"
                );
                $connexion->query(
                    "UPDATE users SET likes=(likes+1) WHERE id=".$_SESSION["like_cache_id"]." ;"
                );
            }

        }
        else {
            // de base ça devait annuler le potentiel like de l'autre personne
            // mais entant donné qu'on se sert des page_liked aussi pour le système de follow
            // dislike un profile va juste faire qu'on ne le like pas
        }
    }

    // donner une nouvelle personne à like
    $new_user = $connexion->query(
        "SELECT * FROM users WHERE (".
        "    enable_public AND id NOT IN (".
        "        SELECT like_id FROM pages_liked WHERE (user_id=".$_SESSION["id"].")".
        "    )".
        ")".
        "ORDER BY RAND()".
        "LIMIT 1"
    );

    if ($new_user->num_rows != 0) // si on peut trouver un profile
    {
        $new_user = $new_user->fetch_assoc();

        $new_user += [
            "like_token_1" => ($_SESSION["like_token_1"] = randomString())
        ];
        $_SESSION["like_cache_id"] = $new_user["id"];
    }
    else
        $new_user = [
            "public_name"   => "",
            "title"         => "",
            "specie"        => "",
            "class"         => "",
            "public_image"  => 0,//getImagePath(0, false, "", true),
            "description"   => "Personne à l'horizon. Veuillez réessayer plus tard.",
            "like_token_1"  => "none"
        ];
    
    ////////////////////////////////////////////////////////////////////

    echo json_encode([
        "success"       => true,
        "name"          => $new_user["public_name"],
        "title"         => $new_user["title"],
        "specie"        => $new_user["specie"],
        "class"         => $new_user["class"],
        "image"         => getImagePath($new_user["public_image"], false, "", true),
        "desc"          => $new_user["description"],
        "like_token_1"  => $new_user["like_token_1"]
    ]);
    
    mysqli_close($connexion);
    exit();
?>