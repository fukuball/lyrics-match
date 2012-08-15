<?php
/**
 * alert-no-licence.php is the view of alert-no-licence
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /ajax-action/BoxActionView/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */

?>
<div id="p-modal" class="modal hide fade" style="width:<?php echo htmlspecialchars($size); ?>;display: none; ">
   <div class="modal-header">
      <h3>
         此音樂沒有授權
      </h3>
   </div>
   <div class="modal-body">
      <div class="alert alert-block">
         <h4 class="alert-heading">Warning! 此音樂沒有授權</h4>
         <p>您上傳的歌曲包含未經授權的音軌，因此系統已停用音訊！請上傳其他歌曲，或使用我們提供的合法授權歌曲！</p>
      </div>
   </div>
   <div class="modal-footer align-center">
      <button id="alert-licence-close" type="button" class="btn" data-dismiss="modal">
         關閉
      </button>
   </div>
</div>
<script>
   $('#p-modal').ready(function() {

      $('#p-modal').modal('show');

      $('#p-modal').on('hidden', function () {

         $('#p-modal-block').empty();

      })

   });// end $('#p-modal').ready
</script>