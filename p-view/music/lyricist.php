<?php
/**
 * lyricist.php is the /music/lyricist.php content
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
<div id='lyricist-list-block'>
   <table class="table table-bordered table-striped">
      <thead>
         <tr>
            <th>
               id
            </th>
            <th>
               名稱
            </th>
         </tr>
      </thead>
      <tbody>
         <?php
         $lyricist_god_obj = new LMLyricistGod();
         $lyricist_list = $lyricist_god_obj->getList('all', 0, 1000);


         foreach ($lyricist_list as $lyricist_list_data) {

            $lyricist_obj = new LMLyricist($lyricist_list_data['id']);
         ?>
         <tr>
            <td>
               <?=$lyricist_obj->getId()?>
            </td>
            <td>
               <?=$lyricist_obj->name?>
            </td>
         </tr>
         <?php
            unset($lyricist_obj);
         }
         unset($lyricist_god_obj);
         ?>
      </tbody>
   </table>
</div>