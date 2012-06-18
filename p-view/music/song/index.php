<?php
/**
 * index.php is the /music/song/index.php content
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /music/song/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */
if (!empty($_GET['song_id'])) {
?>
<ul class="breadcrumb">
   <li>
      <a href="<?=SITE_HOST?>/music">歌曲列表</a> <span class="divider">/</span>
   </li>
   <li class="active">
      歌曲資料
   </li>
</ul>
<?php
   $song_obj = new IndievoxSong($_GET['song_id']);
   $disc_obj = new IndievoxDisc($song_obj->disc_id);
?>
<div id='song-item-block'>
   <div class="row">
      <div width="300px">
         <a href="#" class="thumbnail">
            <img src="<?=$disc_obj->cover_path?>" alt="cover">
         </a>
      </div>
      <div width="700px">
      </div>
   </div>
</div>
<?php
   unset($song_obj);
   unset($disc_obj);
}
?>