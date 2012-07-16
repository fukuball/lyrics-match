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

// get unprocess data
foreach ($query_result as $query_result_data) {

   echo  $query_result_data['bar_count'].", ".
         $query_result_data['beat_count'].", ".
         $query_result_data['tatum_count'].", ".
         $query_result_data['section_count'].", ".
         $query_result_data['segment_count'].", ".
         $query_result_data['bar_avg_second'].", ".
         $query_result_data['beat_avg_second'].", ".
         $query_result_data['tatum_avg_second'].", ".
         $query_result_data['section_avg_second'].", ".
         $query_result_data['segment_avg_second'].", ".
         $query_result_data['pitch_avg_vector'].", ".
         $query_result_data['timbre_avg_vector'].", ".
         $query_result_data['pitch_std_vector'].", ".
         $query_result_data['timbre_std_vector']."<br/>";

}

require_once SITE_ROOT."/p-config/application-unsetter.php";

?>
