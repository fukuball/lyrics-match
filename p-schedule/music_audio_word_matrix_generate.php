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

   $song_section_array = array();
   foreach ($echonest_data->sections as $section_data) {
      array_push($song_section_array, $section_data->start);
   }
   array_push($song_section_array, 100000);

   $song_audio_word_array = array();
   $song_audio_word_count_array = array();

   foreach ($echonest_data->segments as $segments_data) {

      $pitch_start = $segments_data->start;

      foreach ($song_section_array as $key => $section_start) {

         if ($section_start!=100000) {

            if ($pitch_start>=$section_start && $pitch_start<$song_section_array[$key+1]) {

               $song_audio_word_array[$key][0] = $song_audio_word_array[$key][0]+$segments_data->pitches[0];
               $song_audio_word_array[$key][1] = $song_audio_word_array[$key][1]+$segments_data->pitches[1];
               $song_audio_word_array[$key][2] = $song_audio_word_array[$key][2]+$segments_data->pitches[2];
               $song_audio_word_array[$key][3] = $song_audio_word_array[$key][3]+$segments_data->pitches[3];
               $song_audio_word_array[$key][4] = $song_audio_word_array[$key][4]+$segments_data->pitches[4];
               $song_audio_word_array[$key][5] = $song_audio_word_array[$key][5]+$segments_data->pitches[5];
               $song_audio_word_array[$key][6] = $song_audio_word_array[$key][6]+$segments_data->pitches[6];
               $song_audio_word_array[$key][7] = $song_audio_word_array[$key][7]+$segments_data->pitches[7];
               $song_audio_word_array[$key][8] = $song_audio_word_array[$key][8]+$segments_data->pitches[8];
               $song_audio_word_array[$key][9] = $song_audio_word_array[$key][9]+$segments_data->pitches[9];
               $song_audio_word_array[$key][10] = $song_audio_word_array[$key][10]+$segments_data->pitches[10];
               $song_audio_word_array[$key][11] = $song_audio_word_array[$key][11]+$segments_data->pitches[11];

               $song_audio_word_count_array[$key] = $song_audio_word_count_array[$key]+1;
            }

         }

      }

      print_r($song_audio_word_array);

      foreach ($song_audio_word_array as $key=>$song_audio_word_dimenstion_array) {
         $song_audio_word_array[$key][0] = $song_audio_word_dimenstion_array[0]/$song_audio_word_count_array[$key];
         $song_audio_word_array[$key][1] = $song_audio_word_dimenstion_array[1]/$song_audio_word_count_array[$key];
         $song_audio_word_array[$key][2] = $song_audio_word_dimenstion_array[2]/$song_audio_word_count_array[$key];
         $song_audio_word_array[$key][3] = $song_audio_word_dimenstion_array[3]/$song_audio_word_count_array[$key];
         $song_audio_word_array[$key][4] = $song_audio_word_dimenstion_array[4]/$song_audio_word_count_array[$key];
         $song_audio_word_array[$key][5] = $song_audio_word_dimenstion_array[5]/$song_audio_word_count_array[$key];
         $song_audio_word_array[$key][6] = $song_audio_word_dimenstion_array[6]/$song_audio_word_count_array[$key];
         $song_audio_word_array[$key][7] = $song_audio_word_dimenstion_array[7]/$song_audio_word_count_array[$key];
         $song_audio_word_array[$key][8] = $song_audio_word_dimenstion_array[8]/$song_audio_word_count_array[$key];
         $song_audio_word_array[$key][9] = $song_audio_word_dimenstion_array[9]/$song_audio_word_count_array[$key];
         $song_audio_word_array[$key][10] = $song_audio_word_dimenstion_array[10]/$song_audio_word_count_array[$key];
         $song_audio_word_array[$key][11] = $song_audio_word_dimenstion_array[11]/$song_audio_word_count_array[$key];
      }

      print_r($song_audio_word_array);
      print_r($song_audio_word_count_array);

   }// end foreach ($echonest_data->segments as $segments_data)

   unset($song_obj);
}

unset($music_feature_god);

require_once SITE_ROOT."/p-config/application-unsetter.php";

?>
