<?php
/**
 * index.php is the /music/index.php content
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /music/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */
?>
<div id='song-list-block'>
   <table class="table table-bordered table-striped">
      <thead>
         <tr>
            <th width="15%">
               歌名
            </th>
            <th width="35%">
               歌詞
            </th>
            <th width="10%">
               類型
            </th>
            <th width="10%">
               發行日期
            </th>
            <th width="30%">
               kkbox 網址
            </th>
         </tr>
      </thead>
      <tbody>
         <?php
         $song_god_obj = new LMSongGod();
         $song_list = $song_god_obj->getList('all', 0, 20);


         foreach ($song_list as $song_list_data) {

            $song_obj = new LMSong($song_list_data['id']);
         ?>
         <tr>
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
               <?=$song_obj->kkbox_url?>
            </td>
         </tr>
         <?php
            unset($song_obj);
         }
         unset($song_god_obj);
         ?>
      </tbody>
   </table>
</div>