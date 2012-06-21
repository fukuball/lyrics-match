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

   echo "second: $second \n";
   echo "bar_count: $bar_count \n";
   echo "beat_count: $beat_count \n";
   echo "tatum_count: $tatum_count \n";
   echo "section_count: $section_count \n";
   echo "segment_count: $segment_count \n";
   echo "bar_avg_second: $bar_avg_second \n";

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
