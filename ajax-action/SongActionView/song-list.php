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
?>
$song_god_obj = new LMSongGod();
$song_list = $song_god_obj->getList('all', $offset, $length);
foreach ($song_list as $song_list_data) {

   $song_obj = new LMSong($song_list_data['id']);
?>
<tr>
   <td>
      <?=$song_obj->getId()?>
   </td>
   <td>
      <?=$song_obj->title?>
   </td>
   <td>
      <?=nl2br($song_obj->lyric)?>
   </td>
   <td>
      <?=$song_obj->genre?>
   </td>
   <td>
      <?=$song_obj->release_date?>
   </td>
   <td>
      <a href="<?=$song_obj->getMidiUrl()?>" target="_blank"><?=$song_obj->getMidiUrl()?></a>
   </td>
   <td>
      <a href="<?=$song_obj->kkbox_url?>" target="_blank"><?=$song_obj->kkbox_url?></a>
   </td>
</tr>
<?php
   unset($song_obj);
}
unset($song_god_obj);
?>