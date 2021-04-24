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

<!-- Gestion de page publique -->

<div id = "mid_content">
    <h1 class="settings_title">Publique</h1>
    <div id = "mid_content" class="posts_and_user">
        
        <div class="settings_title"><p>Page publique</p></div>
        
        <?php
        if ($_SESSION["enable_public"]) { ?>
                <button class="btn_valide" onclick="enable_public();"
                >Créer</button>
        <?php } /* else */ { ?>
                <button class="btn_valide" onclick="reroll();"
                >Reroll</button>
                <button class="btn_remove" onclick="remove_public();"
                >Supprimer</button>
        <?php } ?>
    
    </div>
</div>

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
                    

<!-- Gestion Compte privé -->

<div id = "mid_content">
    <div class="settings_title"><p style="color: white;">Privé</p></div>
    <div id = "mid_content" class="posts_and_user">
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



    <div id = "mid_content">
        <div class="settings_title"><p style="color: white; font-size: 24px;">Supprimer le compte</p></div>
        <div style="text-align: center;">
            <dfn title="Ceci est inreversible!">
                <button class="btn_dell_acc btn_button_btn" onclick="document.getElementById('del_account').style.display='block'" style="width: auto; height: 40px; background-color: #f44336;">Supprimer le compte</button>
            </dfn>
        </div>
    </div>
    <div id="del_account"   class="del_account_model">
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


<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/footer.php"); ?>

<script>
    window.onclick = function(event) {
        switch(event.target == document.getElementById('del_account')) {
            document.getElementById('del_account').style.display = "none";
        }
    }
</script>