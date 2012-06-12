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

$song_god_obj = new LMSongGod();
$song_list = $song_god_obj->getList('all', $offset, $length);
$song_num = 0;
foreach ($song_list as $song_list_data) {
   $song_num++;
   $song_obj = new LMSong($song_list_data['id']);
   $artist_obj = new LMPerformer($song_obj->performer_id);
?>
<tr width="1000px">
   <td width="50px">
      <?=$song_obj->getId()?>
   </td>
   <td width="50px">
      <?=$artist_obj->name?>
   </td>
   <td width="100px">
      <?=$song_obj->title?>
   </td>
   <td width="400px">
      <?=nl2br($song_obj->lyric)?>
   </td>
   <td width="50px">
      <?=$song_obj->genre?>
   </td>
   <td width="50px">
      <?=$song_obj->release_date?>
   </td>
   <td width="100px">
      <a href="<?=$song_obj->getMidiUrl()?>" target="_blank">連結</a>
   </td>
   <td width="100px">
      <a href="<?=$song_obj->getAudioUrl()?>" target="_blank">連結</a>
      <br/>
      <a class="upload-audio" href="javascript:;">
         <button type="button" class="btn btn-primary start">
            <i class="icon-upload icon-white"></i>
            <span>Upload</span>
         </button>
      </a>
   </td>
   <td width="100px">
      <a href="<?=$song_obj->kkbox_url?>" target="_blank">連結</a>
   </td>
</tr>
<?php
   unset($song_obj);
}
unset($song_god_obj);

?>