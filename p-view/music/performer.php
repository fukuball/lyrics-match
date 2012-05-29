<?php
/**
 * performer.php is the /music/performer.php content
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
<div id='performer-list-block'>
   <table class="table table-bordered table-striped">
      <thead>
         <tr>
            <th>
               名稱
            </th>
            <th>
               kkbox 網址
            </th>
         </tr>
      </thead>
      <tbody>
         <?php
         $performer_god_obj = new LMPerformerGod();
         $performer_list = $performer_god_obj->getList('all', 0, 20);


         foreach ($performer_list as $performer_list_data) {

            $performer_obj = new LMPerformer($performer_list_data['id']);
         ?>
         <tr>
            <td>
               <?=$performer_obj->name?>
            </td>
            <td>
               <?=$performer_obj->kkbox_url?>
            </td>
         </tr>
         <?php
            unset($performer_obj);
         }
         unset($performer_god_obj);
         ?>
      </tbody>
   </table>
</div>