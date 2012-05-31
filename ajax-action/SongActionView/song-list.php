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
?>
<tr>
   <td width="5%">
      <?=$song_obj->getId()?>
   </td>
   <td width="15%">
      <?=$song_obj->title?>
   </td>
   <td width="30%">
      <?=nl2br($song_obj->lyric)?>
   </td>
   <td width="10%">
      <?=$song_obj->genre?>
   </td>
   <td width="10%">
      <?=$song_obj->release_date?>
   </td>
   <td width="15%">
      <a href="<?=$song_obj->getMidiUrl()?>" target="_blank"><?=$song_obj->getMidiUrl()?></a>
   </td>
   <td width="15%">
      <a href="<?=$song_obj->kkbox_url?>" target="_blank"><?=$song_obj->kkbox_url?></a>
   </td>
</tr>
<?php
   unset($song_obj);
}
unset($song_god_obj);

?>