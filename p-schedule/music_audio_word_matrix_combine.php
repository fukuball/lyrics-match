<?php
/**
 * music_audio_word_matrix_combine.php
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


$db_obj = LMDBAccess::getInstance();

$select_sql = "SELECT ".
              "pitch_audio_word, timbre_audio_word ".
              "FROM music_feature ".
              "ORDER BY id";

$query_result = $db_obj->selectCommand($select_sql);

// get unprocess data
$p_audio_word_array = array();
$t_audio_word_array = array();

foreach ($query_result as $query_result_data) {

   $pitch_audio_word = $query_result_data['pitch_audio_word'];
   $pitch_audio_word_array = json_decode($pitch_audio_word);
   foreach ($pitch_audio_word_array as $key=>$value) {
      array_push($p_audio_word_array, $value);
   }

   $timbre_audio_word = $query_result_data['timbre_audio_word'];
   $timbre_audio_word_array = json_decode($timbre_audio_word);
   foreach ($timbre_audio_word_array as $key=>$value) {
      array_push($t_audio_word_array, $value);
   }

}

$p_audio_word = json_encode($p_audio_word_array);
$t_audio_word = json_encode($t_audio_word_array);

echo $t_audio_word;

require_once SITE_ROOT."/p-config/application-unsetter.php";

?>
