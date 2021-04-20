<?php $global_params = [
  "root"        => "../../../",
  "root_public" => "../../",
  "title"       => "Coeur de poudlard",
  "css"         => "all.css",
  "css_add"     => ["posts.css"],
  "redirect"    => TRUE
];?>
<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/functions.php"  ); ?>
<?php require($global_params["root"] . "assets/script/php/header.php"); ?>
<!-- ------------------------------------------ -->
<?php // FUNCTIONS (specific à cette page)

?>
<!-- ------------------------------------------ -->

    <div style="text-align: center; margin: 7em auto 1em;">
        <div id="search_container">
            <form action="/search.php" method="get">
                <input id="search_input" type="search" autocomplete="off" placeholder="Recherche">
            </form>
        </div>
    </div>
    <div id = "mid_content" style="margin-top: 0px; text-align: initial; padding-top: 0px">
        <div id="container_add">
            <form id="form_post_add" action="/add_post.php" method="post">
                <textarea id="post_add" name="post_content" form="form_post_add" placeholder="Quel serait votre nouveau post?" rows="5" maxlength="735"></textarea><br>
                <button id="submit_add" type="submit">Poster</button>
            </form>
        </div>
    </div>
    <div id = "mid_content" style="margin-top: 0px; text-align: initial;">
        <div class="posts">
            <a href="/UP-IO2-Project/root_public/page/public/public_page.php?id=">
                <img class="profile_img_posts" src="<?= $global_params["root"] . "assets/profile/default.png" ?>">
            </a>
            <div class="info_containt border" style="border-radius: 15px; padding: 10px 10px;">
                <a href="/UP-IO2-Project/root_public/page/public/public_page.php?id=">
                    <span class="post_auteur" style="color: white; font-size: 20px">Test Test</span><br>
                    <span class="post_date" style="color: lightgray; font-size: 14px">19/04/2021 19:24</span>
                </a>
                <div class="post_menu">
                    <button class="btn_menu_post">&#8226;&#8226;&#8226;</button>
                    <div class="supp_post border">
                        <form action="/supp_post.php" method="post">
                            <input type="hidden" name="sup_post" value="post_id">
                            <button class="btn_sup_post" type="submit">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="post_content border">
                <p style="color: white; font-size: 18px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi aliquet fermentum odio. Nulla sed venenatis nulla. Pellentesque interdum ligula ac venenatis mattis. Nam nec lectus urna. Vestibulum finibus tellus a auctor feugiat. Morbi vel cursus orci, eu efficitur nisl. Vivamus congue mi sed metus condimentum aliquet. Aliquam tempus ante vel viverra vulputate. Phasellus eros lorem, imperdiet in ante vel, malesuada viverra orci. Curabitur laoreet porta quam nec rhoncus. Donec aliquet dui in rhoncus eleifend.

                    Donec eleifend elementum bibendum. Quisque porta, lacus eget vehicula aliquam, augue ante dignissim lectus, eu porta neque magna sit amet odio. Morbi gravida quam a libero blandit, nec laoreet tortor finibus. In facilisis augue sed ante interdum, nec consequat arcu feugiat. Morbi sagittis justo non ligula luctus imperdiet. Integer ultrices diam vel venenatis sodales. Praesent nisl est, vulputate ut viverra quis, rhoncus et libero.</p>
            </div>
            <a href="#" class="btn_like">
                <img class="like_img" width="32" height="32" src="<?= $global_params["root_public"] . "assets/image/like.png"?>"><span class="like_num"">0</span>
            </a>
            <div class="espace" style="grid-area: espace;"></div>
            <dfn title="Voulez-vous signaler?">
                <div class="btn_report">
                    <a href="#" class="report_ref">
                        <img class="report_img" width="32" height="32" src="<?= $global_params["root_public"] . "assets/image/report.png"?>">
                    </a>
                </div>
            </dfn>
        </div>
    </div>

<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/footer.php"); ?>