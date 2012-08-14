<?php
/**
 * BoxAction.php is the controller to dispatch box actions with box view
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
 * BoxAction is the controller to dispatch box actions with box view
 *
 * An example of a BoxAction is:
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
class BoxAction extends LMRESTControl implements LMRESTfulInterface
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

      case 'login-form':

         include SITE_ROOT.'/ajax-action/BoxActionView/login-form.php';

         break;

      case 'edit-lyric-form':

         $song_id = $_GET['song_id'];
         $size = $_GET['size'];
         if (empty($size)) {
            $size = "500px";
         }

         $song_obj = new LMSong($song_id);

         include SITE_ROOT.'/ajax-action/BoxActionView/edit-lyric-form.php';

         unset($song_obj);

         break;

      case 'upload-audio-form':

         $song_id = $_GET['song_id'];
         $size = $_GET['size'];
         if (empty($size)) {
            $size = "500px";
         }

         include SITE_ROOT.'/ajax-action/BoxActionView/upload-audio-form.php';

         break;

      case 'upload-midi-form':

         $song_id = $_GET['song_id'];
         $size = $_GET['size'];
         if (empty($size)) {
            $size = "500px";
         }

         include SITE_ROOT.'/ajax-action/BoxActionView/upload-midi-form.php';

         break;

      case 'alert-no-licence':

         $size = $_GET['size'];
         if (empty($size)) {
            $size = "500px";
         }

         include SITE_ROOT.'/ajax-action/BoxActionView/alert-no-licence.php';

         break;

      case 'r-song-box':

         $size = $_GET['size'];
         if (empty($size)) {
            $size = "500px";
         }
         $song_id = $_GET['song_id'];
         $song_id_array = explode('_', $song_id);
         $song_o_obj = new LMSong($song_id_array[0]);
         $song_r_obj = new LMSong($song_id_array[1]);

         include SITE_ROOT.'/ajax-action/BoxActionView/r-song-box.php';

         unset($song_o_obj);
         unset($song_r_obj);

         break;

      case 'send-sms-box':

         $size = $_GET['size'];
         if (empty($size)) {
            $size = "500px";
         }
         $url = $_GET['url'];

         include SITE_ROOT.'/ajax-action/BoxActionView/send-sms-box.php';

         unset($song_obj);

         break;

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

}// end class BoxAction
?>