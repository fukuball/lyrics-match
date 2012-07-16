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
              "* ".
              "FROM music_feature ".
              "WHERE is_deleted = '0' ".
              "ORDER BY id";

$query_result = $db_obj->selectCommand($select_sql);

$song_id = "";
$music_feature = "bar_count,beat_count,tatum_count,section_count,segment_count,bar_avg_second,beat_avg_second,tatum_avg_second,section_avg_second,segment_avg_second";
$matirx = "";

foreach ($query_result as $query_result_data) {

   $song_id =  $song_id.
               $query_result_data['song_id'].",";

   $matirx =   $matirx.
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
               //$query_result_data['pitch_avg_vector']." ".
               //$query_result_data['timbre_avg_vector']." ".
               //$query_result_data['pitch_std_vector']." ".
               //$query_result_data['timbre_std_vector']." "
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

if ($music_feature_matrix_god->create($parameter_array)) {
   echo "create music feature matrix success \n";
} else {
   echo "create music feature matrix fail \n";
}

unset($music_feature_matrix_god);

require_once SITE_ROOT."/p-config/application-unsetter.php";

?>
