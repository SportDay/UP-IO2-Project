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
        
        <?php if ($_SESSION["enable_public"]) { ?>
                <button class="btn_valide" onclick="reroll();"
                >Reroll</button>
                
                <button class="btn_remove" onclick="remove_public();"
                >Supprimer</button>
        <?php } else { ?>
                <button class="btn_valide" onclick="enable_public();"
                >Créer</button>
        <?php } ?>
    
    </div>
</div>

<!-- Gestion Compte privé -->

<div id = "mid_content"> <!-- Passwords -->
    <h1 class="settings_title">Privé | <?= $_SESSION["username"] ?> </h1>
    
    <div id = "mid_content">
        <div class="settings_title"><p>Changement du mot de passe</p></div>
        
        <div class="input_title">
            <input type="password" placeholder="Ancien mot de passe"> <br>
            <input type="password" placeholder="Nouveau mot de passe"><br>
            <input type="password" placeholder="Repetez le mot de passe"><br>
        </div>
        <br>
        <button class="btn_chg_pass btn_button_btn" onclick="changePassword()"
            >Changer le mot de passe</button>
    </div>

    <div id = "mid_content">
        
        <div class="settings_title"><p>Supprimer le compte</p></div>
        
        <div class="input_title">
            <input type="text"     placeholder="Pseudo"> <br>
            <input type="password" placeholder="Mot de passe"><br>
            <input type="password" placeholder="Repetez le mot de passe"><br>
        </div>
        <br>
        <button class="btn_remove" onclick="removeAccount();" 
                >Supprimer</button>
        

    </div>

</div>


<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/footer.php"); ?>

<script>
    
    function enable_public() {

    }

    function reroll() {

    }

    function remove_public() {

    }

    function changePassword() {

    }

    function removeAccount() {

    }

</script>