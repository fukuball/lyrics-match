<?php
/**
 * echonest_vector_counter.php to count feature vector
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

   $second = $echonest_data->meta->seconds;
   $bar_count = count($echonest_data->bars);
   $beat_count = count($echonest_data->beats);
   $tatum_count = count($echonest_data->tatums);
   $section_count = count($echonest_data->sections);
   $segment_count = count($echonest_data->segments);

   $bar_duration_sum = 0;
   foreach ($echonest_data->bars as $bars_data){
      $bar_duration_sum = $bar_duration_sum+$bars_data->duration;
   }
   $bar_avg_second = $bar_duration_sum/$bar_count;

   $beat_duration_sum = 0;
   foreach ($echonest_data->beats as $beats_data){
      $beat_duration_sum = $beat_duration_sum+$beats_data->duration;
   }
   $beat_avg_second = $beat_duration_sum/$beat_count;

   $tatum_duration_sum = 0;
   foreach ($echonest_data->tatums as $tatums_data){
      $tatum_duration_sum = $tatum_duration_sum+$tatums_data->duration;
   }
   $tatum_avg_second = $tatum_duration_sum/$tatum_count;

   $section_duration_sum = 0;
   foreach ($echonest_data->sections as $sections_data) {
      $section_duration_sum = $section_duration_sum+$sections_data->duration;
   }
   $section_avg_second = $section_duration_sum/$section_count;


   $segment_duration_sum = 0;
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
      $segment_duration_sum = $segment_duration_sum+$segments_data->duration;

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

   }// end foreach ($echonest_data->segments as $segments_data)

   $pitch_a1  = ($pitch_d1/$segment_count);
   $pitch_a2  = ($pitch_d2/$segment_count);
   $pitch_a3  = ($pitch_d3/$segment_count);
   $pitch_a4  = ($pitch_d4/$segment_count);
   $pitch_a5  = ($pitch_d5/$segment_count);
   $pitch_a6  = ($pitch_d6/$segment_count);
   $pitch_a7  = ($pitch_d7/$segment_count);
   $pitch_a8  = ($pitch_d8/$segment_count);
   $pitch_a9  = ($pitch_d9/$segment_count);
   $pitch_a10 = ($pitch_d10/$segment_count);
   $pitch_a11 = ($pitch_d11/$segment_count);
   $pitch_a12 = ($pitch_d12/$segment_count);

   $timbre_a1  = ($timbre_d1/$segment_count);
   $timbre_a2  = ($timbre_d2/$segment_count);
   $timbre_a3  = ($timbre_d3/$segment_count);
   $timbre_a4  = ($timbre_d4/$segment_count);
   $timbre_a5  = ($timbre_d5/$segment_count);
   $timbre_a6  = ($timbre_d6/$segment_count);
   $timbre_a7  = ($timbre_d7/$segment_count);
   $timbre_a8  = ($timbre_d8/$segment_count);
   $timbre_a9  = ($timbre_d9/$segment_count);
   $timbre_a10 = ($timbre_d10/$segment_count);
   $timbre_a11 = ($timbre_d11/$segment_count);
   $timbre_a12 = ($timbre_d12/$segment_count);

   $pitch2_d1  = 0;
   $pitch2_d2  = 0;
   $pitch2_d3  = 0;
   $pitch2_d4  = 0;
   $pitch2_d5  = 0;
   $pitch2_d6  = 0;
   $pitch2_d7  = 0;
   $pitch2_d8  = 0;
   $pitch2_d9  = 0;
   $pitch2_d10 = 0;
   $pitch2_d11 = 0;
   $pitch2_d12 = 0;

   $timbre2_d1  = 0;
   $timbre2_d2  = 0;
   $timbre2_d3  = 0;
   $timbre2_d4  = 0;
   $timbre2_d5  = 0;
   $timbre2_d6  = 0;
   $timbre2_d7  = 0;
   $timbre2_d8  = 0;
   $timbre2_d9  = 0;
   $timbre2_d10 = 0;
   $timbre2_d11 = 0;
   $timbre2_d12 = 0;

   foreach ($echonest_data->segments as $segments_data) {

      $count_pitch_d = 1;
      foreach ($segments_data->pitches as $pitches_data) {

         switch ($count_pitch_d) {
         case '1':
            $pitch2_d1 = $pitch2_d1+pow(($pitches_data-$pitch_a1),2);
            break;
         case '2':
            $pitch2_d2 = $pitch2_d2+pow(($pitches_data-$pitch_a2),2);
            break;
         case '3':
            $pitch2_d3 = $pitch2_d3+pow(($pitches_data-$pitch_a3),2);
         break;
         case '4':
            $pitch2_d4 = $pitch2_d4+pow(($pitches_data-$pitch_a4),2);
            break;
         case '5':
            $pitch2_d5 = $pitch2_d5+pow(($pitches_data-$pitch_a5),2);
            break;
         case '6':
            $pitch2_d6 = $pitch2_d6+pow(($pitches_data-$pitch_a6),2);
            break;
         case '7':
            $pitch2_d7 = $pitch2_d7+pow(($pitches_data-$pitch_a7),2);
            break;
         case '8':
            $pitch2_d8 = $pitch2_d8+pow(($pitches_data-$pitch_a8),2);
            break;
         case '9':
            $pitch2_d9 = $pitch2_d9+pow(($pitches_data-$pitch_a9),2);
            break;
         case '10':
            $pitch2_d10 = $pitch2_d10+pow(($pitches_data-$pitch_a10),2);
            break;
         case '11':
            $pitch2_d11 = $pitch2_d11+pow(($pitches_data-$pitch_a11),2);
            break;
         case '12':
            $pitch2_d12 = $pitch2_d12+pow(($pitches_data-$pitch_a12),2);
            break;
         }// end switch ($count_pitch_d)

         $count_pitch_d++;
      }// end foreach ($segments_data->pitches as $pitches_data)

      $count_timbre_d = 1;
      foreach ($segments_data->timbre as $timbre_data) {

         switch ($count_timbre_d) {
         case '1':
            $timbre2_d1 = $timbre2_d1+pow(($timbre_data-$timbre_a1),2);
            break;
         case '2':
            $timbre2_d2 = $timbre2_d2+pow(($timbre_data-$timbre_a2),2);
            break;
         case '3':
            $timbre2_d3 = $timbre2_d3+pow(($timbre_data-$timbre_a3),2);
         break;
         case '4':
            $timbre2_d4 = $timbre2_d4+pow(($timbre_data-$timbre_a4),2);
            break;
         case '5':
            $timbre2_d5 = $timbre2_d5+pow(($timbre_data-$timbre_a5),2);
            break;
         case '6':
            $timbre2_d6 = $timbre2_d6+pow(($timbre_data-$timbre_a6),2);
            break;
         case '7':
            $timbre2_d7 = $timbre2_d7+pow(($timbre_data-$timbre_a7),2);
            break;
         case '8':
            $timbre2_d8 = $timbre2_d8+pow(($timbre_data-$timbre_a8),2);
            break;
         case '9':
            $timbre2_d9 = $timbre2_d9+pow(($timbre_data-$timbre_a9),2);
            break;
         case '10':
            $timbre2_d10 = $timbre2_d10+pow(($timbre_data-$timbre_a10),2);
            break;
         case '11':
            $timbre2_d11 = $timbre2_d11+pow(($timbre_data-$timbre_a11),2);
            break;
         case '12':
            $timbre2_d12 = $timbre2_d12+pow(($timbre_data-$timbre_a12),2);
            break;
         }// end switch ($count_timbre_d)

         $count_timbre_d++;
      }// end foreach ($segments_data->timbre as $timbre_data)

   }// end foreach ($echonest_data->segments as $segments_data)

   $segment_avg_second = $segment_duration_sum/$segment_count;
   $pitch_avg_vector = $pitch_a1.','.
                       $pitch_a2.','.
                       $pitch_a3.','.
                       $pitch_a4.','.
                       $pitch_a5.','.
                       $pitch_a6.','.
                       $pitch_a7.','.
                       $pitch_a8.','.
                       $pitch_a9.','.
                       $pitch_a10.','.
                       $pitch_a11.','.
                       $pitch_a12;

   $timbre_avg_vector = $timbre_a1.','.
                        $timbre_a2.','.
                        $timbre_a3.','.
                        $timbre_a4.','.
                        $timbre_a5.','.
                        $timbre_a6.','.
                        $timbre_a7.','.
                        $timbre_a8.','.
                        $timbre_a9.','.
                        $timbre_a10.','.
                        $timbre_a11.','.
                        $timbre_a12;

   $pitch_std_vector = sqrt($pitch2_d1/($segment_count-1)).','.
                       sqrt($pitch2_d2/($segment_count-1)).','.
                       sqrt($pitch2_d3/($segment_count-1)).','.
                       sqrt($pitch2_d4/($segment_count-1)).','.
                       sqrt($pitch2_d5/($segment_count-1)).','.
                       sqrt($pitch2_d6/($segment_count-1)).','.
                       sqrt($pitch2_d7/($segment_count-1)).','.
                       sqrt($pitch2_d8/($segment_count-1)).','.
                       sqrt($pitch2_d9/($segment_count-1)).','.
                       sqrt($pitch2_d10/($segment_count-1)).','.
                       sqrt($pitch2_d11/($segment_count-1)).','.
                       sqrt($pitch2_d12/($segment_count-1));

   $timbre_std_vector = sqrt($timbre2_d1/($segment_count-1)).','.
                        sqrt($timbre2_d2/($segment_count-1)).','.
                        sqrt($timbre2_d3/($segment_count-1)).','.
                        sqrt($timbre2_d4/($segment_count-1)).','.
                        sqrt($timbre2_d5/($segment_count-1)).','.
                        sqrt($timbre2_d6/($segment_count-1)).','.
                        sqrt($timbre2_d7/($segment_count-1)).','.
                        sqrt($timbre2_d8/($segment_count-1)).','.
                        sqrt($timbre2_d9/($segment_count-1)).','.
                        sqrt($timbre2_d10/($segment_count-1)).','.
                        sqrt($timbre2_d11/($segment_count-1)).','.
                        sqrt($timbre2_d12/($segment_count-1));

   echo "second: $second \n";
   echo "bar_count: $bar_count \n";
   echo "beat_count: $beat_count \n";
   echo "tatum_count: $tatum_count \n";
   echo "section_count: $section_count \n";
   echo "segment_count: $segment_count \n";
   echo "bar_avg_second: $bar_avg_second \n";
   echo "beat_avg_second: $beat_avg_second \n";
   echo "tatum_avg_second: $tatum_avg_second \n";
   echo "section_avg_second: $section_avg_second \n";
   echo "segment_avg_second: $segment_avg_second \n";
   echo "pitch_avg_vector: $pitch_avg_vector \n";
   echo "timbre_avg_vector: $timbre_avg_vector \n";
   echo "pitch_std_vector: $pitch_std_vector \n";
   echo "timbre_std_vector: $timbre_std_vector \n";

   $music_feature_id = $music_feature_god->findBySongId($song_obj->getId());
   if ($music_feature_id) {
      $music_feature_obj = new LMMusicFeature($music_feature_id);

      $music_feature_obj->second = $second;
      $music_feature_obj->bar_count = $bar_count;
      $music_feature_obj->beat_count = $beat_count;
      $music_feature_obj->tatum_count = $tatum_count;
      $music_feature_obj->section_count = $section_count;
      $music_feature_obj->segment_count = $segment_count;
      $music_feature_obj->bar_avg_second = $bar_avg_second;
      $music_feature_obj->beat_avg_second = $beat_avg_second;
      $music_feature_obj->tatum_avg_second = $tatum_avg_second;
      $music_feature_obj->section_avg_second = $section_avg_second;
      $music_feature_obj->segment_avg_second = $segment_avg_second;
      $music_feature_obj->pitch_avg_vector = $pitch_avg_vector;
      $music_feature_obj->timbre_avg_vector = $timbre_avg_vector;
      $music_feature_obj->pitch_std_vector = $pitch_std_vector;
      $music_feature_obj->timbre_std_vector = $timbre_std_vector;
      if ($music_feature_obj->save()) {
         echo "update music feature success \n";
      } else {
         echo "update music feature fail \n";
      }

   } else {

      $parameter_array = array();
      $parameter_array['song_id']
          = $song_obj->getId();
      $parameter_array['second']
          = $second;
      $parameter_array['bar_count']
          = $bar_count;
      $parameter_array['beat_count']
          = $beat_count;
      $parameter_array['tatum_count']
          = $tatum_count;
      $parameter_array['section_count']
          = $section_count;
      $parameter_array['segment_count']
          = $segment_count;
      $parameter_array['bar_avg_second']
          = $bar_avg_second;
      $parameter_array['beat_avg_second']
          = $beat_avg_second;
      $parameter_array['tatum_avg_second']
          = $tatum_avg_second;
      $parameter_array['section_avg_second']
          = $section_avg_second;
      $parameter_array['segment_avg_second']
          = $segment_avg_second;
      $parameter_array['pitch_avg_vector']
          = $pitch_avg_vector;
      $parameter_array['timbre_avg_vector']
          = $timbre_avg_vector;
      $parameter_array['pitch_std_vector']
          = $pitch_std_vector;
      $parameter_array['timbre_std_vector']
          = $timbre_std_vector;

      if ($music_feature_god->create($parameter_array)) {
         echo "create music feature success \n";
      } else {
         echo "create music feature fail \n";
      }

   }

   unset($song_obj);
}

unset($music_feature_god);

require_once SITE_ROOT."/p-config/application-unsetter.php";

?>
