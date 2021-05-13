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
        isset($_POST["like_token_1"]) && ($_POST["like_token_1"]==$_SESSION["like_token_1"])
        && isset($_POST["isLike"])
    )
    {
        if ($_POST["isLike"] == "true") {
            
        }
        else {

        }
    }

    // donner une nouvelle personne à like
    $newUser = $connexion->multi_query("
    SELECT * FROM users WHERE (
        enable_public AND id NOT IN (
            SELECT like_id FROM pages_liked WHERE (user_id=".$_SESSION["id"].")
        )
    )
    ORDER BY RAND()
    LIMIT 1
    ");

    if ($new_user->num_rows != 0) // si on peut trouver un profile
    {
        $new_user = $new_user->fetch_assoc();

        $_SESSION["like_token_1"]  = randomString();
        $_SESSION["like_cache_id"] = $new_user["id"];
    }
    else
        $new_user = [
            "name"          => "",
            "title"         => "",
            "specie"        => "",
            "class"         => "",
            "image"         => getImagePath(0, false, "", true),
            "desc"          => "Personne à l'horizon. Veuillez réessayer plus tard.",
            "like_token_1"  => "none"
        ];
    
    ////////////////////////////////////////////////////////////////////

    echo json_encode([
        "success"     => true
    ]);
    
    mysqli_close($connexion);
    exit();
?>