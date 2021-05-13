<?php $global_params = [
  "root"        => "../../../",
  "root_public" => "../../",
  "title"       => "Rencontres",
  "css"         => "all.css",
  "css_add"     => ["admin.css","friends.css","like.css"],
  "redirect"    => TRUE
];?>
<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/functions.php"  ); ?>
<?php require($global_params["root"] . "assets/script/php/header.php"); ?>
<!-- ------------------------------------------ -->

    <div class = "mid_content">
        <div class="img_btn_like">
            <button class="like btn_button_btn" onclick="matchChoice(true)" 
            >Like</button>
            
            <img
                id = "image_profile"
                class="img_profile border" 
                width="256" height="256" 
                src="<?= $global_params["root_public"] . "assets/profile/default.png"?>" >
            
            <button class="dislike btn_button_btn" onclick="matchChoice(false)" 
            >Dislike</button>
        
        </div>

        <div class="info_profile border">
            <span id = "name"   class="profile_nickname">Nom: </span>
            <span id = "title"  class="profile_titre"   >Titre: </span>
            <span id = "specie" class="profile_espece"  >Espece: </span>
            <span id = "class"  class="profile_classe"  >Classe: </span>
        </div>
        
        <div id="desc" class="border">
            Description
        </div>
    </div>

    <script>

        // init
        var pageToken   = <?=json_encode($_SESSION["like_token_0"] = randomString())?>;
        var likeToken   = "none";

        var otp_image   = document.getElementById("image_profile");
        var otp_name    = document.getElementById("name");
        var otp_title   = document.getElementById("title");
        var otp_specie  = document.getElementById("specie");
        var otp_class   = document.getElementById("class");
        var otp_desc    = document.getElementById("desc");

        matchChoice(false);
        
        //////////////////////////

        function matchChoice(isLike) {
             


        }

    </script>

<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/footer.php"); ?>