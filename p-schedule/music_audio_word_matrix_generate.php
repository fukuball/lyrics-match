<?php
/**
 * music_audio_word_matrix_generate.php
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
              "id ".
              "FROM song ".
              "WHERE is_deleted = '0' ".
              "AND audio_path!='' ".
              "AND echonest_track_id!='' ".
              "AND retrieval_status='success' ".
              "ORDER BY id";

$query_result = $db_obj->selectCommand($select_sql);

$music_feature_god = new LMMusicFeatureGod();

// get unprocess data
foreach ($query_result as $query_result_data) {

   $song_obj = new LMSong($query_result_data['id']);
   $echonest_analysis_file = AUDIO_ROOT.'/'.$song_obj->getId().'.json';
   $echonest_analysis = file_get_contents($echonest_analysis_file);
   $echonest_data = json_decode($echonest_analysis);

   $pitch_matrix = '';
   $timbre_matrix = '';
   foreach ($echonest_data->segments as $segments_data) {

      $pitch_vector = '';
      $count_pitch_d = 1;
      foreach ($segments_data->pitches as $pitches_data) {

         switch ($count_pitch_d) {
         default:
            $pitch_vector = $pitch_vector.$pitches_data.' ';
            break;
         case '12':
            $pitch_vector = $pitch_vector.$pitches_data.'; ';
            break;
         }// end switch

         $count_pitch_d++;

      }// end foreach ($segments_data->pitches as $pitches_data)

      echo $pitch_vector;

      $timbre_vector = '';
      $count_timbre_d = 1;
      foreach ($segments_data->timbre as $timbre_data) {

         switch ($count_timbre_d) {
         default:
            $timbre_vector = $timbre_vector.$pitches_data.' ';
            break;
         case '12':
            $timbre_vector = $timbre_vector.$pitches_data.'; ';
            break;
         }// end switch ($count_timbre_d)

         $count_timbre_d++;
      }// end foreach ($segments_data->pitches as $pitches_data)

   }// end foreach ($echonest_data->segments as $segments_data)

   echo $timbre_vector;

   unset($song_obj);
}

unset($music_feature_god);

require_once SITE_ROOT."/p-config/application-unsetter.php";

?>
