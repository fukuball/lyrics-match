<?php
/**
 * word_feature_genereate.php
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

$select_sql = "SELECT id FROM song WHERE lyric!='' ORDER BY id";

$query_result = $db_obj->selectCommand($select_sql);
$document_num = 0;
foreach ($query_result as $query_result_data) {

   $song_id = $query_result_data['id'];
   $select_sql = "SELECT ltu.*,IF(ltt.tfidf IS NULL, 0, ltt.tfidf) tfidf FROM lyrics_term_unique ltu LEFT JOIN lyrics_term_tfidf ltt ON (ltu.term=ltt.term AND ltt.song_id='$song_id')";

   $query_result2 = $db_obj->selectCommand($select_sql);

   $term_vector_string = '';
   $term_vector_readable_string = '';
   foreach ($query_result2 as $query_result_data2) {

      $term_vector_string = $term_vector_string.$query_result_data2['tfidf'].',';
      $term_vector_readable_string = $term_vector_readable_string.$query_result_data2['term'].',';

   }

   $term_vector_string = substr ($term_vector_string, 0, -1);
   $term_vector_readable_string = substr ($term_vector_readable_string, 0, -1);

   $insert_sql = "INSERT INTO lyrics_feature (song_id,lyrics_term_vector,lyrics_term_vector_readable,create_time,modify_time) VALUES ('".addslashes($song_id)."', '".addslashes($term_vector_string)."', '".addslashes($term_vector_readable_string)."', NOW(), NOW())";
   $query_result3 = $db_obj->insertCommand($insert_sql);
   echo $song_id." lyrics feature \n";

}



require_once SITE_ROOT."/p-config/application-unsetter.php";

?>