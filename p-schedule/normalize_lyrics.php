<?php
/**
 * normalize_lyrics.php to normalize kkbox lyrics
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
$song_god_obj = new LMSongGod();

$select_sql = "SELECT ".
              "* ".
              "FROM song LIMIT 1";

$query_result = $db_obj->selectCommand($select_sql);

// get unprocess data
foreach ($query_result as $query_result_data) {

   $song_id = $query_result_data['id'];
   $lyric = $query_result_data['lyric'];

   $parse_lyric = explode("\n", $lyric);
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
   $in_lyric = implode("\n", $normalize_lyric_array);

   $song_obj = new LMSong($song_id);
   $song_obj->lyric = $in_lyric;

   if ($song_obj->save()) {

      echo "normalize $song_id \n";

   } else {

      echo "fail normalize $song_id \n";

   }


}// end foreach ($query_result as $query_result_data) {


require_once SITE_ROOT."/p-config/application-unsetter.php";

?>