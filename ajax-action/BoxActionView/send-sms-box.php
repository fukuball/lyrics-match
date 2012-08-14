<?php
/**
 * send-sms-box.php is the view of send-sms-box
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

$message = $_GET['message'];
?>
<div id="p-modal" class="modal hide fade" style="width:<?php echo htmlspecialchars($size); ?>;display: none; ">
   <div class="modal-header">
      <h3>
         傳簡訊分享給好朋友
      </h3>
   </div>
   <form id="sms-form" name="sms_form" action="<?=SITE_HOST?>/ajax-action/user-action/send-sms" method="post">
      <div class="modal-body">
         <div class="control-group">
            <label>
               手機號碼
            </label>
            <input id="phone" name="phone" type="text" class="span4" placeholder="09" />
            <p class="help-block" style="display:none;">
               未填寫
            </p>
         </div>
         <input id="message" name="message" type="hidden" value="<?=$message?>" />
         <div class="control-group" style="display:none;">
            <label>
               留言
            </label>
            <textarea class="span4" id="user_message" name="user_message" style="height:50px;"></textarea>
         </div>
      </div>
      <div class="modal-footer align-center">
         <button id="sms-submit" type="submit" class="btn btn-primary">
            送出
         </button>
         <button id="sms-cancel" type="button" class="btn" data-dismiss="modal">
            取消
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

      $('#sms-form').ajaxForm({

         beforeSubmit:  smsValidate,
         success:       smsResponse,
         url: '<?=SITE_HOST?>/ajax-action/user-action/send-sms',
         type: 'post',
         dataType: 'json'

      });

      function smsValidate(formData, jqForm, options){

         var is_validated = true;

         if(!$('#phone').val()){

            $('#phone').parent().attr('class', 'control-group error');
            $('#phone').parent().find( $('.help-block') ).css('display','block');
            is_validated = false;

         } else {

            $('#phone').parent().attr('class', 'control-group');
            $('#phone').parent().find( $('.help-block') ).css('display','none');

         }

         if(is_validated){

            $('#sms-submit').attr("disabled", "disabled");
            $('#sms-cancel').attr("disabled", "disabled");

            $('#system-message').html('Processing...');
            $('#system-message').show();

         }

         return is_validated;

      }// end function loginValidate

      function smsResponse(responseText, statusText, xhr, $form)  {

         if(responseText.response.status.code==0){
            // reload or redirect to some page

            $('#system-message').html('Success');
            $('#system-message').fadeOut();

            $('#p-modal').modal('hide');

         } else {

            $('#sms-submit').removeAttr("disabled");
            $('#sms-cancel').removeAttr("disabled");

            $('#system-message').html('Fail, Please Try Again');
            $('#system-message').fadeOut();
         }

      }

   });// end $('#p-modal').ready
</script>