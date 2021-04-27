<?php $global_params = [
  "root"        => "../../../",
  "root_public" => "../../",
  "title"       => "Amis",
  "css"         => "all.css",
  "css_add"     => [
      "posts.css", "public_page.css","admin.css",
      "friends.css","user_bloc.css","login.css"
    ],
  "redirect"    => TRUE
];?>
<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/functions.php"  ); ?>
<?php require($global_params["root"] . "assets/script/php/header.php"); ?>
<!-- ------------------------------------------ -->

<div id="mid_content">
    
    <!-- Ajout d'amis -->
    <div class="grid">
        <input  id="add_friend_input" placeholder="Pseudo à ajouter">
        <button class="btn_button_btn" onclick="addFriend();">Ajouter en amis</button>
        <p id="add_friend_debug" style="display:none">Debug</p>
    </div>

    <!-- Accepte demande d'amis -->
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
                (SELECT user_id_0 as friend FROM `friends` WHERE (user_id_1=".$_SESSION["id"]." AND NOT accepted))
            ) as t1
            inner join users
            on t1.friend=users.id
            
            ORDER BY last_join DESC
            "
        );

        if ($friends->num_rows!=0)
        { ?>
            <div class="mid_sub_content_friend">
                <h3>Demandes d'amis en attente : </h3>
                <?php while($friend=$friends->fetch_assoc()) add_friend_bloc($friend); ?>
            </div>
        <?php }
    ?>
</div>

<!-- Liste d'amis -->
<div id="mid_content">
<div id="friend_blocs_area">
    <?php
        $friends = $connexion->query( 
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

<?php friend_js_bloc(); add_friend_js_bloc(); ?>
<script>
        function addFriend() {
            let requestFriend = document.getElementById("add_friend_input");
            let debugHtml     = document.getElementById("add_friend_debug");

            let data = new FormData();
            data.append("username", requestFriend.value);
            data.append("add_friend", "<?= $_SESSION["add_friend"] = randomString() ?>");

            let xmlhttp = new XMLHttpRequest();
            xmlhttp.open('POST',
            "<?php echo $GLOBALS["global_params"]["root_public"] ?>assets/script/php/add_friend.php");
            xmlhttp.send( data );

            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState === 4) // request done
                    if (xmlhttp.status === 200) // successful return
                    {
                        //alert(xmlhttp.responseText);
                        const feedback = JSON.parse(xmlhttp.responseText);
                        
                        requestFriend.value = "";
                        debug.style.display="block";
                        debug.innerHtml = feedback["error"];
                    }
            }
        }
</script>

<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/footer.php"); ?>