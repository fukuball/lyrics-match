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

         require SITE_ROOT."/ajax-action/SongActionView/song-list.php";

         ?>
      </tbody>
   </table>
   <div id="song-show-more" class="show-more margin-top-1">
      <a data-length="30">
         顯示更多
      </a>
   </div>
</div>