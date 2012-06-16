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
   <?php
   if (LMAuthHelper::isLogin()) {
   ?>
   <table width="1000px" class="table table-bordered table-striped">
      <thead width="1000px">
         <tr width="1000px">
            <th width="50px">
               id
            </th>
            <th width="50px">
               藝人
            </th>
            <th width="100px">
               歌名
            </th>
            <th width="400px">
               歌詞
            </th>
            <th width="50px">
               類型
            </th>
            <th width="50px">
               發行日期
            </th>
            <th width="100px">
               midi 網址
            </th>
            <th width="100px">
               mp3 網址
            </th>
            <th width="100px">
               kkbox 網址
            </th>
         </tr>
      </thead>
      <tbody width="1000px" id="song-list-tbody">
         <?php
         $offset = 0;
         $length = 30;

         switch ($_GET['song_list_type']) {


         case 'audio':

            $song_list_type = 'audio';

            break;

         case 'no-audio':

            $song_list_type = 'no-audio';

            break;

         case 'all':
         default:

            $song_list_type = 'all';

            break;
         }

         $song_god_obj = new LMSongGod();
         $song_list = $song_god_obj->getList($song_list_type, $offset, $length);

         require SITE_ROOT."/ajax-action/SongActionView/song-list.php";

         unset($song_god_obj);

         ?>
      </tbody>
   </table>
   <div id="song-show-more" class="show-more margin-top-1">
      <a data-length="30" data-list-type="<?=$song_list_type?>">
         顯示更多
      </a>
   </div>
   <?php
   } else {
   ?>
   <h2>Please Login To Use This Page!</h2>
   <?php
   }
   ?>
</div>