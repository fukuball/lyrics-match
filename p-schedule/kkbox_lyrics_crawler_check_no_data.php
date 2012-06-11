<?php
/**
 * kkbox_lyrics_crawler_check_no_data.php to cache kkbox lyrics
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /p-schedule/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */

require_once dirname(dirname(__FILE__))."/p-config/application-setter.php";

$kkbox_link = 'http://tw.kkbox.com';

$db_obj = LMDBAccess::getInstance();
$performer_god_obj = new LMPerformerGod();
$composer_god_obj = new LMComposerGod();
$lyricist_god_obj = new LMLyricistGod();
$disc_god_obj = new LMDiscGod();
$song_god_obj = new LMSongGod();

$select_sql = "SELECT ".
              "* ".
              "FROM temp_midi ".
              "WHERE ".
              "is_moved='0' ";

$query_result = $db_obj->selectCommand($select_sql);

// get unprocess data
foreach ($query_result as $query_result_data) {

   $midi_id = $query_result_data['id'];
   echo "create midi_id $midi_id \n";
   $artist_title = $query_result_data['artist_title'];
   $song_title = $query_result_data['song_title'];
   $midi_path = $query_result_data['midi_path'];
   $search_resp = LMHelper::doGet($kkbox_link."/search.php?word=".urlencode($song_title)."+".urlencode($artist_title)."&search=song&search_lang=");
   $process_string = explode('<div class="search-notice">', $search_resp);
   $process_string = explode('</div>', $process_string[1]);
   $process_string = explode('<strong>', $process_string[0]);
   $song_result_song_num = explode('</strong>', $process_string[1]);

   if ($song_result_song_num[0]==0) {
      $update_sql = "UPDATE ".
                    "temp_midi ".
                    "SET memo='kkbox 沒有資料' ".
                    "WHERE ".
                    "id='$midi_id' ".
                    "LIMIT 1";

      $query_result = $db_obj->updateCommand($update_sql);

      echo "mark song $song_id no data \n";
   }

}// end foreach ($query_result as $query_result_data) {


require_once SITE_ROOT."/p-config/application-unsetter.php";

?>