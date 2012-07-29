<?php
/**
 * song-audio-td.php is the song audio td content
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

$song_obj = new LMSong($song_id);
?>
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
<?php
unset($song_obj);
?>