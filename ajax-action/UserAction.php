<?php
/**
 * SongAction.php is the controller to dispatch song actions with song view
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /ajax-action/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */

/**
 * SongAction is the controller to dispatch song actions with song view
 *
 * An example of a SongAction is:
 *
 * <code>
 *  # This will done by rest request
 * </code>
 *
 * @category PHP
 * @package  /ajax-action/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */
class UserAction extends LMRESTControl implements LMRESTfulInterface
{

   /**
    * Dispatch post actions
    *
    * @param array $segments Method segments indicate action and resource
    *
    * @return void
    */
   public function restPost($segments)
   {

      $action_id = $segments[0];

      switch ($action_id) {

      case 'user-upload':

         // 5 minutes execution time
         @set_time_limit(5 * 60);

         $type = 'unknow_error';
         $parameter = array("none"=>"none");
         $error_messanger = new LMErrorMessenger($type, $parameter);
         $error_messanger->printErrorJSON();
         unset($error_messanger);

         break;

      case 'send-sms':

         //variable configuration
         $phone = $_POST['phone'];
         $message = $_POST['message'];
         $user_message = $_POST['user_message'];

         if (!empty($phone)) {
            $host       = "hiapi.ext.hipaas.hinet.net";
            $serviceid  = "14";
            $isvid      = "a93ac44e86924497892b674d619c29fe";
            $isvkey     = "FngenvssyLCMw+A1W8TeNg==";
            $phone      = $phone;
            $msg        = $message." \n ".$user_message;

            //get the token and sign
            //$nonce = substr(md5(uniqid('nonce_', true)),0,16);
            //$timestamp=round(microtime(true)*1000);
            //$sdksign=sha1($isvkey.$nonce.$timestamp);
            //$url="http://$host/SrvMgr/requestToken/$isvid/$serviceid/$nonce/$timestamp/$sdksign/";
            //$ch = curl_init();
            //curl_setopt($ch, CURLOPT_URL, $url);
            //curl_setopt($ch, CURLOPT_HEADER, 0);
            //curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            //$r=curl_exec($ch);
            //$jsonresult=json_decode($r);
            //$token=$jsonresult->info->token;
            //$sign=$jsonresult->info->sign;
            //
            //$smsserver="http://sms.hiapi1.lab.hipaas.hinet.net/hisms/servlet/send";
            //$ch=curl_init();
            //curl_setopt($ch, CURLOPT_URL, $smsserver);
            //curl_setopt($ch,CURLOPT_POST,1);
            //curl_setopt($ch, CURLOPT_POSTFIELDS, "isvAccount=$isvid&token=$token&sign=$sign&msisdn=$phone&msg=$msg");
            //curl_exec($ch);

            echo "fail";

            //$type = 'success';
            //$parameter = array("none"=>"none");
            //$error_messanger = new LMErrorMessenger($type, $parameter);
            //$error_messanger->printErrorJSON();
            //unset($error_messanger);

         } else {
            //$type = 'unknow_error';
            //$parameter = array("none"=>"none");
            //$error_messanger = new LMErrorMessenger($type, $parameter);
            //$error_messanger->printErrorJSON();
            //unset($error_messanger);

            echo "fail";

         }



         break;

      default:

         $type = 'page_not_found';
         $parameter = array("none"=>"none");
         $error_messanger = new LMErrorMessenger($type, $parameter);
         $error_messanger->printErrorJSON();
         unset($error_messanger);

         break;// end default

      }// end switch ($action_id)

   }// end function restPost

   /**
    * Dispatch get actions
    *
    * @param array $segments Method segments indicate action and resource
    *
    * @return void
    */
   public function restGet($segments)
   {

      $action_id = $segments[0];

      switch ($action_id) {

      default:

         $type = 'page_not_found';
         $parameter = array("none"=>"none");
         $error_messanger = new LMErrorMessenger($type, $parameter);
         $error_messanger->printErrorJSON();
         unset($error_messanger);

         break;
      }

  }// end function restGet

   /**
    * Dispatch put actions
    *
    * @param array $segments Method segments indicate action and resource
    *
    * @return void
    */
   public function restPut($segments)
   {

      echo file_get_contents('php://input');

   }// end function restPut

   /**
    * Dispatch delete actions
    *
    * @param array $segments Method segments indicate action and resource
    *
    * @return void
    */
   public function restDelete($segments)
   {

      echo file_get_contents('php://input');

   }// end function restDelete

}// end class UserAction
?>