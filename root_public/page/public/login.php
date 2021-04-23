<?php $global_params = [
  "root"        => "../../../",
  "root_public" => "../../",
  "title"       => "Connection",
  "css"         => "all.css",
  "css_add"     => ["login.css"],
  "redirect"    => FALSE
];?>
<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/functions.php"  ); ?>
<?php require($global_params["root"] . "assets/script/php/header.php"); ?>
<!-- ------------------------------------------ -->
<?php // FUNCTIONS (specific à cette page)

?>
<!-- ------------------------------------------ -->
<div id = "mid_content">
<button onclick="document.getElementById('register').style.display='block'" style="width:auto;">S'inscire</button>
<button onclick="document.getElementById('login').style.display='block'" style="width:auto;">Se connecter</button>


<div id="login" class="reg_log_model">
    <form class="modal-content animate" action="/action_page.php" method="post">
        <div class="reg_log_form_container">
            <span onclick="document.getElementById('login').style.display='none'" class="close" title="Fermer">&times;</span>
            <label class="form_title" for="nickname"><b>Nickname</b></label>
            <input type="text" placeholder="Nickname" name="nickname" >

            <label class="form_title" for="password"><b>Mot de passe</b></label>
            <input type="password" placeholder="Mot de passe" name="password">

            <button type="submit">Se connecter</button>
            <label style="color:white;">Se souvenir de moi?
                <input type="checkbox" checked="checked" name="remember" >
            </label>
            <br>
            <button type="button" onclick="document.getElementById('login').style.display='none'" class="cancelbtn">Annuler</button>
        </div>
    </form>
</div>

<div id="register" class="reg_log_model">
    <form class="modal-content animate" action="/action_page.php" method="post">
            <div class="reg_log_form_container">
                <span onclick="document.getElementById('register').style.display='none'" class="close" title="Fermer">&times;</span>
                <label class="form_title" for="nickname"><b>Nickname</b></label>
                <input type="text" placeholder="Nickname" name="nickname" >

                <label class="form_title" for="password"><b>Mot de passe</b></label>
                <input type="password" placeholder="Mot de passe" name="password">

                <button type="submit">S'inscrire</button>

                <button type="button" onclick="document.getElementById('register').style.display='none'" class="cancelbtn">Annuler</button>
            </div>
  </div>
</div>
  <!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/footer.php"); ?>

<script>
    // Get the modal
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == document.getElementById('login')) {
            document.getElementById('login').style.display = "none";
        }
        if (event.target == document.getElementById('register')) {
            document.getElementById('register').style.display = "none";
        }
    }
</script>
