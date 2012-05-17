<?php
/**
 * header.php is the view of main header
 * 
 * PHP version 5
 *
 * @category PHP
 * @package  /p-view/header/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */

$home_header_active = '';
$music_header_active = '';

switch ($current_page_path_url) {
   
case '/':
case '/index.html':

   $home_header_active = ' active';

   break;

case '/music':
case (preg_match('/music\/.*/', $current_page_path_url) ? true : false) :

   $music_header_active = ' active';

   break;
      
}
 
?>
<header id="top-bar" class="navbar navbar-fixed-top">
   <div class="navbar-inner">
      <div class="container">
         <a id="p-collapse-btn" class="btn btn-navbar" title="導覽列">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
         </a>
         <div id="logo">
            <a class="brand" href="<?=SITE_HOST?>/" title="Lyrics Match">Lyrics Match</a>
         </div>
         <div id="p-nav-collapse" class="nav-collapse">
            <nav>
               <ul class="nav">
                  <li id="header-home-tab" class="main-nav<?php echo $home_header_active; ?>">
                     <a href="<?=SITE_HOST?>/" title="首頁">
                        首頁
                     </a>
                  </li>
                  <li id="header-music-tab" class="main-nav<?php echo $music_header_active; ?>">
                     <a href="<?=SITE_HOST?>/music/" title="音樂">
                        音樂
                     </a>
                  </li>
               </ul>
            </nav>
            <ul class="nav pull-right">
               <li class="divider-vertical"></li>
               <li>
                  <a id="header-login" title="登入">
                     登入
                  </a>
               </li>
               <li class="divider-vertical"></li>
            </ul>
         </div><!--/.nav-collapse -->
      </div>
   </div>
</header>
<div class="align-center">
   <span id="system-message"> 處理中 ... </span>
</div>
<div id="p-modal-block">
</div>