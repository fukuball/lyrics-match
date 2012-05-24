<?php
/**
 * home-layout.php is home layout
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /iv-layout/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */
?>
<!DOCTYPE html>
<html lang='en' xmlns:fb='https://www.facebook.com/2008/fbml' xmlns:og='http://ogp.me/ns#'>
   <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# music: http://ogp.me/ns/music#">
      <?php
      require SITE_ROOT."/p-view/head/meta-include.php";
      require SITE_ROOT."/p-view/head/icon-include.php";
      require SITE_ROOT."/p-view/head/style-include.php";
      require SITE_ROOT."/p-view/head/javascript-include.php";
      ?>
   </head>
   <body>
      <?php
      require SITE_ROOT."/p-view/head/facebook-include.php";
      require SITE_ROOT."/p-view/header/header.php";
      ?>
      <div id="main-board" class="container">
         <div id="content" class="row margin-all">
            <section class="margin-v-1">
            <?php
            require SITE_ROOT.$yield_top_tab_path;
            ?>
            </section>
            <section>
            <?php
            require SITE_ROOT.$yield_path;
            ?>
            </section>
         </div>
         <?php
         require SITE_ROOT."/p-view/footer/footer.php";
         ?>
      </div><!-- /container -->
      <?php
      require SITE_ROOT."/p-view/foot/javascript-include.php";
      ?>
   </body>
</html>