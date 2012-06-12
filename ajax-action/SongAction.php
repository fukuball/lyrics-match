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
class SongAction extends LMRESTControl implements LMRESTfulInterface
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

      case 'upload-audio':

         // 5 minutes execution time
         @set_time_limit(5 * 60);

         $validate_song_id
             = LMValidateHelper::
                  validateNoEmpty($_POST['song_id']);

         if (!$validate_song_id) {
            $type = 'not_exist_value';
            $parameter = array("none"=>"none");
            $error_messanger = new LMErrorMessenger($type, $parameter);
            $error_messanger->printErrorJSON();
            unset($error_messanger);
         } else {

            $song_id = $_POST['song_id'];
            $target_file_name = AUDIO_ROOT.'/'.$song_id;

            $retunr_value = LMUploadHelper::pluploadProcess(
               $_REQUEST,
               $_SERVER,
               $_FILES,
               $target_file_name
            );

            if ($retunr_value!='fail') {

               $song_obj = new LMSong($song_id);
               $song_obj->audio_path = $retunr_value;

               if ($song_obj->save()) {

                  $type = 'success';
                  $parameter = array("none"=>"none");
                  $error_messanger = new LMErrorMessenger($type, $parameter);
                  $error_messanger->printErrorJSON();
                  unset($error_messanger);

               } else {

                  $type = 'unknow_error';
                  $parameter = array("none"=>"none");
                  $error_messanger = new LMErrorMessenger($type, $parameter);
                  $error_messanger->printErrorJSON();
                  unset($error_messanger);

               }

               unset($song_obj);
            } else {
               $type = 'unknow_error';
               $parameter = array("none"=>"none");
               $error_messanger = new LMErrorMessenger($type, $parameter);
               $error_messanger->printErrorJSON();
               unset($error_messanger);
            }

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

      case 'song-list':

         $offset = $_GET['offset'];
         if (!LMValidateHelper::validateNoEmpty($offset)) {
            $offset = 0;
         }
         $length = $_GET['length'];
         if (!LMValidateHelper::validateNoEmpty($length)) {
            $length = 33;
         }

         require SITE_ROOT."/ajax-action/SongActionView/song-list.php";

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

}// end class DiscAction
?>