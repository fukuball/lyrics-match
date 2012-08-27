<?php
/**
 * word_feature_matrix_genereate.php
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

$row_song_id = "";
$lyrics_feature = "";
$matirx = "[";

$select_sql = "SELECT song_id,lyrics_term_vector,lyrics_term_vector_readable FROM lyrics_feature WHERE term_vector_string!='' ORDER BY song_id";

$query_result = $db_obj->selectCommand($select_sql);

$count = 0 ;
foreach ($query_result as $query_result_data) {

   $song_id = $query_result_data['song_id'];
   $row_song_id = $row_song_id.$song_id.',';
   $matirx = $matirx.'['.$query_result_data['lyrics_term_vector'].'],';

   if ($count==0) {
      $lyrics_feature = $query_result_data['lyrics_term_vector_readable'];
   }
   $count++;

   echo "combine $song_id \n";
}

$row_song_id = substr ($row_song_id, 0, -1);
$matirx = substr ($matirx, 0, -1);

$matirx = $matirx."]";

$insert_sql = "INSERT INTO lyrics_feature_matrix (matrix,row_song_id,column_lyrics_feature,type,create_time,modify_time) VALUES ('".addslashes($matirx)."', '".addslashes($row_song_id)."', '".addslashes($lyrics_feature)."', 'matrix', NOW(), NOW())";
$query_result3 = $db_obj->insertCommand($insert_sql);
echo "lyrics matirx feature \n";


require_once SITE_ROOT."/p-config/application-unsetter.php";

?>