<?php $global_params = [
  "root"        => "../../../",
  "root_public" => "../../",
  "title"       => "Paramètres",
  "css"         => "all.css",
  "css_add"     => ["params.css"],
  "redirect"    => TRUE
];?>
<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/functions.php"  ); ?>
<?php require($global_params["root"] . "assets/script/php/header.php"); ?>
<!-- ------------------------------------------ -->
<?php // FUNCTIONS (specific à cette page)

?>
<!-- ------------------------------------------ -->

    <div id = "mid_content" style="width: 50%; min-width: 400px;">
        <div class="settings_title"><p style="color: white;">Publique</p></div>
        <div id = "mid_content" class="posts_and_user" style="min-width: 350px; margin: 0px 20px; border-radius: 15px; text-align: initial; width: auto;">
            <div class="settings_title"><p style="color: white; font-size: 24px;">Page publique</p></div>
            <div class="btn_grid">
                <div class="btn_public">
                <?php
                    if("pub" === "pub"){
                ?>
                <form action="/public_sett.php" method="post">
                    <input type="hidden" name="create" value="user_id">
                    <button class="btn_button_btn" type="submit" style="padding: 10px 10px; width: auto; background-color: limegreen;">Créer</button>
                </form>
                <?php
                    }else{
                ?>
                <form  action="/public_sett.php" method="post">
                    <input type="hidden" name="delete" value="user_id">
                    <button class="btn_button_btn" type="submit" style="padding: 10px 10px; width: auto; background-color: #E14236;">Supprimer</button>
                </form>
                <?php
                    }
                ?>
                </div>
                <div class="btn_public_reroll">
                    <form action="/public_sett.php" method="post">
                        <input type="hidden" name="create" value="user_id">
                        <button class="btn_button_btn" type="submit" style="padding: 10px 10px; width: auto;" <?php if("not activate"){ echo "disabled";} ?> >Reroll</button>
                    </form>
                </div>
            </div>
        </div>
        <div id = "mid_content" class="posts_and_user" style="min-width: 350px; margin: 20px 20px; border-radius: 15px; text-align: initial; width: auto;">
            <div class="settings_title"><p style="color: white; font-size: 24px;">Posts</p></div>
            <div style="text-align: center;">
                <form action="/auto_post.php" method="post">
                    <button class="btn_chg_pass btn_button_btn" type="submit" style="width: auto;">Générer un post</button>
                </form>
            </div>
        </div>
    </div>
    <div id = "mid_content" style="width: 50%; margin-top: 20px; min-width: 400px;">
        <div class="settings_title"><p style="color: white;">Privé</p></div>
        <div id = "mid_content" class="posts_and_user" style="min-width: 350px; margin: 0px 20px; border-radius: 15px; text-align: initial; width: auto;">
            <div class="settings_title"><p style="color: white; font-size: 24px;">Changement du mot de passe</p></div>
            <div style="text-align: center;">
            <form action="/chng_password.php" method="post">
                <div class="place_grid">
                <input class="input_title"  type="password" name="old_pass" placeholder="Ancien mot de passe" required>
                <br>
                <input class="input_title" type="password" name="new_pass1" placeholder="Nouveau mot de passe" required>
                <br>
                <input class="input_title"  type="password" name="new_pass2" placeholder="Repetez le mot de passe" required>
                </div>
                <button class="btn_chg_pass btn_button_btn" type="submit" style="width: auto;">Changer le mot de passe</button>
            </form>
            </div>
        </div>
        <div id = "mid_content" class="posts_and_user" style="min-width: 350px; margin-top: 20px; margin-bottom: 0; margin-left: 20px; margin-right: 20px; border-radius: 15px; text-align: initial; width: auto;">
            <div class="settings_title"><p style="color: white; font-size: 24px;">Supprimer le compte</p></div>
            <div style="text-align: center;">
                <dfn title="Ceci est inreversible!">
                    <button class="btn_dell_acc btn_button_btn" onclick="document.getElementById('del_account').style.display='block'" style="width: auto; height: 40px; background-color: #f44336;">Supprimer le compte</button>
                </dfn>
            </div>
        </div>
        <div id="del_account" class="del_account_model">
            <form class="modal-content" action="/dell_acc.php" method="post">
                <div class="del_account_form_container">
                    <span onclick="document.getElementById('del_account').style.display='none'" class="close" title="Fermer">&times;</span>
                    <label class="form_title" for="del_acc"><b>Veuilez sasir votre <br>nickname pour confirmer</b></label>
                    <input type="hidden" name="del_acc" value="user_name">
                    <input type="text" placeholder="user_name" name="confirm_code" required>
                    <dfn title="Ceci est inreversible!">
                        <button class="btn_dell btn_button_btn" style="width: auto; background-color: #FFC38A;" type="submit">Supprimer</button>
                    </dfn>
                    <button type="button" onclick="document.getElementById('del_account').style.display='none'" class="cancelbtn btn_button_btn">Annuler</button>
                </div>
        </div>
    </div>

    <script>
        window.onclick = function(event) {
            if (event.target == document.getElementById('del_account')) {
                document.getElementById('del_account').style.display = "none";
            }
        }
    </script>


<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/footer.php"); ?>