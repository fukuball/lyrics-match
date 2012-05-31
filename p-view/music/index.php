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
            <th width="5%">
               id
            </th>
            <th width="15%">
               歌名
            </th>
            <th width="30%">
               歌詞
            </th>
            <th width="10%">
               類型
            </th>
            <th width="10%">
               發行日期
            </th>
            <th width="15%">
               midi 網址
            </th>
            <th width="15%">
               kkbox 網址
            </th>
         </tr>
      </thead>
      <tbody>
         <?php
         $offset = 0;
         $length = 30;

         require SITE_ROOT."/ajax-action/SongActionView/song-list.php";

         ?>
      </tbody>
   </table>
</div>
<script>
$.get("sarasti.cs.nccu.edu.tw/?url=www.google.com", function(response) {
    alert(response)
});
</script>