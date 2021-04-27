<?php $global_params = [
  "root"        => "../../../",
  "root_public" => "../../",
  "title"       => "Messages Directs",
  "css"         => "all.css",
  "css_add"     => ["posts.css", "public_page.css", "dm.css"],
  "redirect"    => TRUE
];?>
<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/functions.php"  ); ?>
<?php require($global_params["root"] . "assets/script/php/header.php"); ?>
<!-- ------------------------------------------ -->
<?php // verification amitié

    $friend = $_GET["user"];

    if (true) 
    {
        require($global_params["root"] . "assets/script/php/footer.php");
        exit();
    }
?>
<!-- ------------------------------------------ -->

    <div id="mid_container_mid">
    <div id = "mid_content" class="container_message" style="margin-top: 0; margin-bottom: 20px; text-align: initial; height: 100%;">
        <div class="pofile_container_dm">
            <a href="/UP-IO2-Project/root_public/page/public/public_page.php?id=">
                <img class="profile_img_posts" src="<?= $global_params["root"] . "assets/profile/default.png" ?>">
            </a>
            <div class="info_containt border" style="border-radius: 15px; padding: 10px 10px;">
                <a href="/UP-IO2-Project/root_public/page/public/public_page.php?id=">
                    <span class="post_auteur" style="color: white; font-size: 32px">Test Test</span><br>
                </a>
            </div>
        </div>
        <div class="all_message_container border">
            <div class="message_container">
                <p class="date" style="color: white; font-size: 14px">Test Test<br>23:06<br>21/04/2021</p>
                <p class="message" style="color: white; font-size: 16px">test</p>
            </div>
            <div class="message_container">
                <p class="date" style="color: white; font-size: 14px">Me Me<br>23:06<br>21/04/2021</p>
                <p class="message" style="color: white; font-size: 16px">test </p>
            </div>
        </div>
        <form action="send_msg.php" method="get" id="msg_form">
                <div class="send_container">
                    <textarea id="msg_send_content" name="message" form="msg_form" placeholder="Votre Message" rows="3"></textarea>
                    <button class="btn_send btn_button_btn" type="submit">
                        <img height="32" width="32" src="../../assets/image/send.png">
                    </button>
                </div>
        </form>
    </div>
    </div>
    <div style="text-align: center; margin: 1em auto 4em;"></div>
    <!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/footer.php"); ?>