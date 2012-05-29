<?php
/**
 * composer.php is the /music/composer.php content
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
<div id='composer-list-block'>
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
         $composer_god_obj = new LMComposerGod();
         $composer_list = $composer_god_obj->getList('all', 0, 20);


         foreach ($composer_list as $composer_list_data) {

            $composer_obj = new LMComposer($composer_list_data['id']);
         ?>
         <tr>
            <td>
               <?=$composer_obj->getId()?>
            </td>
            <td>
               <?=$composer_obj->name?>
            </td>
         </tr>
         <?php
            unset($composer_obj);
         }
         unset($composer_god_obj);
         ?>
      </tbody>
   </table>
</div>