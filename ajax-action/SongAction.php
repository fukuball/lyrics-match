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

      case 'add-song':

         $validate_artist_name
             = LMValidateHelper::
                  validateNoEmpty($_POST['artist_name']);

         $validate_artist_kkbox_url
             = LMValidateHelper::
                  validateNoEmpty($_POST['artist_kkbox_url']);

         if (   !$validate_artist_name
             || !$validate_artist_name
         ) {
            $type = 'not_exist_value';
            $parameter = array("none"=>"none");
            $error_messanger = new LMErrorMessenger($type, $parameter);
            $error_messanger->printErrorJSON();
            unset($error_messanger);
         } else {

            $performer_god_obj = new LMPerformerGod();
            $composer_god_obj = new LMComposerGod();
            $lyricist_god_obj = new LMLyricistGod();
            $disc_god_obj = new LMDiscGod();
            $song_god_obj = new LMSongGod();

            $artist_name = $_POST['artist_name'];
            $artist_kkbox_url = $_POST['artist_kkbox_url'];

            // get performer id
            $performer_id = $performer_god_obj->findByKKBOXURL($artist_kkbox_url);
            if (empty($performer_id)) {
               $parameter_array = array();
               $parameter_array['name']
                   = $artist_name;
               $parameter_array['kkbox_url']
                   = $artist_kkbox_url;
               $performer_id = $performer_god_obj->create($parameter_array);
               echo "create performer $performer_id \n";
            } else {
               echo "read performer $performer_id \n";
            }

         }


         break;

      case 'check-add-song':

         $validate_check_song_kkbox_url
             = LMValidateHelper::
                  validateNoEmpty($_POST['check_song_kkbox_url']);

         if (!$validate_check_song_kkbox_url) {
            $type = 'not_exist_value';
            $parameter = array("none"=>"none");
            $error_messanger = new LMErrorMessenger($type, $parameter);
            $error_messanger->printErrorJSON();
            unset($error_messanger);
         } else {

            $check_song_kkbox_url = $_POST['check_song_kkbox_url'];

            $song_god_obj = new LMSongGod();

            $instance_id = 0;
            $instance_id = $song_god_obj->findBYKKBOXURL($check_song_kkbox_url);


            if ($instance_id) {

               $html_block = 'song_exist';
               echo $html_block;

            } else {

               $kkbox_link = 'http://tw.kkbox.com';
               $song_kkbox_url = $check_song_kkbox_url;

               // get song detail
               $yql_query = urlencode('SELECT * FROM html WHERE url="'.$song_kkbox_url.'"');
               $song_page_html = file_get_contents('http://query.yahooapis.com/v1/public/yql?q='.$yql_query.'&format=json');
               $song_page_dom = json_decode($song_page_html);

               // get lyric info
               $kk_lyric = $song_page_dom->query->results->body->div[3]->div[1]->div[0]->div[2]->p->content;
               //print_r($kk_lyric);

               // parse wrighter
               $kk_lyric_array = explode('：', $kk_lyric);
               //print_r($kk_lyric_array);

               // parse lyricist
               $parse_lyricist = explode('   ', $kk_lyric_array[1]);
               $in_lyricist_name = trim($parse_lyricist[0]);

               // parse lyricist
               //$parse_composer = explode(' ', $kk_lyric_array[2]);
               $parse_composer = explode("\n", $kk_lyric_array[2]);
               $in_composer_name = trim($parse_composer[0]);

               // parse lyric
               //$parse_lyric = explode("\n", $kk_lyric_array[4]);
               $parse_lyric = explode("\n", $kk_lyric_array[2]);
               $parse_lyric = array_slice($parse_lyric, 1);
               //print_r($parse_lyric);
               $normalize_lyric_array = array();
               foreach ($parse_lyric as $key => $value) {
                  $normal_value = nl2br(trim($value));
                  $normal_value = str_replace('<br />', '', $normal_value);
                  $normal_value = str_replace('<br/>', '', $normal_value);
                  $normal_value = str_replace('<br>', '', $normal_value);
                  if ($normal_value!='') {
                     array_push($normalize_lyric_array, trim($value));
                  }
               }
               //print_r($normalize_lyric_array);
               $in_lyric = implode("\n", $normalize_lyric_array);

               // get performer info
               $in_performer_name = trim($song_page_dom->query->results->body->div[3]->div[0]->ul->li[1]->a->content);
               $in_performer_url = $kkbox_link.$song_page_dom->query->results->body->div[3]->div[0]->ul->li[1]->a->href;

               // get disc info
               $in_disc_name = trim($song_page_dom->query->results->body->div[3]->div[0]->ul->li[2]->a->content);
               $in_disc_url = $kkbox_link.$song_page_dom->query->results->body->div[3]->div[0]->ul->li[2]->a->href;
               $in_disc_src = $song_page_dom->query->results->body->div[3]->div[1]->div[0]->div[0]->div->div[0]->img->src;
               $in_disc_genre = $song_page_dom->query->results->body->div[3]->div[1]->div[0]->div[0]->div->div[1]->dl->dd[1]->p;
               $in_disc_release = trim($song_page_dom->query->results->body->div[3]->div[1]->div[0]->div[0]->div->div[1]->dl->dd[2]->p).'-01';

               require SITE_ROOT."/ajax-action/SongActionView/add-song-form.php";

            }

            unset($song_god_obj);
         }

         break;

      case 'delete-lyric-block':

         $validate_lyrics_block_truth_id
             = LMValidateHelper::
                  validateNoEmpty($_POST['lyrics_block_truth_id']);

         if (!$validate_lyrics_block_truth_id) {
            $type = 'not_exist_value';
            $parameter = array("none"=>"none");
            $error_messanger = new LMErrorMessenger($type, $parameter);
            $error_messanger->printErrorJSON();
            unset($error_messanger);
         } else {

            $lyrics_block_truth_id = $_POST['lyrics_block_truth_id'];
            $lyrics_block_truth_obj = new LMLyricsBlockTruth($lyrics_block_truth_id);

            if ($lyrics_block_truth_obj->destroy()) {

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

            unset($lyrics_block_truth_obj);
         }


         break;

      case 'save-lyric-block':

         $validate_block
             = LMValidateHelper::
                  validateNoEmpty($_POST['block']);
         $validate_label_id
             = LMValidateHelper::
                  validateNoEmpty($_POST['label_id']);
         $validate_lyrics_block_truth_id
             = LMValidateHelper::
                  validateNoEmpty($_POST['lyrics_block_truth_id']);

         if (   !$validate_lyrics_block_truth_id
             || !$validate_block
             || !$validate_label_id
         ) {
            $type = 'not_exist_value';
            $parameter = array("none"=>"none");
            $error_messanger = new LMErrorMessenger($type, $parameter);
            $error_messanger->printErrorJSON();
            unset($error_messanger);
         } else {

            $lyrics_block_truth_id = $_POST['lyrics_block_truth_id'];
            $block = $_POST['block'];
            $label_id = $_POST['label_id'];

            $lyrics_block_truth_obj = new LMLyricsBlockTruth($lyrics_block_truth_id);
            $lyrics_block_truth_obj->block = $block;
            $lyrics_block_truth_obj->label_id = $label_id;

            if ($lyrics_block_truth_obj->save()) {

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

            unset($lyrics_block_truth_obj);
         }

         break;

      case 'add-lyric-block':

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

            $lyrics_block_truth_god_obj = new LMLyricsBlockTruthGod();
            $parameter_array['song_id']
                = $song_id;

            $song_lyrics_block_id = $lyrics_block_truth_god_obj->create($parameter_array);

            if ($song_lyrics_block_id) {
               require SITE_ROOT."/ajax-action/SongActionView/song-lyrics-block-form.php";
            } else {
               $type = 'unknow_error';
               $parameter = array("none"=>"none");
               $error_messanger = new LMErrorMessenger($type, $parameter);
               $error_messanger->printErrorJSON();
               unset($error_messanger);
            }

         }

         break;

      case 'edit-lyric':

         $validate_song_id
             = LMValidateHelper::
                  validateNoEmpty($_POST['edit_lyric_song_id']);

         if (!$validate_song_id) {
            $type = 'not_exist_value';
            $parameter = array("none"=>"none");
            $error_messanger = new LMErrorMessenger($type, $parameter);
            $error_messanger->printErrorJSON();
            unset($error_messanger);
         } else {

            $song_id = $_POST['edit_lyric_song_id'];
            $song_lyric = $_POST['edit_lyric_content'];

            $song_obj = new LMSong($song_id);
            $song_obj->lyric = $song_lyric;

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
         }

         break;

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

      case 'upload-midi':

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
            $target_file_name = MIDI_ROOT.'/'.$song_id;

            $retunr_value = LMUploadHelper::pluploadProcess(
               $_REQUEST,
               $_SERVER,
               $_FILES,
               $target_file_name
            );

            if ($retunr_value!='fail') {

               $song_obj = new LMSong($song_id);
               $song_obj->midi_path = $retunr_value;

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

      case 'get-song-audio-td':

         $validate_song_id
             = LMValidateHelper::
                  validateNoEmpty($_GET['song_id']);

         if (!$validate_song_id) {
            $type = 'not_exist_value';
            $parameter = array("none"=>"none");
            $error_messanger = new LMErrorMessenger($type, $parameter);
            $error_messanger->printErrorJSON();
            unset($error_messanger);
         } else {

            $song_id = $_GET['song_id'];
            require SITE_ROOT."/ajax-action/SongActionView/song-audio-td.php";

         }

         break;

      case 'song-list':

         $offset = $_GET['offset'];
         if (!LMValidateHelper::validateNoEmpty($offset)) {
            $offset = 0;
         }
         $length = $_GET['length'];
         if (!LMValidateHelper::validateNoEmpty($length)) {
            $length = 33;
         }

         switch ($_GET['song_list_type']) {

         case 'audio':

            $song_list_type = 'audio';

            break;

         case 'no-audio':

            $song_list_type = 'no-audio';

            break;

         case 'all':
         default:

            $song_list_type = 'all';

            break;
         }

         $song_god_obj = new LMSongGod();
         $song_list = $song_god_obj->getList($song_list_type, $offset, $length);

         require SITE_ROOT."/ajax-action/SongActionView/song-list.php";

         unset($song_god_obj);

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