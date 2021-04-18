<?php $global_params = [
  "root"        => "../../../",
  "root_public" => "../../",
  "title"       => "Tableaux vivants",
  "css"         => "all.css",
  "redirect"    => FALSE // J'hésite à mettre ça en true
];?>
<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/functions.php"  ); ?>
<?php require($global_params["root"] . "assets/script/php/header.php"); ?>
<!-- ------------------------------------------ -->
<?php // FUNCTIONS (specific à cette page)

?>
<!-- ------------------------------------------ -->
    <div id = "mid_content">
  <?php

      for ($i = 0; $i < 50; $i++)
        write("test : " . $i);

  ?>
    </div>

<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/footer.php"); ?>