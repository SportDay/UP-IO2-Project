<?php


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" type="text/css" href="../assets/reg-login.css">
    <link rel="icon" type="image/jpg" href="../assets/image/logo.jpg" />
</head>
<body>
<header>
    <div id="up_panel"></div>
    <img class="logo" src="../assets/image/logo.jpg">
</header>
<h2>Modal Login Form</h2>

<button onclick="document.getElementById('register').style.display='block'" style="width:auto;">S'inscire</button>
<button onclick="document.getElementById('login').style.display='block'" style="width:auto;">Se connecter</button>


<div id="login" class="modal">
    <form class="modal-content animate" action="/action_page.php" method="post">
        <div class="container">
            <span onclick="document.getElementById('login').style.display='none'" class="close" title="Close Modal">&times;</span>
            <label for="nick" style="color: white;"><b>Nickname</b></label>
            <input type="text" placeholder="Nickname" name="nick" required style="background-color: #43505f; color:white;">

            <label for="psw" style="color: white;"><b>Mot de passe</b></label>
            <input type="password" placeholder="Mot de passe" name="psw" required style="background-color: #43505f; color:white;">

            <button type="submit">Se connecter</button>
            <label style="color: white;">
                <input type="checkbox" checked="checked" name="remember" > Se souvenir de moi?
            </label>
        </div>

        <div class="container" style="background-color:#43505f">
            <button type="button" onclick="document.getElementById('login').style.display='none'" class="cancelbtn">Annuler</button>
            <span class="psw"><a href="lostpass.php" style="text-decoration: none; color:white;">Mot de passe oublier?</a></span>
        </div>
    </form>
</div>

<div id="register" class="modal">
    <form class="modal-content animate" action="/action_page.php" method="post">
            <div class="container">
                <span onclick="document.getElementById('login').style.display='none'" class="close" title="Close Modal">&times;</span>
                <label for="nick" style="color: white;"><b>Nickname</b></label>
                <input type="text" placeholder="Nickname" name="nick" required style="background-color: #43505f; color:white;">

                <label for="psw" style="color: white;"><b>Mot de passe</b></label>
                <input type="password" placeholder="Mot de passe" name="psw" required style="background-color: #43505f; color:white;">

                <label for="secret" style="color: white;"><b>Cle secrete (permet de modifier le mot de passe)</b></label>
                <input type="password" placeholder="Cle secrete" name="secret" required style="background-color: #43505f; color:white;">

                <button type="submit">S'inscrire</button>
            </div>

            <div class="container" style="background-color:#43505f">
                <button type="button" onclick="document.getElementById('register').style.display='none'" class="cancelbtn">Annuler</button>
            </div>
    </form>
</div>
<footer>
    <div id="down_panel"></div>
</footer>
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
</body>
</html>