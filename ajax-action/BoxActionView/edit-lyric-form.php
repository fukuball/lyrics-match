<?php
/**
 * edit-lyric-form.php is the view of edit lyric form
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
         Edit Lyric
      </h3>
   </div>
   <form id="edit-lyric-form" name="edit_lyric_form" action="<?=SITE_HOST?>/ajax-action/song-action/edit-lyric" method="post">
      <input id="edit-lyric-song-id" value="<?=$song_obj->getId()?>" name="edit_lyric_song_id" type="hidden" />
      <div class="modal-body">
         <div class="control-group">
            <label class="control-label" for="textarea">Lyric</label>
            <div class="controls">
               <textarea class="input-xlarge" id="edit-lyric-content" name="edit_lyric_content" style="width:450px; height:300px; overflow-y:auto;"><?=$song_obj->lyric?></textarea>
            </div>
         </div>
      </div>
      <div class="modal-footer align-center">
         <button id="edit-lyric-submit" type="submit" class="btn btn-primary">
            Save
         </button>
         <button id="edit-lyric-cancel" type="button" class="btn" data-dismiss="modal">
            Cancel
         </button>
      </div>
   </form>
</div>
<script>
   $('#p-modal').ready(function() {

      $('#p-modal').modal('show');

      $('#p-modal').on('hidden', function () {

         $('#p-modal-block').empty();

      })

      $('#edit-lyric-form').ajaxForm({

         beforeSubmit:  editLyricValidate,
         success:       editLyricResponse,
         url: '<?=SITE_HOST?>/ajax-action/song-action/edit-lyric',
         type: 'post',
         dataType: 'json'

      });// end $('#edit-lyric-for').ajaxForm

      function editLyricValidate(formData, jqForm, options){

         var is_validated = true;

         if(is_validated){

            $('#edit-lyric-submit').attr("disabled", "disabled");
            $('#edit-lyric-cancel').attr("disabled", "disabled");

            $('#system-message').html('Processing...');
            $('#system-message').show();

         }

         return is_validated;

      }// end function editLyricValidate

      function editLyricResponse(responseText, statusText, xhr, $form)  {

         if(responseText.response.status.code==0){

            $('#song-lyric-<?=$song_obj->getId()?>').html($('#edit-lyric-content').val());
            $('#p-modal').modal('hide');

            $('#system-message').html('Success');
            $('#system-message').fadeOut();

         } else {

            $('#edit-lyric-submit').removeAttr("disabled");
            $('#edit-lyric-cancel').removeAttr("disabled");

            $('#system-message').html('Fail, Please Try Again');
            $('#system-message').fadeOut();
         }

      }// end function editLyricResponse

   });// end $('#p-modal').ready
</script>