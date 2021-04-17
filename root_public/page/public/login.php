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

<button onclick="document.getElementById('register').style.display='block'" style="width:auto;">S'inscire</button>
<button onclick="document.getElementById('login').style.display='block'" style="width:auto;">Se connecter</button>


<div id="login" class="modal">
    <form class="modal-content animate" action="/action_page.php" method="post">
        <div class="container">
            <span onclick="document.getElementById('login').style.display='none'" class="close" title="Close Modal">&times;</span>
            <label for="nick" ><b>Nickname</b></label>
            <input type="text" placeholder="Nickname" name="nick" >

            <label for="psw"><b>Mot de passe</b></label>
            <input type="password" placeholder="Mot de passe" name="psw">

            <button type="submit">Se connecter</button>
            <label >
                <input type="checkbox" checked="checked" name="remember" > Se souvenir de moi?
            </label>
        </div>

        <div class="container">
            <button type="button" onclick="document.getElementById('login').style.display='none'" class="cancelbtn">Annuler</button>
        </div>
    </form>
</div>

<div id="register" class="modal">
    <form class="modal-content animate" action="/action_page.php" method="post">
            <div class="container">
                <span onclick="document.getElementById('login').style.display='none'" class="close" title="Close Modal">&times;</span>
                <label for="nick" ><b>Nickname</b></label>
                <input type="text" placeholder="Nickname" name="nick" >

                <label for="psw" ><b>Mot de passe</b></label>
                <input type="password" placeholder="Mot de passe" name="psw" >

                <label for="secret" ><b>Cle secrete (permet de modifier le mot de passe)</b></label>
                <input type="password" placeholder="Cle secrete" name="secret" >

                <button type="submit">S'inscrire</button>
            </div>

            <div class="container" >
                <button type="button" onclick="document.getElementById('register').style.display='none'" class="cancelbtn">Annuler</button>
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
