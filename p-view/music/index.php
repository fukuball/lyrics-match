<div id='song-list-block'>
   <table class="table table-bordered table-striped">
      <thead>
         <tr>
            <th>
               歌名
            </th>
            <th>
               歌詞
            </th>
            <th>
               類型
            </th>
            <th>
               發行日期
            </th>
            <th>
               kkbox 網址
            </th>
         </tr>
      </thead>
      <tbody>
         <?php
         $song_god_obj = new LMSongGod();
         $song_list = $song_god_obj->getList('all', 0, 20);

         foreach ($song_list as $key => $song_id ) {
            echo $song_id.'<br/>';
            /*$song_obj = new LMSong($song_id);
         ?>
         <tr>
            <td>
               <?=$song_obj->title?>
            </td>
            <td>
               <?=$song_obj->lyric?>
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
            unset($song_obj);*/
         }
         unset($song_god_obj);
         ?>
      </tbody>
   </table>
</div>