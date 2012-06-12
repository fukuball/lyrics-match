<?php
/**
 * login-form.php is the view of login form
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
         Login
      </h3>
   </div>
   <form id="login-form" name="login_form" action="<?=SITE_HOST?>/ajax-action/auth-action/login" method="post">
      <div class="modal-body">
         <div class="control-group">
            <label>
               Account
            </label>
            <input id="login-username" name="login_username" type="text" class="span4" placeholder="username..." />
         </div>
         <div class="control-group">
            <label>
               Password
            </label>
            <input id="login-password" name="login_password" type="password" class="span4" placeholder="password..." />
         </div>
      </div>
      <div class="modal-footer align-center">
         <button id="login-submit" type="submit" class="btn btn-primary">
            Login
         </button>
         <button id="login-cancel" type="button" class="btn" data-dismiss="modal">
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

      $('#login-form').ajaxForm({

         beforeSubmit:  loginValidate,
         success:       loginResponse,
         url: '<?=SITE_HOST?>/ajax-action/auth-action/login',
         type: 'post',
         dataType: 'json'

      });// end $('#login-form').ajaxForm

      function loginValidate(formData, jqForm, options){

         var is_validated = true;

         if(is_validated){

            $('#login-submit').attr("disabled", "disabled");
            $('#login-cancel').attr("disabled", "disabled");

            $('#system-message').html('Processing...');
            $('#system-message').show();

         }

         return is_validated;

      }// end function loginValidate

      function loginResponse(responseText, statusText, xhr, $form)  {

         if(responseText.response.status.code==0){
            // reload or redirect to some page

            $('#system-message').html('Success');
            $('#system-message').fadeOut();

            <?php
            switch ($mode) {
            case 'home':
               echo 'window.location = "<?=SITE_HOST?>/index.html";';
               break;
            case 'reload':
            default:
               echo 'window.location.reload();';
               break;
            }
            ?>

         } else {

            $('#login-submit').removeAttr("disabled");
            $('#login-cancel').removeAttr("disabled");

            $('#system-message').html('Fail, Please Try Again');
            $('#system-message').fadeOut();
         }

      }// end function loginResponse

   });// end $('#p-modal').ready
</script>