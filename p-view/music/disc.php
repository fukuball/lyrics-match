<div id='disc-list-block'>
   <table class="table table-bordered table-striped">
      <thead>
         <tr>
            <th width="20%">
               專輯
            </th>
            </th>
            <th width="10%">
               類型
            </th>
            <th width="10%">
               發行日期
            </th>
            <th width="30%">
               封面網址
            </th>
            <th width="30%">
               kkbox 網址
            </th>
         </tr>
      </thead>
      <tbody>
         <?php
         $disc_god_obj = new LMDiscGod();
         $disc_list = $disc_god_obj->getList('all', 0, 20);


         foreach ($disc_list as $disc_list_data) {

            $disc_obj = new LMSong($disc_list_data['id']);
         ?>
         <tr>
            <td>
               <?=$disc_obj->title?>
            </td>
            <td>
               <?=$disc_obj->genre?>
            </td>
            <td>
               <?=$disc_obj->release_date?>
            </td>
            <td>
               <?=$disc_obj->cover_path?>
            </td>
            <td>
               <?=$disc_obj->kkbox_url?>
            </td>
         </tr>
         <?php
            unset($disc_obj);
         }
         unset($disc_god_obj);
         ?>
      </tbody>
   </table>
</div>