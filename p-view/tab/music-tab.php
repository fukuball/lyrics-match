<?php
/**
 * music-tab.php is music tab view
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /tab/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */

$music_song_tab_active = '';
$music_disc_tab_active = '';
$music_performer_tab_active = '';
$music_lyricist_tab_active = '';
$music_composer_tab_active = '';
$music_addsong_tab_active = '';
$music_wordcount_tab_active = '';
$music_audiocodebook_tab_active = '';

switch ($current_page_path_url) {

case '/music':
case '/music/':
case (preg_match('/music\/index.*/', $current_page_path_url) ? true : false) :
case (preg_match('/music\/song\/.*/', $current_page_path_url) ? true : false) :

 $music_song_tab_active = ' active';

 break;

case (preg_match('/music\/disc.*/', $current_page_path_url) ? true : false) :

   $music_disc_tab_active = ' active';

   break;

case (preg_match('/music\/performer.*/', $current_page_path_url) ? true : false) :

   $music_performer_tab_active = ' active';

   break;

case (preg_match('/music\/lyricist.*/', $current_page_path_url) ? true : false) :

   $music_lyricist_tab_active = ' active';

   break;

case (preg_match('/music\/composer.*/', $current_page_path_url) ? true : false) :

   $music_composer_tab_active = ' active';

   break;

case (preg_match('/music\/add\-song.*/', $current_page_path_url) ? true : false) :

   $music_addsong_tab_active = ' active';

   break;

case (preg_match('/music\/word\-count.*/', $current_page_path_url) ? true : false) :

   $music_wordcount_tab_active = ' active';

   break;

case (preg_match('/music\/audio\-code\-book.*/', $current_page_path_url) ? true : false) :

   $music_audiocodebook_tab_active = ' active';

   break;

}


?>
<ul class="nav nav-tabs">
   <li class="<?=$music_song_tab_active?>">
     <a href="<?=SITE_HOST?>/music/">
        歌曲列表
     </a>
   </li>
   <li class="<?=$music_disc_tab_active?>">
      <a href="<?=SITE_HOST?>/music/disc.php">
         專輯列表
      </a>
   </li>
   <li class="<?=$music_performer_tab_active?>">
      <a href="<?=SITE_HOST?>/music/performer.php">
         藝人列表
      </a>
   </li>
   <li class="<?=$music_lyricist_tab_active?>">
      <a href="<?=SITE_HOST?>/music/lyricist.php">
         作詞人列表
      </a>
   </li>
   <li class="<?=$music_composer_tab_active?>">
      <a href="<?=SITE_HOST?>/music/composer.php">
         作曲人列表
      </a>
   </li>
   <li class="<?=$music_addsong_tab_active?>">
      <a href="<?=SITE_HOST?>/music/add-song.php">
         新增歌曲資料
      </a>
   </li>
   <li class="<?=$music_wordcount_tab_active?>">
      <a href="<?=SITE_HOST?>/music/word-count.php">
         歌詞字數列表
      </a>
   </li>
   <li class="<?=$music_audiocodebook_tab_active?>">
      <a href="<?=SITE_HOST?>/music/audio-code-book.php">
         Audio Code Book
      </a>
   </li>
</ul>