      <?php $global_params = [
  "root"        => "../../../",
  "root_public" => "../../",
  "title"       => "Amis",
  "css"         => "all.css",
  "css_add"     => ["posts.css", "public_page.css","admin.css","friends.css"],
  "redirect"    => TRUE
];?>
<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/functions.php"  ); ?>
<?php require($global_params["root"] . "assets/script/php/header.php"); ?>
<!-- ------------------------------------------ -->
<?php // FUNCTIONS (specific à cette page)

?>
<!-- ------------------------------------------ -->

      <div style="text-align: center; margin-bottom: 1em;">
          <div id="search_container">
              <form action="/search_ami.php" method="get">
                  <input id="search_input" type="search" autocomplete="off" placeholder="Recherche">
              </form>
          </div>
      </div>
      <div id = "mid_content" class="posts_and_user" style="text-align: initial;">
          <div id = "profile">
              <a href="/UP-IO2-Project/root_public/page/public/public_page.php?id=">
                  <img class="profile_img_profile" src="<?= $global_params["root"] . "assets/profile/default.png" ?>">
              </a>
              <div class="info_profile">
                  <span class="profile_nickname" style="color: white; font-size: 24px">Nom: </span>
                  <span class="profile_titre" style="color: white; font-size: 24px">Titre: </span>
                  <span class="profile_espece" style="color: white; font-size: 24px">Espece: </span>
                  <span class="profile_classe" style="color: white; font-size: 24px">Classe: </span>
                  <span class="profile_nlikes" style="color: white; font-size: 24px">Likes: </span>
                  <div class="user_menu">
                      <button class="btn_menu_user">&#8226;&#8226;&#8226;</button>
                      <div class="user_menu_content border">
                          <form action="/supp_friend.php" method="post">
                              <input type="hidden" name="supp_friend" value="user_id">
                              <button class="btn_ignr_user" type="submit">Supprimer</button>
                          </form>
                      </div>
                  </div>
                  <div class="espace2"></div>
                  <a href="dm.php?id=">
                    <img class="msg_img" width="32" height="32" src="../../assets/image/msg.png">
                  </a>
              </div>
          </div>
      </div>


<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/footer.php"); ?>