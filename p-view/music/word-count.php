<?php
/**
 * word-count.php is the /music/word-count.php content
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
            <th width="100px">
               id
            </th>
            <th width="100px">
               藝人
            </th>
            <th width="200px">
               歌名
            </th>
            <th width="500px">
               歌詞
            </th>
            <th width="100px">
               字數
            </th>
         </tr>
      </thead>
      <tbody width="1000px" id="song-list-tbody">
         <?php

         require SITE_ROOT."/ajax-action/SongActionView/word-count-list.php";

         ?>
      </tbody>
   </table>
</div>