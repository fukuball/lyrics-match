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
   $song_obj = new LMSong($_GET['song_id']);
   $disc_obj = new LMDisc($song_obj->disc_id);
   $performer_obj = new LMPerformer($song_obj->performer_id);
?>
<div id='song-item-block'>
   <div class="row">
      <div style="width:300px;">
         <a href="#" class="thumbnail">
            <img src="<?=$disc_obj->cover_path?>" alt="cover">
         </a>
      </div>
      <div style="width:700px;">
         <h2><?=$song_obj->title?></h2>
         <h3><?=$performer_obj->name?></h3>
         <h3><?=$disc_obj->title?></h3>
      </div>
   </div>
</div>
<?php
   unset($song_obj);
   unset($disc_obj);
}
?>