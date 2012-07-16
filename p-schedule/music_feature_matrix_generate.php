<?php
/**
 * music_feature_matrix_generate.php to generate matrix
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
              "mf.bar_count, ".
              "mf.beat_count, ".
              "mf.tatum_count, ".
              "mf.section_count, ".
              "mf.segment_count, ".
              "mf.bar_avg_second, ".
              "mf.beat_avg_second, ".
              "mf.tatum_avg_second, ".
              "mf.section_avg_second, ".
              "mf.segment_avg_second, ".
              "mf.pitch_avg_vector, ".
              "mf.timbre_avg_vector, ".
              "mf.pitch_std_vector, ".
              "mf.timbre_std_vector, ".
              "s.mode, ".
              "s.tempo, ".
              "s.time_signature, ".
              "s.energy, ".
              "s.danceability, ".
              "s.speechiness, ".
              "s.loudness ".
              "FROM music_feature mf ".
              "INNER JOIN song s ON (mf.song_id = s.id) ".
              "WHERE mf.is_deleted = '0' ".
              "AND s.is_deleted ='0' ".
              "ORDER BY mf.id";

$query_result = $db_obj->selectCommand($select_sql);

$song_id = "";
$music_feature = "bar_count,beat_count,tatum_count,section_count,segment_count,bar_avg_second,beat_avg_second,tatum_avg_second,section_avg_second,segment_avg_second";
$matirx = "";
$augment_music_feature = "pitch_avg_vector1,".
                         "pitch_avg_vector2,".
                         "pitch_avg_vector3,".
                         "pitch_avg_vector4,".
                         "pitch_avg_vector5,".
                         "pitch_avg_vector6,".
                         "pitch_avg_vector7,".
                         "pitch_avg_vector8,".
                         "pitch_avg_vector9,".
                         "pitch_avg_vector10,".
                         "pitch_avg_vector11,".
                         "pitch_avg_vector12,".
                         "timbre_avg_vector1".
                         "timbre_avg_vector2".
                         "timbre_avg_vector3".
                         "timbre_avg_vector4".
                         "timbre_avg_vector5".
                         "timbre_avg_vector6".
                         "timbre_avg_vector7".
                         "timbre_avg_vector8".
                         "timbre_avg_vector9".
                         "timbre_avg_vector10,".
                         "timbre_avg_vector11,".
                         "timbre_avg_vector12,".
                         "pitch_std_vector1,".
                         "pitch_std_vector2,".
                         "pitch_std_vector3,".
                         "pitch_std_vector4,".
                         "pitch_std_vector5,".
                         "pitch_std_vector6,".
                         "pitch_std_vector7,".
                         "pitch_std_vector8,".
                         "pitch_std_vector9,".
                         "pitch_std_vector10,".
                         "pitch_std_vector11,".
                         "pitch_std_vector12,".
                         "timbre_std_vector1,".
                         "timbre_std_vector2,".
                         "timbre_std_vector3,".
                         "timbre_std_vector4,".
                         "timbre_std_vector5,".
                         "timbre_std_vector6,".
                         "timbre_std_vector7,".
                         "timbre_std_vector8,".
                         "timbre_std_vector9,".
                         "timbre_std_vector10,".
                         "timbre_std_vector11,".
                         "timbre_std_vector12";
$augment_matrix = "";

foreach ($query_result as $query_result_data) {

   $song_id =  $song_id.
               $query_result_data['song_id'].",";

   if ($query_result_data['mode']==1) {
      $mode = 10;
   } else {
      $mode = -10;
   }

   $matirx =   $matirx.
               $mode." ".
               $query_result_data['tempo']." ".
               $query_result_data['time_signature']." ".
               $query_result_data['energy']." ".
               $query_result_data['danceability']." ".
               $query_result_data['speechiness']." ".
               $query_result_data['loudness']." ".
               $query_result_data['bar_count']." ".
               $query_result_data['beat_count']." ".
               $query_result_data['tatum_count']." ".
               $query_result_data['section_count']." ".
               $query_result_data['segment_count']." ".
               $query_result_data['bar_avg_second']." ".
               $query_result_data['beat_avg_second']." ".
               $query_result_data['tatum_avg_second']." ".
               $query_result_data['section_avg_second']." ".
               $query_result_data['segment_avg_second'].
               "; ";

   $augment_matrix = $augment_matrix.
                     str_replace(",", " ", $query_result_data['pitch_avg_vector'])." ".
                     str_replace(",", " ", $query_result_data['timbre_avg_vector'])." ".
                     str_replace(",", " ", $query_result_data['pitch_std_vector'])." ".
                     str_replace(",", " ", $query_result_data['timbre_std_vector']).
                     "; ";

}

$song_id = substr ($song_id, 0, -1);
$matrix = substr ($matirx, 0, -2);

$music_feature_matrix_god = new LMMusicFeatureMatrixGod();

$parameter_array = array();
$parameter_array['matrix']
    = $matrix;
$parameter_array['row_song_id']
    = $song_id;
$parameter_array['column_music_feature']
    = $music_feature;
$parameter_array['type']
    = 'matrix';
$parameter_array['augment_matrix']
    = $augment_matrix;
$parameter_array['augment_music_feature']
    = $augment_music_feature;

if ($music_feature_matrix_god->create($parameter_array)) {
   echo "create music feature matrix success \n";
} else {
   echo "create music feature matrix fail \n";
}

unset($music_feature_matrix_god);

require_once SITE_ROOT."/p-config/application-unsetter.php";

?>
