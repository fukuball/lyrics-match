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

   $segment_count = count($echonest_data->segments);

   $pitch_d1  = 0;
   $pitch_d2  = 0;
   $pitch_d3  = 0;
   $pitch_d4  = 0;
   $pitch_d5  = 0;
   $pitch_d6  = 0;
   $pitch_d7  = 0;
   $pitch_d8  = 0;
   $pitch_d9  = 0;
   $pitch_d10 = 0;
   $pitch_d11 = 0;
   $pitch_d12 = 0;

   $timbre_d1  = 0;
   $timbre_d2  = 0;
   $timbre_d3  = 0;
   $timbre_d4  = 0;
   $timbre_d5  = 0;
   $timbre_d6  = 0;
   $timbre_d7  = 0;
   $timbre_d8  = 0;
   $timbre_d9  = 0;
   $timbre_d10 = 0;
   $timbre_d11 = 0;
   $timbre_d12 = 0;

   foreach ($echonest_data->segments as $segments_data) {

      $pitch_start = $segments_data->start;

      foreach ($song_section_array as $key => $section_start) {

         if ($section_start!=100000) {

            echo 'pitch_start'.$pitch_start;
            echo 'section_start'.$section_start;
            if ($pitch_start>=$section_start && $pitch_start<$song_section_array[$key+1]) {

               echo 'key'.$key;

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

            }

         }

      }


      $count_pitch_d = 1;
      foreach ($segments_data->pitches as $pitches_data) {

         switch ($count_pitch_d) {
         case '1':
            $pitch_d1 = $pitch_d1+$pitches_data;
            break;
         case '2':
            $pitch_d2 = $pitch_d2+$pitches_data;
            break;
         case '3':
            $pitch_d3 = $pitch_d3+$pitches_data;
         break;
         case '4':
            $pitch_d4 = $pitch_d4+$pitches_data;
            break;
         case '5':
            $pitch_d5 = $pitch_d5+$pitches_data;
            break;
         case '6':
            $pitch_d6 = $pitch_d6+$pitches_data;
            break;
         case '7':
            $pitch_d7 = $pitch_d7+$pitches_data;
            break;
         case '8':
            $pitch_d8 = $pitch_d8+$pitches_data;
            break;
         case '9':
            $pitch_d9 = $pitch_d9+$pitches_data;
            break;
         case '10':
            $pitch_d10 = $pitch_d10+$pitches_data;
            break;
         case '11':
            $pitch_d11 = $pitch_d11+$pitches_data;
            break;
         case '12':
            $pitch_d12 = $pitch_d12+$pitches_data;
            break;
         }// end switch ($count_pitch_d)

         $count_pitch_d++;
      }// end foreach ($segments_data->pitches as $pitches_data)

      $count_timbre_d = 1;
      foreach ($segments_data->timbre as $timbre_data) {

         switch ($count_timbre_d) {
         case '1':
            $timbre_d1 = $timbre_d1+$timbre_data;
            break;
         case '2':
            $timbre_d2 = $timbre_d2+$timbre_data;
            break;
         case '3':
            $timbre_d3 = $timbre_d3+$timbre_data;
         break;
         case '4':
            $timbre_d4 = $timbre_d4+$timbre_data;
            break;
         case '5':
            $timbre_d5 = $timbre_d5+$timbre_data;
            break;
         case '6':
            $timbre_d6 = $timbre_d6+$timbre_data;
            break;
         case '7':
            $timbre_d7 = $timbre_d7+$timbre_data;
            break;
         case '8':
            $timbre_d8 = $timbre_d8+$timbre_data;
            break;
         case '9':
            $timbre_d9 = $timbre_d9+$timbre_data;
            break;
         case '10':
            $timbre_d10 = $timbre_d10+$timbre_data;
            break;
         case '11':
            $timbre_d11 = $timbre_d11+$timbre_data;
            break;
         case '12':
            $timbre_d12 = $timbre_d12+$timbre_data;
            break;
         }// end switch ($count_timbre_d)

         $count_timbre_d++;
      }// end foreach ($segments_data->pitches as $pitches_data)

      print_r($song_audio_word_array);

   }// end foreach ($echonest_data->segments as $segments_data)

   unset($song_obj);
}

unset($music_feature_god);

require_once SITE_ROOT."/p-config/application-unsetter.php";

?>
