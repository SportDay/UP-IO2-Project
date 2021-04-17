<?php $global_params = [
  "root"        => "../../../",
  "root_public" => "../../",
  "title"       => "Amis",
  "css"         => "all.css",
  "redirect"    => TRUE
];?>
<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/functions.php"  ); ?>
<?php require($global_params["root"] . "assets/script/php/header.php"); ?>
<!-- ------------------------------------------ -->
<?php // FUNCTIONS (specific à cette page)

?>
<!-- ------------------------------------------ -->

  <?php

      for ($i = 0; $i < 50; $i++)
        write("test : " . $i);

  ?>

<!-- ------------------------------------------ -->
<?php require($global_params["root"] . "assets/script/php/footer.php"); ?>