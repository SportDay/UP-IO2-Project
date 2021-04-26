<?php $global_params = [
  "root"        => "../../../",
  "root_public" => "../../",
  "title"       => "Amis",
  "css"         => "all.css",
  "css_add"     => [
      "posts.css", "public_page.css","admin.css",
      "friends.css","user_bloc.css"
    ],
  "redirect"    => TRUE
];?>
<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/functions.php"  ); ?>
<?php require($global_params["root"] . "assets/script/php/header.php"); ?>
<!-- ------------------------------------------ -->

<div style="text-align: center; margin-bottom: 1em;">
    <div id="search_container">
        <form action="/search_ami.php" method="get">
            <input id="search_input" type="search" autocomplete="off" placeholder="Recherche">
        </form>
    </div>
</div>

<div id="mid_content">
    <p> friend accept/refuse</p>
</div>

<div id="mid_content">
<div id="friend_blocs_area">
    <?php

        $connexion = mysqli_connect (
            $GLOBALS["DB_URL"],
            $GLOBALS["DB_ACCOUNT"],
            $GLOBALS["DB_PASSWORD"],
            $GLOBALS["DB_NAME"]
        );

        if (!$connexion) { 
            echo "connection_error"; exit(); 
        }

        $friends = $connexion->query( // enfin ça fonctionne !!
            "select *
            from
            (
                (SELECT user_id_1 as friend FROM `friends` WHERE (user_id_0=".$_SESSION["id"]." AND accepted))
                    UNION 
                (SELECT user_id_0 as friend FROM `friends` WHERE (user_id_1=".$_SESSION["id"]." AND accepted))
            ) as t1
            inner join users
            on t1.friend=users.id
            
            ORDER BY last_join DESC
            "
        );

        while($friend=$friends->fetch_assoc())
            friend_bloc($friend);

        mysqli_close($connexion);
    ?>
</div>
</div>

<?php friend_js_bloc(); ?>
<script>
    function addFriend() {

    }

    function acceptFriend() {
        let friendBlocs = document.getElementById("friend_blocs_area");



        friendBloc.prepend(
            "<p>utiliser la fonction friend_bloc() de php</p>"
        );
    }

    function refuseFriend() {

    }
</script>

<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/footer.php"); ?>