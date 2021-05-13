<?php $global_params = [
  "root"        => "../../../",
  "root_public" => "../../",
  "title"       => "Admin Panel",
  "css"         => "all.css",
  "css_add"     => ["public_page.css","posts.css","admin.css"],
  "redirect"    => TRUE,
  "admin_req"   => TRUE
];?>

<?php
    // INCLURE UN CODE DE REDIRECTION SI COMPTE NON ADMIN
    // if (!not_admin)
    //    header(accueil)
?>

<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/functions.php"  ); ?>
<?php require($global_params["root"] . "assets/script/php/header.php"); ?>
<!-- ------------------------------------------ -->
<?php // FUNCTIONS (specific à cette page)
$connexion = mysqli_connect (
    $db_conf["DB_URL"],
    $db_conf["DB_ACCOUNT"],
    $db_conf["DB_PASSWORD"],
    $db_conf["DB_NAME"]
);

if (!$connexion) {
    echo "connection_error"; exit();
}
/*
if(isset($_SESSION["admin"]) && $_SESSION["admin"] == "0"){
    ?>
    <script>window.location.href = "<?=$global_params["root_public"] . "/page/public/home_page.php"?>";</script>
    <?php
}*/


$reported_post = $connexion->query("SELECT * FROM posts WHERE reported=\"1\" ORDER BY reportnum DESC;");

if ($reported_post->num_rows==0)
{ ?>
    <div class="mid_content">
        <p>C'est vide par ici.</p>
    </div>
<?php }

while($report_post=$reported_post->fetch_assoc()) {

    $like_query =
        "SELECT id FROM likes WHERE ".
        " user_id=\"" . $connexion->real_escape_string($_SESSION["id"]) .
        "\" AND message_id=\"" . $connexion->real_escape_string($report_post["id"]) . "\";";
    $like = $connexion->query($like_query)->num_rows != 0;

    $report_query =
        "SELECT id FROM reports WHERE ".
        " user_id=\"" . $connexion->real_escape_string($_SESSION["id"]) .
        "\" AND message_id=\"" . $connexion->real_escape_string($report_post["id"]) . "\";";
    $reported = $connexion->query($report_query)->num_rows != 0;

    post_reported_bloc($report_post, $like, $reported);
}

mysqli_close($connexion);

?>
<!-- ------------------------------------------ -->
    <div id="tmp_ban" class="tmp_ban_model">
        <div class="modal-content animate">
            <div class="tmp_ban_form_container">
                <span onclick="hideTempBanBlock();" class="close" title="Fermer">&times;</span>
                <label class="form_title"><b>Ban temporaire</b></label>
                <input id="time_input" type="number" placeholder="Durée de la punition" name="ban_time" min="0" autocomplete="off">
                <div class="ban_radio">
                    <input label="Minute" type="radio" id="male" name="time" value="min">
                    <input label="Heure" type="radio" id="female" name="time" value="hour" checked>
                    <input label="Jour" type="radio" id="other" name="time" value="day">
                    <input label="Mois" type="radio" id="other" name="time" value="month">
                </div>
                <button id="ban_btn" class="ban_user_temp" onclick="">Bannir</button>
                <button id="cancel_ban" class="ban_user_temp" onclick="hideTempBanBlock();" class="cancelbtn">Annuler</button>
            </div>
        </div>
    </div>
<!-- ------------------------------------------ -->

<?php
post_reported_js_bloc();
?>
<?php require($global_params["root"] . "assets/script/php/footer.php"); ?>