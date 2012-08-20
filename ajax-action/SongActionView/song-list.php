<?php
/**
 * song-list.php is the song list content
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /ajax-action/SongActionView
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */
$song_num = 0;
foreach ($song_list as $song_list_data) {
   $song_num++;
   $song_obj = new LMSong($song_list_data['id']);
   $artist_obj = new LMPerformer($song_obj->performer_id);
?>
<tr width="1000px">
   <td width="50px">
      <a href="<?=SITE_HOST?>/music/song/index.php?song_id=<?=$song_obj->getId()?>"><?=$song_obj->getId()?></a>
   </td>
   <td width="50px">
      <?=$artist_obj->name?>
   </td>
   <td width="100px">
      <a href="<?=SITE_HOST?>/music/song/index.php?song_id=<?=$song_obj->getId()?>">
         <?=$song_obj->title?>
      </a>
   </td>
   <td width="400px">
      <a class="edit-lyric-link" data-songid="<?=$song_obj->getId()?>">修改歌詞</a>
      <br/>
      <div id="song-lyric-<?=$song_obj->getId()?>" style="height:300px; width:400px; overflow:auto;"><?=nl2br($song_obj->lyric)?></div>
   </td>
   <td width="50px">
      <?=$song_obj->genre?>
   </td>
   <td width="50px">
      <?=$song_obj->release_date?>
   </td>
   <td id="song-td-audio-<?=$song_obj->getId()?>" width="100px">
      <?php
      if (!empty($song_obj->midi_path)) {
      ?>
      <a href="<?=$song_obj->getMidiUrl()?>" target="_blank">midi 連結</a>
      <br/>
      <br/>
      <?php
      }
      ?>
      <a class="upload-midi-link" data-songid="<?=$song_obj->getId()?>">
         midi 上傳
      </a>
      <br/>
      <br/>
      <?php
      if (!empty($song_obj->audio_path)) {
      ?>
      <a href="<?=$song_obj->getAudioUrl()?>" target="_blank">mp3 連結</a>
      <br/>
      <br/>
      <?php
      }
      ?>
      <a class="upload-audio-link" data-songid="<?=$song_obj->getId()?>">
         mp3 上傳
      </a>
   </td>
   <td width="100px">
      <a href="<?=$song_obj->kkbox_url?>" target="_blank">連結</a>
   </td>
   <td width="100px">
      <a href="<?=SITE_HOST?>/music/song/edit-lyric-block.php?song_id=<?=$song_obj->getId()?>" >編輯歌詞分段</a>
   </td>
</tr>
<?php
   unset($song_obj);
}

?>
