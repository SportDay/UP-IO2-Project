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
<?php // FUNCTIONS (specific à cette page)

?>
<!-- ------------------------------------------ -->

    <div class = "mid_content">
        <div class="img_btn_like">
            <button class="like btn_button_btn">Like</button>
            <img class="img_profile border" width="256" height="256" src="<?= $global_params["root_public"] . "assets/profile/default_2.png"?>"s>
            <button class="dislike btn_button_btn">Dislike</button>
        </div>
        <div class="info_profile border">
            <span class="profile_nickname">Nom: </span>
            <span class="profile_titre">Titre: </span>
            <span class="profile_espece">Espece: </span>
            <span class="profile_classe">Classe: </span>
        </div>
        <div id="desc" class="border">
            Description
        </div>
    </div>


    <!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/footer.php"); ?>